<?php

namespace Pyz\Yves\Currency;

use Pyz\Shared\Currency\CurrencyConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class CurrencyConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isMultiCurrencyEnabled(): bool
    {
        return $this->get(CurrencyConstants::IS_MULTI_CURRENCY_FEATURE_ENABLED);
    }
}
