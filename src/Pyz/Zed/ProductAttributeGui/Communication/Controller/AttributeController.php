<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Controller;

use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Spryker\Zed\ProductAttributeGui\Communication\Controller\AttributeController as SprykerAttributeController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductAttributeGui\Business\ProductAttributeGuiFacade getFacade()
 */
class AttributeController extends SprykerAttributeController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idProductManagementAttribute = $this->castId($request->get('id'));
        $productManagementAttributeEntity = $this->getProductManagementAttributeEntity($idProductManagementAttribute);
        $productManagementAttributeEntityArray = $productManagementAttributeEntity->toArray();
        $productManagementAttributeEntityArray['name'] = $productManagementAttributeEntity->getSpyProductAttributeKey()->getKey();

        $isItCanBeDeleted = $this->getFacade()
            ->isProductAttributeCanBeDeleted($productManagementAttributeEntityArray['name']);

        $form = $this->getFactory()->createAttributeDeleteForm($idProductManagementAttribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $isItCanBeDeleted) {
            $data = $form->getData();

            $this
                ->getFacade()
                ->delete($data['id_product_management_attribute']);

            return $this->redirectResponse('/product-attribute-gui/attribute');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'productManagementAttributeEntity' => $productManagementAttributeEntityArray,
            'isItCanBeDeleted' => $isItCanBeDeleted,
        ]);
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute|null
     */
    protected function getProductManagementAttributeEntity(int $idProductManagementAttribute): ?SpyProductManagementAttribute
    {
        return $this->getFactory()
            ->getProductAttributeQueryContainer()
            ->queryProductManagementAttribute()
            ->joinWithSpyProductAttributeKey()
            ->filterByIdProductManagementAttribute($idProductManagementAttribute)
            ->findOne();
    }
}
