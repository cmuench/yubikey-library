<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Extractor;

use Psr\Http\Message\ResponseInterface;

class PartsExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $responseMock;

    /**
     * @var PartsExtractor
     */
    private $partsExtractor;

    protected function setUp()
    {
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();

        $this->partsExtractor = new PartsExtractor();
    }

    public function testExtract()
    {
        $expectedParts = [
            'h' => 'vjhFxZrNHB5CjI6vhuSeF2n46a8',
            't' => '2010-09-23T20:34:51Z0678',
            'otp' => 'cccccccbchdifctrndncchkftchjlnbhvhtugdljibej',
            'nonce' => 'somesendrandomstring',
            'sl' => '75',
            'status' => 'OK',
        ];

        $body = '';
        foreach ($expectedParts as $key => $value) {
            $body .= sprintf("%s=%s\r\n", $key, $value);
        }

        $this->responseMock->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        $this->assertEquals($expectedParts, $this->partsExtractor->extract($this->responseMock));
    }
}
