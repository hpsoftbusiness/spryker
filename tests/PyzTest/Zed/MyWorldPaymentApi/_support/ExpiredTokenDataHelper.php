<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPaymentApi;

use Generated\Shared\DataBuilder\SsoAccessTokenBuilder;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;

class ExpiredTokenDataHelper extends DataHelper
{
    /**
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    protected function getSsoAccessToken(): SsoAccessTokenTransfer
    {
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJlMmFlMTNlNS1mYWU2LTRhODQtODZlZi1hN2ZlMDBlYjZiM2QiLCJzY29wZSI6Im9wZW5pZCIsImNsaWVudF9pZCI6Im9wdGl3ZWJfc3NvIiwiaHR0cDovL3NjaGVtYXMueG1sc29hcC5vcmcvd3MvMjAwNS8wNS9pZGVudGl0eS9jbGFpbXMvc2lkIjoiYzY3NjQxYTItZjdmYi00ZDM0LTgwYTQtYjgwZGNjYzEwNDBiIiwibmJmIjoxNjIxNDE3NzMzLCJleHAiOjE2MjE0MTk1MzMsImlhdCI6MTYyMTQxNzczMywiaXNzIjoiaWQubHlvbmVzcy5pbnRlcm5hbCIsImF1ZCI6Imx5byJ9.VqKsCuG5eJR46eyd9y8PehcSExYP_-_yWIOilS17ZdI";

        return (new SsoAccessTokenBuilder(
            [
                'accessToken' => $token,
            ]
        ))->build();
    }
}
