<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Controller;

use Spryker\Zed\ProductAttributeGui\Communication\Controller\SaveController as SprykerSaveController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class SaveController extends SprykerSaveController
{
    protected const PARAM_EXTRA_JSON = 'extraJson';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAction(Request $request)
    {
        if (!$this->validateCsrfToken($request)) {
            return $this->createJsonResponse(static::MESSAGE_INVALID_CSRF_TOKEN, false);
        }

        $idProduct = $this->castId($request->get(
            static::PARAM_ID_PRODUCT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $extraJson = $request->request->get(static::PARAM_EXTRA_JSON);
        $data = json_decode($json, true);
        $dataExtra = json_decode($extraJson, true);

        $this->getFactory()
            ->getPyzProductAttributeFacade()
            ->saveConcreteAttributes($idProduct, $data, $dataExtra);

        return $this->createJsonResponse(static::MESSAGE_PRODUCT_ATTRIBUTES_SAVED);
    }
}