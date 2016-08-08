<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Generator;

class NonceGenerator implements NonceGeneratorInterface
{
    /**
     * @var int
     */
    const MIN_NONCE_LENGTH = 16;

    /**
     * @var int
     */
    const MAX_NONCE_LENGTH = 40;

    /**
     * @return string
     */
    public function generate()
    {
        $length = rand(self::MIN_NONCE_LENGTH, self::MAX_NONCE_LENGTH);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';

        for ($p = 0; $p < $length; $p++) {
            $string .= substr($characters, rand(0, strlen($characters)), 1);
        }

        return $string;
    }
}