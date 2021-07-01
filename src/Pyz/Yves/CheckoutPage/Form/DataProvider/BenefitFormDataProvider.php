<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\DataProvider;

use Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal\BenefitDealCollectionForm;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;

class BenefitFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        return [
            BenefitDealCollectionForm::OPTION_KEY_ITEMS => $quoteTransfer->getItems(),
        ];
    }
}
