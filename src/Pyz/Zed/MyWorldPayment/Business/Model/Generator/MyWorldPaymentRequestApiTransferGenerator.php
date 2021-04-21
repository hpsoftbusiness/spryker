<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Model\Generator;

use ArrayObject;
use Generated\Shared\Transfer\FlowsTransfer;
use Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateRequestTransfer;
use Generated\Shared\Transfer\PaymentSessionRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig as ZedMyWorldPaymentConfig;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;

class MyWorldPaymentRequestApiTransferGenerator implements MyWorldPaymentRequestApiTransferGeneratorInterface
{
    public const PAYMENT_SESSION_PREFIX = 'MyWorld_Order_';

    private const INITIAL_PRICE = 0.00;

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    private $myWorldPaymentConfig;

    /**
     * @var \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    private $sequenceNumberFacade;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     */
    public function __construct(
        ZedMyWorldPaymentConfig $myWorldPaymentConfig,
        SequenceNumberFacadeInterface $sequenceNumberFacade
    ) {
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
        $this->sequenceNumberFacade = $sequenceNumberFacade;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer
     */
    protected function createPaymentCodeGenerateRequestTransfer(QuoteTransfer $quoteTransfer): PaymentCodeGenerateRequestTransfer
    {
        return (new PaymentCodeGenerateRequestTransfer())
            ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentSessionRequestTransfer
     */
    protected function createPaymentSessionRequestTransfer(QuoteTransfer $quoteTransfer): PaymentSessionRequestTransfer
    {
        $usedMyWorldPayments = $this->getListOfSelectedPaymentOptions($quoteTransfer);

        return ($requestTransfer = new PaymentSessionRequestTransfer())
            ->setCurrencyId($quoteTransfer->getCurrency()->getCode())
            ->setReference($this->sequenceNumberFacade->generate($this->generateSequenceSettings()))
            ->setPaymentOptions($this->getNamesOfPaymentsOptions($usedMyWorldPayments))
            ->setSsoAccessToken($quoteTransfer->getCustomer()->getSsoAccessToken())
            ->setFlows($this->createFlowsTransferFromQuoteTransfer($quoteTransfer))
            ->setAmount(
                $this->getCommonPriceForThePayments($requestTransfer->getFlows()->getMwsDirect())
            );
    }

    /***
     * @param \ArrayObject $dwsDirectItems
     *
     * @return int
     */
    protected function getCommonPriceForThePayments(ArrayObject $dwsDirectItems): int
    {
        return (int)array_reduce(
            $dwsDirectItems->getArrayCopy(),
            function (float $carry, MwsDirectPaymentOptionTransfer $directPaymentOptionTransfer) {
                if ($directPaymentOptionTransfer->getPaymentOptionId() === $this->myWorldPaymentConfig->getOptionShoppingPoints()) {
                    return $carry;
                }
                $carry += $directPaymentOptionTransfer->getAmount();

                return $carry;
            },
            static::INITIAL_PRICE
        );
    }

    /**
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected function generateSequenceSettings(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setIncrementMinimum(1300)
            ->setPrefix(static::PAYMENT_SESSION_PREFIX);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FlowsTransfer
     */
    protected function createFlowsTransferFromQuoteTransfer(QuoteTransfer $quoteTransfer): FlowsTransfer
    {
        $listMwsDirect = new ArrayObject();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($this->isPaymentMethodForMyWorld($paymentTransfer->getPaymentSelection())) {
                $unitType = $this->getUnitTypeForPayment($paymentTransfer);
                $unit = $paymentTransfer->getPaymentMethodName() === $this->myWorldPaymentConfig->getShoppingPointsPaymentName()
                    ? $this->myWorldPaymentConfig->getShoppingPointsPaymentName()
                    : $this->getUnitByUnitType($unitType, $quoteTransfer);

                $listMwsDirect->append(
                    $this->createMwsDirectFromPaymentTransfer(
                        $paymentTransfer,
                        $unit,
                        $unitType,
                        $this->getOptionIdByName($paymentTransfer->getPaymentSelection())
                    )
                );
            }
        }

        return (new FlowsTransfer())
            ->setType($this->myWorldPaymentConfig->getDefaultFlowsType())
            ->setMwsDirect($listMwsDirect);
    }

    /**
     * @param string $unitType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getUnitByUnitType(string $unitType, QuoteTransfer $quoteTransfer): string
    {
        return $unitType === $this->myWorldPaymentConfig->getUnitTypeCurrency()
            ? $quoteTransfer->getCurrency()->getCode()
            : $this->myWorldPaymentConfig->getUnitTypeUnit();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getListOfSelectedPaymentOptions(QuoteTransfer $quoteTransfer): array
    {
        $listSelectedOptions = [];

        foreach ($quoteTransfer->getPayments() as $payment) {
            if (array_key_exists($payment->getPaymentSelection(), $this->myWorldPaymentConfig->getMapOptionNameToOptionId())) {
                $listSelectedOptions[] = $payment;
            }
        }

        return $listSelectedOptions;
    }

    /**
     * @param array $listOfPayments
     *
     * @return array
     */
    protected function getNamesOfPaymentsOptions(array $listOfPayments): array
    {
        return array_reduce($listOfPayments, function (array $carry, PaymentTransfer $item) {
            $carry[] = $this->getOptionIdByName($item->getPaymentSelection());

            return $carry;
        }, []);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    protected function getUnitTypeForPayment(PaymentTransfer $paymentTransfer): string
    {
        return $this->myWorldPaymentConfig
            ->getUnitTypeToOptionIdMap()[$this->getOptionIdByName($paymentTransfer->getPaymentSelection())];
    }

    /**
     * @param string $nameOfOption
     *
     * @return int
     */
    protected function getOptionIdByName(string $nameOfOption): int
    {
        return (int)$this->myWorldPaymentConfig->getOptionNameToIdMap()[$nameOfOption];
    }

    /**
     * @param string $paymentMethodName
     *
     * @return bool
     */
    protected function isPaymentMethodForMyWorld(string $paymentMethodName): bool
    {
        return array_key_exists($paymentMethodName, $this->myWorldPaymentConfig->getOptionNameToIdMap());
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param string $unit
     * @param string $unitType
     * @param int $paymentOptionId
     *
     * @return \Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer
     */
    protected function createMwsDirectFromPaymentTransfer(PaymentTransfer $paymentTransfer, string $unit, string $unitType, int $paymentOptionId): MwsDirectPaymentOptionTransfer
    {
        return (new MwsDirectPaymentOptionTransfer())
            ->setAmount($paymentTransfer->getAmount())
            ->setUnit($unit)
            ->setUnitType($unitType)
            ->setPaymentOptionId($paymentOptionId);
    }
}
