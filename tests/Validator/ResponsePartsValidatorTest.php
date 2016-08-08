<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Validator;

class ResponsePartsValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $parts
     * @param string $otp
     * @param bool $expected
     *
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $parts, $otp, $expected)
    {
        $validator = new ResponsePartsValidator();

        $this->assertEquals($expected, $validator->validate($parts, $otp));
    }

    /**
     * @return array
     */
    public function validateDataProvider()
    {
        $otp = str_repeat('c', 44);
        $wrongOtp = str_repeat('c', 43);

        return [
            'correct_otp' => [
                ['status' => 'OK', 'otp' => $otp, 'nonce' => 'abc123'], $otp, true
            ],
            'incorrect_otp' => [
                ['status' => 'OK', 'otp' => $otp, 'nonce' => 'abc123'], $wrongOtp, false
            ],
            'nonce_not_permitted' => [
                ['status' => 'OK', 'otp' => $otp], $otp, false
            ],
        ];
    }
}
