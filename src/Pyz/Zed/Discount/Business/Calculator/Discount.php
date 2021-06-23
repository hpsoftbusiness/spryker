<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Discount\Business\Calculator\Discount as SprykerDiscount;
use Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class Discount extends SprykerDiscount
{
    /**
     * @var \Pyz\Zed\Discount\Business\Calculator\InternalDiscountInterface
     */
    private $internalDiscount;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface $calculator
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $decisionRuleBuilder
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface $voucherValidator
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface $discountEntityMapper
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface $storeFacade
     * @param \Pyz\Zed\Discount\Business\Calculator\InternalDiscountInterface $internalDiscount
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        CalculatorInterface $calculator,
        SpecificationBuilderInterface $decisionRuleBuilder,
        VoucherValidatorInterface $voucherValidator,
        DiscountEntityMapperInterface $discountEntityMapper,
        DiscountToStoreFacadeInterface $storeFacade,
        InternalDiscountInterface $internalDiscount
    ) {
        parent::__construct(
            $queryContainer,
            $calculator,
            $decisionRuleBuilder,
            $voucherValidator,
            $discountEntityMapper,
            $storeFacade
        );

        $this->internalDiscount = $internalDiscount;
    }

    /**
     * @param array $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer[][]
     */
    protected function splitDiscountsByApplicability(array $discounts, QuoteTransfer $quoteTransfer): array
    {
        [$applicableDiscounts, $nonApplicableDiscounts] = parent::splitDiscountsByApplicability($discounts, $quoteTransfer);
        $internalDiscounts = $this->internalDiscount->getInternalDiscounts($quoteTransfer);

        return [array_merge($applicableDiscounts, $internalDiscounts), $nonApplicableDiscounts];
    }
}
