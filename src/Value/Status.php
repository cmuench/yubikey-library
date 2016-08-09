<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Value;

class Status
{
    /**
     * @var string
     */
    const STATUS_UNDEFINED = 'UNDEFINED';

    /**
     * @var string
     */
    const STATUS_OK = 'OK';

    /**
     * @var string
     */
    const STATUS_BAD_OTP = 'BAD_OTP';

    /**
     * @var string
     */
    const STATUS_REPLAYED_OTP = 'REPLAYED_OTP';

    /**
     * @var string
     */
    const STATUS_BAD_SIGNATURE = 'BAD_SIGNATURE';

    /**
     * @var string
     */
    const STATUS_MISSING_PARAMETER = 'MISSING_PARAMETER';

    /**
     * @var string
     */
    const STATUS_NO_SUCH_CLIENT = 'NO_SUCH_CLIENT';

    /**
     * @var string
     */
    const STATUS_OPERATION_NOT_ALLOWED = 'OPERATION_NOT_ALLOWED';

    /**
     * @var string
     */
    const STATUS_BACKEND_ERROR = 'BACKEND_ERROR';

    /**
     * @var string
     */
    const STATUS_NOT_ENOUGH_ANSWERS = 'NOT_ENOUGH_ANSWERS';

    /**
     * @var string
     */
    const STATUS_REPLAYED_REQUEST = 'REPLAYED_REQUEST';

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
