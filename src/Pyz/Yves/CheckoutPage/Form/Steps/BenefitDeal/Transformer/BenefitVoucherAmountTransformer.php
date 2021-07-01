<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal\Transformer;

use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface;
use Symfony\Component\Form\DataTransformerInterface;

class BenefitVoucherAmountTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    private $decimalToIntegerConverter;

    /**
     * @var \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    private $integerToDecimalConverter;

    /**
     * @param \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface $decimalToIntegerConverter
     * @param \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     */
    public function __construct(
        DecimalToIntegerConverterInterface $decimalToIntegerConverter,
        IntegerToDecimalConverterInterface $integerToDecimalConverter
    ) {
        $this->decimalToIntegerConverter = $decimalToIntegerConverter;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
    }

    /**
     * @param mixed $value
     *
     * @return float|null
     */
    public function transform($value): ?float
    {
        if ($value !== null) {
            $value = $this->integerToDecimalConverter->convert((int)$value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return int|null
     */
    public function reverseTransform($value): ?int
    {
        if ($value !== null) {
            $value = $this->decimalToIntegerConverter->convert((float)$value);
        }

        return $value;
    }
}
