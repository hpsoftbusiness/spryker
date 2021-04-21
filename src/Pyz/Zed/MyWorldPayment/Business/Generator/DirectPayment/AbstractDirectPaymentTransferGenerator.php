<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

abstract class AbstractDirectPaymentTransferGenerator implements DirectPaymentTransferGeneratorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    protected $config;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $config
     */
    public function __construct(MyWorldPaymentConfig $config)
    {
        $this->config = $config;
    }

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
     * @return bool
     */
    abstract public function isPaymentUsed(QuoteTransfer $quoteTransfer): bool;

    /**
     * @return int
     */
    abstract protected function getPaymentOptionId(): int;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    abstract protected function getAmount(QuoteTransfer $quoteTransfer);
}
