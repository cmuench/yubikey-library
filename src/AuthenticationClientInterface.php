<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey;

interface AuthenticationClientInterface
{
    /**
     * @param string $otp
     * @param string $yubikey
     *
     * @return bool
     */
    public function verify($otp, $yubikey = null);
}
