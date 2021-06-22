<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 */
class BenefitVoucherOrderCalculationPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    /**
     * Specification:
     * - Recalculates total used benefit vouchers amount for each order item (required because of item splitting strategy on order pre-save).
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFacade()->recalculateItemsPricesForBenefitVoucherOrder($calculableObjectTransfer);
    }
}
