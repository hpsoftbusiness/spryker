<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Customer\Balance;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;

class BalanceResolver implements BalanceResolverInterface
{
    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    private $decimalToIntegerConverter;

    /**
     * @param \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface $decimalToIntegerConverter
     */
    public function __construct(DecimalToIntegerConverterInterface $decimalToIntegerConverter)
    {
        $this->decimalToIntegerConverter = $decimalToIntegerConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int $paymentOptionId
     *
     * @return int
     */
    public function resolveBalanceAmount(CustomerTransfer $customerTransfer, int $paymentOptionId): int
    {
        $balanceAmount = $this->resolveBalanceAmountWithoutConversion($customerTransfer, $paymentOptionId);
        if ($balanceAmount === (float)0) {
            return 0;
        }

        return $this->decimalToIntegerConverter->convert($balanceAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int $paymentOptionId
     *
     * @return float
     */
    public function resolveBalanceAmountWithoutConversion(CustomerTransfer $customerTransfer, int $paymentOptionId): float
    {
        foreach ($customerTransfer->getBalances() as $balanceTransfer) {
            if ($balanceTransfer->getPaymentOptionId() !== $paymentOptionId) {
                continue;
            }

            if ($balanceTransfer->getTargetAvailableBalance()) {
                return $balanceTransfer->getTargetAvailableBalance()->toFloat();
            } elseif ($balanceTransfer->getAvailableBalance()) {
                return $balanceTransfer->getAvailableBalance()->toFloat();
            } else {
                return 0;
            }
        }

        return 0;
    }
}
