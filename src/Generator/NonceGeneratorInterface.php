<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Generator;

interface NonceGeneratorInterface
{
    /**
     * @return string
     */
    public function generate();
}
