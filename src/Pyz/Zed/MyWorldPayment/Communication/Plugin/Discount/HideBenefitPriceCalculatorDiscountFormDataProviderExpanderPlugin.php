<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Discount;

use Pyz\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Communication\Form\CalculatorForm;
use Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormDataProviderExpanderPluginInterface;

class HideBenefitPriceCalculatorDiscountFormDataProviderExpanderPlugin implements DiscountFormDataProviderExpanderPluginInterface
{
    /**
     * Specification:
     *
     * Expand data provider options, the options will be passed from concrete form provider that is (calculator, general, general...)
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandDataProviderOptions(array $options)
    {
        return $options;
    }

    /**
     * Specification:
     *
     * Expand data provider data, the options will be passed from concrete form provider that is (calculator, general, general...)
     *
     * @api
     *
     * @param array|null $data
     *
     * @return array|null
     */
    public function expandDataProviderData(?array $data)
    {
        unset($data[CalculatorForm::FIELD_CALCULATOR_PLUGIN][DiscountDependencyProvider::PLUGIN_CALCULATOR_BENEFIT_PRICE]);

        return $data;
    }
}
