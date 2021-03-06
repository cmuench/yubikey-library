<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey;

use CMuench\Yubikey\Extractor\PartsExtractor;
use CMuench\Yubikey\Generator\NonceGenerator;
use CMuench\Yubikey\Generator\NonceGeneratorInterface;
use CMuench\Yubikey\Generator\SignatureGenerator;
use CMuench\Yubikey\Generator\SignatureGeneratorInterface;
use CMuench\Yubikey\Validator\OtpValidator;
use CMuench\Yubikey\Validator\OtpValidatorInterface;
use CMuench\Yubikey\Validator\ResponsePartsValidator;
use CMuench\Yubikey\Validator\ResponsePartsValidatorInterface;
use CMuench\Yubikey\Value\ApiConfigurationInterface;
use CMuench\Yubikey\Value\Status;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class AuthenticationClient implements AuthenticationClientInterface
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ApiConfigurationInterface
     */
    private $apiConfiguration;

    /**
     * @var OtpValidator
     */
    private $otpValidator;

    /**
     * @var NonceGeneratorInterface
     */
    private $nonceGenerator;

    /**
     * @var SignatureGeneratorInterface
     */
    private $signatureGenerator;

    /**
     * @var ResponsePartsValidatorInterface
     */
    private $responsePartsValidator;

    /**
     * @var PartsExtractor
     */
    private $partsExtractor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AuthenticationClient constructor.
     *
     * @param ApiConfigurationInterface       $apiConfiguration
     * @param HttpClient                      $httpClient
     * @param RequestFactory                  $requestFactory
     * @param LoggerInterface                 $logger
     * @param OtpValidatorInterface           $otpValidator
     * @param NonceGeneratorInterface         $nonceGenerator
     * @param SignatureGeneratorInterface     $signatureGenerator
     * @param ResponsePartsValidatorInterface $responsePartsValidator
     */
    public function __construct(
        ApiConfigurationInterface $apiConfiguration,
        HttpClient $httpClient,
        RequestFactory $requestFactory,
        LoggerInterface $logger,
        OtpValidatorInterface $otpValidator = null,
        NonceGeneratorInterface $nonceGenerator = null,
        SignatureGeneratorInterface $signatureGenerator = null,
        ResponsePartsValidatorInterface $responsePartsValidator = null
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->apiConfiguration = $apiConfiguration;

        $this->otpValidator = $otpValidator;
        if ($otpValidator === null) {
            $this->otpValidator = new OtpValidator();
        }

        $this->nonceGenerator = $nonceGenerator;
        if ($nonceGenerator === null) {
            $this->nonceGenerator = new NonceGenerator();
        }

        $this->signatureGenerator = $signatureGenerator;
        if ($signatureGenerator === null) {
            $this->signatureGenerator = new SignatureGenerator();
        }

        $this->responsePartsValidator = $responsePartsValidator;
        if ($responsePartsValidator === null) {
            $this->responsePartsValidator = new ResponsePartsValidator();
        }

        $this->partsExtractor = new PartsExtractor();
        $this->logger = $logger;
    }

    /**
     * @param string $otp
     * @param string $yubikey
     *
     * @return bool
     */
    public function verify($otp, $yubikey = null)
    {
        if (!$this->otpValidator->validate($otp, $yubikey)) {
            return false;
        }

        $response = null;
        $status = new Status(Status::STATUS_UNDEFINED);
        $parts = [];

        $queryString = $this->createQueryString($otp);

        foreach ($this->apiConfiguration->getValidationServers() as $apiUrl) {
            try {
                $response = $this->callValidationServer($apiUrl, $queryString);

                if ($response->getStatusCode() == 200) {
                    $parts = $this->partsExtractor->extract($response);

                    $status = new Status($parts['status']);
                }

                $this->logger->debug(sprintf('got response with status: %s', $status->getCode()));

                // Status OK
                if ($status->getCode() == Status::STATUS_OK) {
                    return true;
                }

                // Sometimes yubico sends a backend error status
                // try next server.
                if ($status->getCode() == Status::STATUS_BACKEND_ERROR) {
                    continue;
                }

                return $this->responsePartsValidator->validate($parts, $otp);
            } catch (\Exception $e) {
                $this->logger->error(
                    $e->getMessage(),
                    [
                        'response' => $response,
                        'status' => $status,
                        'parts' => $parts,
                    ]
                );

                continue; // Take next URL
            }
        }

        return false;
    }

    /**
     * @param int $otp
     *
     * @return string
     */
    private function createQueryString($otp)
    {
        $data = array(
            'id' => $this->apiConfiguration->getClientId(),
            'otp' => $otp,
            'nonce' => $this->nonceGenerator->generate(),
        );

        if ($this->apiConfiguration->isUseTimestamp()) {
            $data['timestamp'] = 1;
        }

        $data['sl'] = $this->apiConfiguration->getSyncLevel();
        $data['timeout'] = $this->apiConfiguration->getTimeout();
        ksort($data);

        foreach ($data as &$value) {
            $value = urlencode($value);
        }

        $data['h'] = $this->signatureGenerator->generate($data, $this->apiConfiguration->getApiSecret());

        return http_build_query($data);
    }

    /**
     * @param string $apiUrl
     * @param string $queryString
     *
     * @return ResponseInterface
     */
    private function callValidationServer($apiUrl, $queryString)
    {
        $apiServerUrl = ($this->apiConfiguration->isUseHttps() ? 'https://' : 'http://')
            .$apiUrl
            .'?'
            .$queryString;

        $this->logger->debug(sprintf('call URL: %s', $apiServerUrl));

        $request = $this->requestFactory->createRequest(
            'POST',
            $apiServerUrl,
            ['User-Agent' => 'cmuench/yubikey']
        );

        return $this->httpClient->sendRequest($request);
    }
}
