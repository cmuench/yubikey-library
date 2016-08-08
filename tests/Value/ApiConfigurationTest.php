<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Value;

class ApiConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testApiConfiguration()
    {
        $clientId = 'client_id';
        $apiSecret = 'api_secrete';
        $validationServers = ['http://example.com/1', 'http://example.com/2'];

        $configuration = new ApiConfiguration(
            $clientId,
            $apiSecret,
            $validationServers,
            false,
            100,
            1000,
            false,
            true
        );

        $this->assertSame($clientId, $configuration->getClientId());
        $this->assertSame($apiSecret, $configuration->getApiSecret());
        $this->assertSame($validationServers, $configuration->getValidationServers());
        $this->assertFalse($configuration->isUseHttps());
        $this->assertEquals(100, $configuration->getSyncLevel());
        $this->assertEquals(1000, $configuration->getTimeout());
        $this->assertFalse($configuration->isUseTimestamp());
        $this->assertTrue($configuration->isTranslateOtp());
    }
}
