<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiFacade getFacade()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Communication\MyWorldMarketplaceApiCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'test' => 'Greetings!',
        ]);
    }
}
