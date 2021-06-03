<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class BenefitDealStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    protected const STEP_TITLE = 'checkout.step.benefit_deal.title';

    /**
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface
     */
    private $breadcrumbEnabledChecker;

    /**
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface $breadcrumbEnabledChecker
     * @param string $stepRoute
     * @param string|null $escapeRoute
     */
    public function __construct(
        CustomerServiceInterface $customerService,
        BreadcrumbStatusCheckerInterface $breadcrumbEnabledChecker,
        $stepRoute,
        $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->customerService = $customerService;
        $this->breadcrumbEnabledChecker = $breadcrumbEnabledChecker;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $dataTransfer */
        return $this->assertCartHasApplicableBenefitDeals($dataTransfer);
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle(): string
    {
        return static::STEP_TITLE;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer): bool
    {
        /**
         * @var \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
         */
        return $this->breadcrumbEnabledChecker->isEnabled($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer): bool
    {
        return !$this->requireInput($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer)
    {
        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $dataTransfer): array
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $dataTransfer */
        $customerTransfer = $dataTransfer->getCustomer();

        return [
            'customerBalance' => [
                'benefitVouchersBalance' => $this->customerService->getCustomerBenefitVoucherBalanceAmount(
                    $customerTransfer
                ),
                'benefitVouchersCurrencyCode' => $dataTransfer->getCurrency()->getCode(),
                'shoppingPointBalance' => $this->customerService->getCustomerShoppingPointsBalanceAmount(
                    $customerTransfer
                ),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertCartHasApplicableBenefitDeals(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $item) {
            if ($item->getShoppingPointsDeal() && $item->getShoppingPointsDeal()->getIsActive()) {
                return true;
            }

            if ($item->getBenefitVoucherDealData() && $item->getBenefitVoucherDealData()->getIsStore()) {
                return true;
            }
        }

        return false;
    }
}
