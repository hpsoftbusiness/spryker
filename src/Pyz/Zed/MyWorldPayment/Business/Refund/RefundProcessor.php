<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Refund;

use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldRefundException;
use Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\RequestDispatcherInterface;
use Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentRepositoryInterface;

class RefundProcessor implements RefundProcessorInterface
{
    private const EXCEPTION_MESSAGE_GENERIC = 'An error occurred while processing order refund.';

    /**
     * @var \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentRepositoryInterface
     */
    private $repository;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\RequestDispatcherInterface
     */
    private $requestDispatcher;

    /**
     * @param \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentRepositoryInterface $repository
     * @param \Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\RequestDispatcherInterface $requestDispatcher
     */
    public function __construct(MyWorldPaymentRepositoryInterface $repository, RequestDispatcherInterface $requestDispatcher)
    {
        $this->repository = $repository;
        $this->requestDispatcher = $requestDispatcher;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundsTransfer
     *
     * @throws \Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldRefundException
     *
     * @return void
     */
    public function processRefunds(array $refundsTransfer): void
    {
        if (empty($refundsTransfer)) {
            return;
        }

        $idSalesOrder = current($refundsTransfer)->getFkSalesOrder();
        $myWorldPaymentResponse = $this->repository->findMyWorldPaymentByIdSalesOrder($idSalesOrder);
        if (!$myWorldPaymentResponse) {
            throw new MyWorldRefundException('Order MyWorld payment data not found!');
        }

        $myWorldApiResponseTransfer = $this->requestDispatcher->dispatchRefundPayment(
            $myWorldPaymentResponse,
            $refundsTransfer
        );

        if (!$myWorldApiResponseTransfer->getIsSuccess()) {
            $this->handleFailedRefundRequest($myWorldApiResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $apiResponseTransfer
     *
     * @throws \Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldRefundException
     *
     * @return void
     */
    private function handleFailedRefundRequest(MyWorldApiResponseTransfer $apiResponseTransfer): void
    {
        $exceptionMessage = self::EXCEPTION_MESSAGE_GENERIC;
        if ($apiResponseTransfer->getError() && $errorMessage = $apiResponseTransfer->getError()->getErrorMessage()) {
            $exceptionMessage = $errorMessage;
        }

        throw new MyWorldRefundException($exceptionMessage);
    }
}
