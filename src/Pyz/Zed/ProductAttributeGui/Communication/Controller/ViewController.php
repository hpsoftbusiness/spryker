<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Controller;

use Pyz\Zed\ProductAttribute\ProductAttributeConfig;
use Spryker\Zed\ProductAttributeGui\Communication\Controller\ViewController as SprykerViewController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class ViewController extends SprykerViewController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function productAction(Request $request)
    {
        $idProduct = $this->castId($request->get(
            static::PARAM_ID_PRODUCT
        ));

        $dataProvider = $this->getFactory()->createAttributeKeyFormDataProvider();
        $form = $this
            ->getFactory()
            ->getAttributeKeyForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $values = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->getProductAttributeValues($idProduct);

        $valueKeys = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->extractKeysFromAttributes($values[ProductAttributeConfig::KEY_ATTRIBUTES]);

        $hiddenAttributesValueKeys = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->extractKeysFromAttributes($values[ProductAttributeConfig::KEY_HIDDEN_ATTRIBUTES]);

        $productTransfer = $this->getProductTransfer($idProduct);
        $productAbstractTransfer = $this->getProductAbstractTransfer(
            $productTransfer->requireFkProductAbstract()->getFkProductAbstract()
        );

        $metaAttributes = $this
            ->getFactory()
            ->getPyzProductAttributeFacade()
            ->getMetaAttributesForProduct($idProduct, ProductAttributeConfig::KEY_ATTRIBUTES);

        $metaHiddenAttributes = $this
            ->getFactory()
            ->getPyzProductAttributeFacade()
            ->getMetaAttributesForProduct($idProduct, ProductAttributeConfig::KEY_HIDDEN_ATTRIBUTES);

        $localesData = $this->getLocaleData();

        $csrfForm = $this
            ->getFactory()
            ->getAttributeCsrfForm();

        return $this->viewResponse([
            'attributeKeyForm' => $form->createView(),
            'locales' => $localesData,
            'metaAttributes' => $metaAttributes,
            'metaHiddenAttributes' => $metaHiddenAttributes,
            'productAttributes' => $values[ProductAttributeConfig::KEY_ATTRIBUTES],
            'productHiddenAttributes' => $values[ProductAttributeConfig::KEY_HIDDEN_ATTRIBUTES],
            'productAttributeKeys' => $valueKeys,
            'productHiddenAttributeKeys' => $hiddenAttributesValueKeys,
            'localesJson' => json_encode(array_values($localesData)),
            'productAttributesJson' => json_encode($values[ProductAttributeConfig::KEY_ATTRIBUTES]),
            'productHiddenAttributesJson' => json_encode($values[ProductAttributeConfig::KEY_HIDDEN_ATTRIBUTES]),
            'metaAttributesJson' => json_encode($metaAttributes),
            'productAbstract' => $productAbstractTransfer,
            'product' => $productTransfer,
            'csrfForm' => $csrfForm->createView(),
        ]);
    }
}
