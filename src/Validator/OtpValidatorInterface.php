<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Validator;

interface OtpValidatorInterface
{
    /**
     * @param $otp
     * @param string $yubikey
     * @return bool
     */
    public function validate($otp, $yubikey = null);
}
