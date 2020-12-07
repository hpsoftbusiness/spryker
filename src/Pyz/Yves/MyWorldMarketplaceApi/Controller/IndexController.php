<?php

namespace Pyz\Yves\MyWorldMarketplaceApi\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Yves\MyWorldMarketplaceApi\MyWorldMarketplaceApiFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        return [];
    }

}
