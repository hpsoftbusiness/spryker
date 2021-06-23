<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Communication\Controller\SalesController as SprykerSalesController;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class SalesController extends SprykerSalesController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request): array
    {
        $orderTransfer = $request->request->get('orderTransfer');
        $this->translateDiscountDisplayNames($orderTransfer);

        return [
            'order' => $orderTransfer,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    private function translateDiscountDisplayNames(OrderTransfer $orderTransfer): void
    {
        foreach ($orderTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            try {
                $translatedDisplayName = $this->getFactory()->getGlossaryFacade()->translate(
                    $calculatedDiscountTransfer->getDisplayName()
                );
                $calculatedDiscountTransfer->setDisplayName($translatedDisplayName);
            } catch (MissingTranslationException $exception) {
            }
        }
    }
}
