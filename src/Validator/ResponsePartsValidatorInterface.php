<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Validator;

interface ResponsePartsValidatorInterface
{
    /**
     * @param array $parts
     * @param string $otp
     *
     * @return bool
     */
    public function validate(array $parts, $otp);
}