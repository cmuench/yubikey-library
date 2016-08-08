<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Extractor;

use Psr\Http\Message\ResponseInterface;

class PartsExtractor
{
    /**
     * Extract the status from response string
     *
     * Response looks like this:
     *
     * h=vjhFxZrNHB5CjI6vhuSeF2n46a8=
     * t=2010-09-23T20:34:51Z0678
     * otp=cccccccbchdifctrndncchkftchjlnbhvhtugdljibej
     * nonce=somesendrandomstring
     * sl=75
     * status=OK
     *
     * @param ResponseInterface $response
     * @return array
     */
    public function extract(ResponseInterface $response)
    {
        $message = $response->getBody();
        $parts = [];

        foreach (explode("\r\n", trim($message)) as $line) {
            list($key, $value) = explode('=', $line);
            $parts[$key] = $value;
        }

        return $parts;
    }
}