<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Value;

class ApiConfiguration implements ApiConfigurationInterface
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var bool
     */
    private $useHttps;

    /**
     * Sync level in percentage between 0 and 100 or "fast" or "secure"
     *
     * @var int
     */
    private $syncLevel;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var bool
     */
    private $useTimestamp;

    /**
     * @var string[]
     */
    private $validationServers = [
        'api.yubico.com/wsapi/2.0/verify',
        'api2.yubico.com/wsapi/2.0/verify',
        'api3.yubico.com/wsapi/2.0/verify',
        'api4.yubico.com/wsapi/2.0/verify',
        'api5.yubico.com/wsapi/2.0/verify'
    ];
    /**
     * @var bool|Mekras\OData\Client\Document\EntryDocument
     */
    private $translateOtp;

    /**
     * ApiConfig constructor.
     *
     * @param string $clientId
     * @param string $apiSecret
     * @param string[] $validationServers
     * @param bool $useHttps
     * @param int $syncLevel
     * @param int $timeout
     * @param bool $useTimestamp
     * @param bool $translateOtp
     */
    public function __construct($clientId, $apiSecret, $validationServers = null, $useHttps = true, $syncLevel = 50,
        $timeout = 10, $useTimestamp = true, $translateOtp = false
    ) {
        $this->clientId = $clientId;
        $this->apiSecret = $apiSecret;
        $this->useHttps = $useHttps;
        $this->syncLevel = $syncLevel;
        $this->timeout = $timeout;
        $this->useTimestamp = $useTimestamp;
        $this->translateOtp = $translateOtp;

        if ($validationServers !== null) {
            $this->validationServers = $validationServers;
        }
    }

    /**
     * @return string
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @return array
     */
    public function getValidationServers()
    {
        return $this->validationServers;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return int
     */
    public function getSyncLevel()
    {
        return $this->syncLevel;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return bool
     */
    public function isTranslateOtp()
    {
        return $this->translateOtp;
    }

    /**
     * @return bool
     */
    public function isUseHttps()
    {
        return $this->useHttps;
    }

    /**
     * @return bool
     */
    public function isUseTimestamp()
    {
        return $this->useTimestamp;
    }
}
