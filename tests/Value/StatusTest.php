<?php
/**
 * @copyright Copyright (c) 1999-2016 netz98 new media GmbH (http://www.netz98.de)
 *
 * @see PROJECT_LICENSE.txt
 */

namespace CMuench\Yubikey\Value;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $code
     *
     * @test
     * @dataProvider statusCodeProvider
     */
    public function testStatusCode($code)
    {
        $statusValueObject = new Status($code);

        $this->assertSame($code, $statusValueObject->getCode());
    }

    /**
     * @return array
     */
    public function statusCodeProvider()
    {
        return [
            [Status::STATUS_UNDEFINED],
            [Status::STATUS_BACKEND_ERROR],
            [Status::STATUS_BAD_OTP],
            [Status::STATUS_BAD_SIGNATURE],
            [Status::STATUS_MISSING_PARAMETER],
            [Status::STATUS_NO_SUCH_CLIENT],
            [Status::STATUS_NOT_ENOUGH_ANSWERS],
            [Status::STATUS_OK],
            [Status::STATUS_OPERATION_NOT_ALLOWED],
            [Status::STATUS_REPLAYED_OTP],
            [Status::STATUS_REPLAYED_REQUEST],
        ];
    }
}
