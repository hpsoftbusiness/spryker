<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Communication\Plugin\PreSaveCheck;

use Exception;
use Pyz\Zed\ProductAttribute\Business\Exception\ProductAttributeCheckException;
use Pyz\Zed\ProductAttribute\Dependency\Plugin\ProductAttributePreSaveCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

/**
 * @method \Pyz\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\ProductAttribute\Communication\ProductAttributeCommunicationFactory getFactory()
 */
class ShoppingPointsAttributePreSaveCheckPlugin extends AbstractPlugin implements ProductAttributePreSaveCheckPluginInterface
{
    private const DEFAULT_LOCALE_KEY = ProductAttributeConfig::DEFAULT_LOCALE;
    private const MISSING_ATTRIBUTE_VALUE_EXCEPTION_MESSAGE = 'Attribute %s must have a value if %s attribute is set.';

    /**
     * @param array $attributes
     *
     * @throws \Pyz\Zed\ProductAttribute\Business\Exception\ProductAttributeCheckException
     *
     * @return void
     */
    public function check(array $attributes): void
    {
        $shoppingPointsAmountAttributeName = $this->getConfig()->getShoppingPointsAmountAttributeName();
        $shoppingPointsStoreAttributeName = $this->getConfig()->getShoppingPointStoreAttributeName();
        $benefitAmountAttributeName = $this->getConfig()->getBenefitAmountAttributeName();

        if (isset($attributes[self::DEFAULT_LOCALE_KEY][$benefitAmountAttributeName])) {
            $benefitAmount = $attributes[self::DEFAULT_LOCALE_KEY][$benefitAmountAttributeName];
            try {
                $this->getFactory()->getMoneyFacade()->fromString($benefitAmount);
            } catch (Exception $e) {
                throw new ProductAttributeCheckException($e->getMessage());
            }
        }

        $shoppingPointsStoreValue = (bool)($attributes[self::DEFAULT_LOCALE_KEY][$shoppingPointsStoreAttributeName] ?? null);
        if (!$shoppingPointsStoreValue) {
            return;
        }

        $shoppingPointsValue = $attributes[self::DEFAULT_LOCALE_KEY][$shoppingPointsAmountAttributeName] ?? null;
        if (empty($shoppingPointsValue)) {
            throw new ProductAttributeCheckException(
                sprintf(
                    self::MISSING_ATTRIBUTE_VALUE_EXCEPTION_MESSAGE,
                    $shoppingPointsAmountAttributeName,
                    $shoppingPointsStoreAttributeName
                )
            );
        }
    }
}
