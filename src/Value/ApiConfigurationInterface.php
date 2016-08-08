<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Value;

interface ApiConfigurationInterface
{
    /**
     * @return string
     */
    public function getApiSecret();

    /**
     * @return array
     */
    public function getValidationServers();

    /**
     * @return string
     */
    public function getClientId();

    /**
     * @return int
     */
    public function getSyncLevel();

    /**
     * @return int
     */
    public function getTimeout();

    /**
     * @return boolean
     */
    public function getTranslateOtp();

    /**
     * @return boolean
     */
    public function getUseHttps();

    /**
     * @return boolean
     */
    public function getUseTimestamp();
}