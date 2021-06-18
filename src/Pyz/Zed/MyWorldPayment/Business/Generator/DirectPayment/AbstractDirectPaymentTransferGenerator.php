<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldPaymentException;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

abstract class AbstractDirectPaymentTransferGenerator implements DirectPaymentTransferGeneratorInterface
{
    /**
     * Specification:
     * - To be overridden with relevant payment method names final direct payment transfer generators.
     */
    protected const PAYMENT_METHOD_NAME = '';

    private const EXCEPTION_PAYMENT_METHOD_NOT_FOUND = 'Payment method %s for MyWorld direct payment transfer generation not found.';

        /**
         * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $config
         */
    public function __construct(MyWorldPaymentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    protected $config;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer
     */
    public function generateMwsDirectPaymentOptionTransfer(
        QuoteTransfer $quoteTransfer
    ): MwsDirectPaymentOptionTransfer {
        $unitType = $this->getUnitType();

        return (new MwsDirectPaymentOptionTransfer())
            ->setAmount($this->getAmount($quoteTransfer))
            ->setUnit($this->getUnitByUnitType($unitType, $quoteTransfer))
            ->setUnitType($unitType)
            ->setPaymentOptionId($this->getPaymentOptionId());
    }

    /**
     * @return string
     */
    protected function getUnitType(): string
    {
        return $this->config->getUnitTypeToOptionIdMap()[$this->getPaymentOptionId()];
    }

    /**
     * @param string $unitType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getUnitByUnitType(string $unitType, QuoteTransfer $quoteTransfer): string
    {
        return $unitType === $this->config->getUnitTypeCurrency()
            ? $quoteTransfer->getCurrency()->getCode()
            : $this->config->getUnitTypeUnit();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function findPaymentByName(QuoteTransfer $quoteTransfer): ?PaymentTransfer
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentMethod() === static::PAYMENT_METHOD_NAME) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldPaymentException
     *
     * @return mixed
     */
    protected function getAmount(QuoteTransfer $quoteTransfer)
    {
        $paymentMethod = $this->findPaymentByName($quoteTransfer);
        if (!$paymentMethod) {
            throw new MyWorldPaymentException(sprintf(self::EXCEPTION_PAYMENT_METHOD_NOT_FOUND, self::PAYMENT_METHOD_NAME));
        }

        return (int)$paymentMethod->getAmount();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    abstract public function isPaymentUsed(QuoteTransfer $quoteTransfer): bool;

    /**
     * @return int
     */
    abstract protected function getPaymentOptionId(): int;
}
