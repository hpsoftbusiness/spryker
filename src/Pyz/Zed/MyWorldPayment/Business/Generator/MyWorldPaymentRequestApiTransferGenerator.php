<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateRequestTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\PaymentSessionRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow\PaymentFlowsTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PaymentRefundRequestTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig as ZedMyWorldPaymentConfig;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;

class MyWorldPaymentRequestApiTransferGenerator implements MyWorldPaymentRequestApiTransferGeneratorInterface
{
    public const PAYMENT_SESSION_PREFIX = 'MyWorld_Order';

    private const INITIAL_PRICE = 0;

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    private $myWorldPaymentConfig;

    /**
     * @var \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    private $sequenceNumberFacade;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow\PaymentFlowsTransferGeneratorInterface
     */
    private $paymentFlowsTransferGenerator;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PaymentRefundRequestTransferGeneratorInterface
     */
    private $paymentRefundRequestTransferGenerator;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     * @param \Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow\PaymentFlowsTransferGeneratorInterface $paymentFlowsTransferGenerator
     * @param \Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PaymentRefundRequestTransferGeneratorInterface $paymentRefundRequestTransferGenerator
     */
    public function __construct(
        ZedMyWorldPaymentConfig $myWorldPaymentConfig,
        SequenceNumberFacadeInterface $sequenceNumberFacade,
        PaymentFlowsTransferGeneratorInterface $paymentFlowsTransferGenerator,
        PaymentRefundRequestTransferGeneratorInterface $paymentRefundRequestTransferGenerator
    ) {
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
        $this->sequenceNumberFacade = $sequenceNumberFacade;
        $this->paymentFlowsTransferGenerator = $paymentFlowsTransferGenerator;
        $this->paymentRefundRequestTransferGenerator = $paymentRefundRequestTransferGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createPerformPaymentSessionCreationRequest(QuoteTransfer $quoteTransfer): MyWorldApiRequestTransfer
    {
        return (new MyWorldApiRequestTransfer())
            ->setPaymentSessionRequest($this->createPaymentSessionRequestTransfer($quoteTransfer))
            ->setPaymentCodeGenerateRequest($this->createPaymentCodeGenerateRequestTransfer($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createPerformGenerateSmsCodeRequest(QuoteTransfer $quoteTransfer): MyWorldApiRequestTransfer
    {
        return (new MyWorldApiRequestTransfer())
            ->setPaymentCodeGenerateRequest($this->createPaymentCodeGenerateRequestTransfer($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createSmsCodeValidationRequest(QuoteTransfer $quoteTransfer): MyWorldApiRequestTransfer
    {
        return (new MyWorldApiRequestTransfer())
            ->setPaymentCodeValidateRequest(
                (new PaymentCodeValidateRequestTransfer())
                    ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId())
                    ->setConfirmationCode($quoteTransfer->getSmsCode())
            )
            ->setPaymentSessionRequest(
                (new PaymentSessionRequestTransfer())
                    ->setSsoAccessToken($quoteTransfer->getCustomer()->getSsoAccessToken())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createRefundRequest(
        PaymentDataResponseTransfer $paymentDataResponseTransfer,
        array $refundTransfers
    ): MyWorldApiRequestTransfer {
        return $this->paymentRefundRequestTransferGenerator->generate($paymentDataResponseTransfer, $refundTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer
     */
    private function createPaymentCodeGenerateRequestTransfer(QuoteTransfer $quoteTransfer): PaymentCodeGenerateRequestTransfer
    {
        return (new PaymentCodeGenerateRequestTransfer())
            ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentSessionRequestTransfer
     */
    private function createPaymentSessionRequestTransfer(QuoteTransfer $quoteTransfer): PaymentSessionRequestTransfer
    {
        $flowsTransfer = $this->paymentFlowsTransferGenerator->generate($quoteTransfer);
        $paymentIds = $this->getSelectedPaymentOptionIds($flowsTransfer->getMwsDirect());

        return ($requestTransfer = new PaymentSessionRequestTransfer())
            ->setCurrencyId($quoteTransfer->getCurrency()->getCode())
            ->setReference($this->sequenceNumberFacade->generate($this->generateSequenceSettings()))
            ->setPaymentOptions($paymentIds)
            ->setSsoAccessToken($quoteTransfer->getCustomer()->getSsoAccessToken())
            ->setFlows($flowsTransfer)
            ->setAmount(
                $this->getCommonPriceForThePayments($requestTransfer->getFlows()->getMwsDirect())
            );
    }

    /***
     * @param \ArrayObject $dwsDirectItems
     *
     * @return int
     */
    private function getCommonPriceForThePayments(ArrayObject $dwsDirectItems): int
    {
        return array_reduce($dwsDirectItems->getArrayCopy(), function (int $carry, MwsDirectPaymentOptionTransfer $directPaymentOptionTransfer) {
            /**
             * Shopping points amount is provided in units thus it doesn't need to be added to total amount sum.
             */
            if ($directPaymentOptionTransfer->getPaymentOptionId() === $this->myWorldPaymentConfig->getOptionShoppingPoints()) {
                return $carry;
            }
            $carry += $directPaymentOptionTransfer->getAmount();

            return $carry;
        }, static::INITIAL_PRICE);
    }

    /**
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    private function generateSequenceSettings(): SequenceNumberSettingsTransfer
    {
        $date = new DateTime();

        return (new SequenceNumberSettingsTransfer())
            ->setPrefix(sprintf('%s_%s_', static::PAYMENT_SESSION_PREFIX, $date->getTimestamp()));
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer[] $directPaymentOptionsTransferCollection
     *
     * @return int[]
     */
    private function getSelectedPaymentOptionIds(ArrayObject $directPaymentOptionsTransferCollection): array
    {
        return array_map(
            static function (MwsDirectPaymentOptionTransfer $directPaymentOptionTransfer): int {
                return $directPaymentOptionTransfer->getPaymentOptionId();
            },
            $directPaymentOptionsTransferCollection->getArrayCopy()
        );
    }
}
