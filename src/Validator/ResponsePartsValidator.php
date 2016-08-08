<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Validator;

class ResponsePartsValidator implements ResponsePartsValidatorInterface
{
    /**
     * @param array $parts
     * @param string $otp
     *
     * @return bool
     */
    public function validate(array $parts, $otp)
    {
        // Check if response contains OTP and nonce
        if (!isset($parts['otp']) || !isset($parts['nonce'])) {
            return false;
        }

        // Check if send yubikey is same as received yubikey.
        // This must be done to prevent "cut & paste" attacks.
        if ($parts['otp'] != $otp) {
            return false;
        }

        return true;
    }
}