<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @SuppressWarnings(PHPMD.FacadeRule)
 *
 * @method \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiBusinessFactory getFactory()
 */
class MyWorldPaymentApiFacade extends AbstractFacade implements MyWorldPaymentApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performCreatePaymentSessionApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        $adapter = $this->getFactory()->createPaymentSessionAdapter($myWorldApiRequestTransfer);
        $converter = $this->getFactory()->createPaymentSessionConverter();
        $mapper = $this->getFactory()->createPaymentSessionMapper();

        return $this->getFactory()->createMyWorldPaymentApiRequest($adapter, $converter, $mapper)->request(
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performGenerateSmsCodeApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        $adapter = $this->getFactory()->createGenerateSmsCodeAdapter($myWorldApiRequestTransfer);
        $converter = $this->getFactory()->createGenerateSmsCodeConverter();
        $mapper = $this->getFactory()->createGenerateSmsCodeMapper();

        return $this->getFactory()->createMyWorldPaymentApiRequest($adapter, $converter, $mapper)->request(
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performValidateSmsCodeApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        $adapter = $this->getFactory()->createValidateSmsCodeAdapter($myWorldApiRequestTransfer);
        $converter = $this->getFactory()->createValidateSmsCodeConverter();
        $mapper = $this->getFactory()->createValidateSmsCodeMapper();

        return $this->getFactory()->createMyWorldPaymentApiRequest($adapter, $converter, $mapper)->request(
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performConfirmPaymentApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        $adapter = $this->getFactory()->createPaymentSessionApiCallAdapter($myWorldApiRequestTransfer);
        $converter = $this->getFactory()->createPaymentSessionApiCallConverter();
        $mapper = $this->getFactory()->createPaymentSessionApiCallMapper();

        return $this->getFactory()->createMyWorldPaymentApiRequest($adapter, $converter, $mapper)->request(
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performGetPaymentApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        $adapter = $this->getFactory()->createGetPaymentAdapter($myWorldApiRequestTransfer);
        $converter = $this->getFactory()->createGetPaymentConverter();
        $mapper = $this->getFactory()->createGetPaymentMapper();

        return $this->getFactory()->createMyWorldPaymentApiRequest($adapter, $converter, $mapper)->request(
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function performCreateRefundApiCall(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        $adapter = $this->getFactory()->createCreateRefundAdapter($myWorldApiRequestTransfer);
        $converter = $this->getFactory()->createCreateRefundConverter();
        $mapper = $this->getFactory()->createCreateRefundMapper();

        return $this->getFactory()->createMyWorldPaymentApiRequest($adapter, $converter, $mapper)->request(
            $myWorldApiRequestTransfer
        );
    }
}
