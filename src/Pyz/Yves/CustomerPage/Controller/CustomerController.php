<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Controller;

use SprykerShop\Yves\CustomerPage\Controller\CustomerController as SprykerCustomerController;

class CustomerController extends SprykerCustomerController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction()
    {
        $this->getFactory()->getCustomerClient()->markCustomerAsDirty();

        return parent::executeIndexAction();
    }
}
