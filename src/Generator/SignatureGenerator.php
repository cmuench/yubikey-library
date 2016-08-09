<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Generator;

class SignatureGenerator implements SignatureGeneratorInterface
{
    /**
     * Generates a signature by given url parameters
     *
     * @param array $data
     * @param string $apiSecret
     * @return string
     */
    public function generate(array $data, $apiSecret)
    {
        return base64_encode(hash_hmac('sha1', http_build_query($data), base64_decode($apiSecret), true));
    }
}
