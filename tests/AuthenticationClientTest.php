<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey;

use CMuench\Yubikey\Validator\OtpValidator;
use CMuench\Yubikey\Validator\OtpValidatorInterface;
use CMuench\Yubikey\Value\ApiConfigurationInterface;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $otp = 'abc123';

    /**
     * @var string
     */
    private $yubikey = 'yubikey123456';

    /**
     * @var OtpValidatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $otpValidator;

    /**
     * @var ApiConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $apiConfigurationMock;

    /**
     * @var AuthenticationClient
     */
    private $authenticationClient;

    /**
     * @var RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestFactoryMock;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClientMock;

    protected function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->getMock();

        $this->requestFactoryMock = $this->getMockBuilder(RequestFactory::class)
            ->getMock();

        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->getMock();

        $this->requestFactoryMock->expects($this->any())
            ->method('createRequest')
            ->willReturn($this->requestMock);

        $this->apiConfigurationMock = $this->getMockBuilder(ApiConfigurationInterface::class)
            ->getMock();

        $this->apiConfigurationMock->expects($this->any())
            ->method('getValidationServers')
            ->willReturn([
                'http://example.com/server1',
                'http://example.com/server2',
                'http://example.com/server3'
            ]);

        $this->apiConfigurationMock->expects($this->any())
            ->method('isUseTimestamp')
            ->willReturn(true);

        $this->otpValidator = $this->getMockBuilder(OtpValidator::class)
            ->getMock();

        $this->authenticationClient = new AuthenticationClient(
            $this->apiConfigurationMock,
            $this->httpClientMock,
            $this->requestFactoryMock,
            $this->otpValidator
        );
    }

    public function testWrongOtp()
    {
        $this->otpValidator->expects($this->once())->method('validate')->willReturn(false);
        $this->assertFalse($this->authenticationClient->verify($this->otp, $this->yubikey));
    }

    /**
     * Simulate server error
     *
     * @test
     */
    public function testErrorResponse()
    {
        $this->otpValidator->expects($this->once())->method('validate')->willReturn(true);

        $responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();

        $responseMock->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(500);

        $this->httpClientMock->expects($this->any())
            ->method('sendRequest')
            ->willReturn($responseMock);

        $this->assertFalse($this->authenticationClient->verify($this->otp, $this->yubikey));
    }

    /**
     * @test
     */
    public function testOkResponse()
    {
        $this->otpValidator->expects($this->once())->method('validate')->willReturn(true);

        $responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();

        $responseMock->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(200);

        $responseMock->expects($this->any())
            ->method('getBody')
            ->willReturn('status=OK');

        $this->httpClientMock->expects($this->any())
            ->method('sendRequest')
            ->willReturn($responseMock);

        $this->assertTrue($this->authenticationClient->verify($this->otp, $this->yubikey));
    }

    /**
     * @test
     */
    public function testBackendErrorResponse()
    {
        $this->otpValidator->expects($this->once())->method('validate')->willReturn(true);

        $responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();

        $responseMock->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(200);

        $responseMock->expects($this->any())
            ->method('getBody')
            ->willReturn('status=BACKEND_ERROR');

        $this->httpClientMock->expects($this->any())
            ->method('sendRequest')
            ->willReturn($responseMock);

        $this->assertFalse($this->authenticationClient->verify($this->otp, $this->yubikey));
    }

    /**
     * @test
     */
    public function testExceptionDuringHttpRequest()
    {
        $this->otpValidator->expects($this->once())->method('validate')->willReturn(true);

        $this->httpClientMock->expects($this->any())
            ->method('sendRequest')
            ->willThrowException(new \RuntimeException('Some error message'));

        $this->assertFalse($this->authenticationClient->verify($this->otp, $this->yubikey));
    }
}