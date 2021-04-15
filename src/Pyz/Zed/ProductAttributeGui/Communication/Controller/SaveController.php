<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Controller;

use Pyz\Zed\ProductAttribute\Business\Exception\ProductAttributeCheckException;
use Spryker\Zed\ProductAttributeGui\Communication\Controller\SaveController as SprykerSaveController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Pyz\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class SaveController extends SprykerSaveController
{
    private const MESSAGE_PRODUCT_ATTRIBUTE_CHECK_ERROR = 'Error occurred while saving attributes';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractAction(Request $request)
    {
        if (!$this->validateCsrfToken($request)) {
            return $this->createJsonResponse(static::MESSAGE_INVALID_CSRF_TOKEN, false, Response::HTTP_FORBIDDEN);
        }

        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        try {
            $this->getFactory()
                ->getProductAttributeFacade()
                ->saveAbstractAttributes($idProductAbstract, $data);
        } catch (ProductAttributeCheckException $exception) {
            return $this->createJsonResponse(
                $this->formatAttributeCheckErrorMessage($exception->getMessage()),
                false,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->createJsonResponse(static::MESSAGE_PRODUCT_ABSTRACT_ATTRIBUTES_SAVED);
    }

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
        $data = json_decode($json, true);

        try {
            $this->getFactory()
                ->getProductAttributeFacade()
                ->saveConcreteAttributes($idProduct, $data);
        } catch (ProductAttributeCheckException $exception) {
            return $this->createJsonResponse(
                $this->formatAttributeCheckErrorMessage($exception->getMessage()),
                false,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->createJsonResponse(static::MESSAGE_PRODUCT_ATTRIBUTES_SAVED);
    }

    /**
     * @param string $exceptionMessage
     *
     * @return string
     */
    private function formatAttributeCheckErrorMessage(string $exceptionMessage): string
    {
        return sprintf(
            '%s: %s',
            self::MESSAGE_PRODUCT_ATTRIBUTE_CHECK_ERROR,
            $exceptionMessage
        );
    }
}
