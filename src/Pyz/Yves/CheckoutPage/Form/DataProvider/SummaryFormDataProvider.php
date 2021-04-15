<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\CheckoutPage\Form\Steps\SummaryForm;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Form\DataProvider\SummaryFormDataProvider as SprykerSummaryFormDataProvider;

class SummaryFormDataProvider extends SprykerSummaryFormDataProvider
{
    protected const GLOSSARY_SMS_CODE = 'page.checkout.summary.sms_code';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $localizedTermsAndConditionsPageLinks = $this->checkoutPageConfig->getLocalizedTermsAndConditionsPageLinks();
        $currentLocale = $this->localeClient->getCurrentLocale();

        $options = [];
        if (!isset($localizedTermsAndConditionsPageLinks[$currentLocale])) {
            $options[SummaryForm::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL] = static::GLOSSARY_KEY_ACCEPT_TERM_AND_CONDITIONS;
        } else {
            $options[SummaryForm::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL] = $this->generateOptionAcceptTermAndConditionsLabel(
                $localizedTermsAndConditionsPageLinks,
                $currentLocale
            );
        }

        if ($this->isMyWorldPaymentSelected($quoteTransfer)
            && $quoteTransfer->getMyWorldPaymentSessionId()
            && $quoteTransfer->getMyWorldPaymentIsSmsAuthenticationRequired()
        ) {
            $options[SummaryForm::OPTION_SMS_CODE] = self::GLOSSARY_SMS_CODE;
        }

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isMyWorldPaymentSelected(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getMyWorldUseEVoucherBalance() || $this->isBenefitVoucherSelected($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isBenefitVoucherSelected(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $item) {
            if ($item->getUseBenefitVoucher()) {
                return true;
            }
        }

        return false;
    }
}
