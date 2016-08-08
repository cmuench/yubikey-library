<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Validator;

class OtpValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $yubikey = str_repeat('c', 12);
        $otp = $yubikey . str_repeat('B', 32);

        $validator = new OtpValidator();
        $this->assertTrue($validator->validate($otp, $yubikey));
    }

    public function testWrongYubikey()
    {
        $yubikey = str_repeat('c', 12);
        $otp = str_repeat('B', 32); // missing yubikey at the front of string

        $validator = new OtpValidator();
        $this->assertFalse($validator->validate($otp, $yubikey));
    }
}
