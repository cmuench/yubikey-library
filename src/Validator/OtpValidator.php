<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Validator;

class OtpValidator implements OtpValidatorInterface
{
    /**
     * Length of the yubikey
     */
    const YUBKEY_LENGTH = 12;

    /**
     * @param $otp
     * @param string $yubikey
     * @return bool
     */
    public function validate($otp, $yubikey = null)
    {
        $yubikeyIsValid = true;

        $formatIsValid = preg_match("/^[cbdefghijklnrtuvCBDEFGHIJKLNRTUV]{44}$/", $otp);

        if ($yubikey != null) {
            // Check if first 12 chars match yubikey of user
            if (substr($otp, 0, self::YUBKEY_LENGTH) !== $yubikey) {
                $yubikeyIsValid = false;
            }
        }

        return $formatIsValid && $yubikeyIsValid;
    }
}