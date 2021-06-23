<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @method \Pyz\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Pyz\Zed\Discount\DiscountConfig getConfig()
 * @method \Pyz\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class BenefitPriceDiscountCalculator extends AbstractPlugin implements DiscountCalculatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer)
    {
        $discountAmount = 0;
        foreach ($discountableItems as $discountableItemTransfer) {
            $itemTransfer = $discountableItemTransfer->getOriginalItem();
            $itemDiscountAmount = $itemTransfer->getUnitGrossPrice() - $itemTransfer->getShoppingPointsDeal()->getPrice();
            $discountAmount += ($itemDiscountAmount * $itemTransfer->getQuantity());
        }

        return $discountAmount;
    }

    /**
     * @api
     *
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    protected function getMoneyPlugin()
    {
        return $this->getFactory()->getMoneyFacade();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getMoneyPlugin()->convertDecimalToInteger($value);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value)
    {
        return $this->getMoneyPlugin()->convertIntegerToDecimal($value);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return string
     */
    public function getFormattedAmount($amount, $isoCode = null)
    {
        $moneyTransfer = $this->getMoneyPlugin()->fromInteger($amount, $isoCode);

        return $this->getMoneyPlugin()->formatWithSymbol($moneyTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getAmountValidators()
    {
        return [
            new Type([
                'type' => 'numeric',
                'groups' => DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE,
            ]),
        ];
    }
}
