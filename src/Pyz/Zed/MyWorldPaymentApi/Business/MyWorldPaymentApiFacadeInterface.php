<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;

interface MyWorldPaymentApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performCreatePaymentSessionApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performGenerateSmsCodeApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performValidateSmsCodeApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performConfirmPaymentApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performGetPaymentApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performCreateRefundApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;
}
