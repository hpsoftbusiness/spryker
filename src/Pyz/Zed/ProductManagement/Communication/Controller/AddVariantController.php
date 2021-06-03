<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\ProductManagement\Communication\Controller\AddVariantController as SprykerAddVariantController;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class AddVariantController extends SprykerAddVariantController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductAbstract = $this->castId(
            $request->get(
                self::PARAM_ID_PRODUCT_ABSTRACT
            )
        );

        $productAbstractTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage('The product [%s] does not exist.', ['%s' => $idProductAbstract]);

            return new RedirectResponse('/product-management');
        }

        $localeProvider = $this->getFactory()->createLocaleProvider();

        /** @var array|null $priceDimension */
        $priceDimension = $request->query->get(static::PARAM_PRICE_DIMENSION);
        $dataProvider = $this->getFactory()->createProductVariantFormAddDataProvider();
        $form = $this
            ->getFactory()
            ->getProductVariantFormAdd(
                $dataProvider->getData($priceDimension),
                $dataProvider->getOptions($productAbstractTransfer, ProductManagementConfig::PRODUCT_TYPE_REGULAR)
            )
            ->handleRequest($request);

        $bundledProductTable = $this->getFactory()
            ->createBundledProductTable();

        if ($form->isSubmitted() && $form->isValid()) {
            $productConcreteTransfer = $this->getFactory()
                ->createProductFormTransferGenerator()
                ->buildProductConcreteTransfer($productAbstractTransfer, $form);

            $productConcreteTransfer->setAttributes(
                array_merge($productConcreteTransfer->getAttributes(), $this->getFacade()->getDefaultAttributes())
            );

            $this->getFactory()
                ->getProductFacade()
                ->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);

            $type = $productConcreteTransfer->getProductBundle() === null ?
                ProductManagementConfig::PRODUCT_TYPE_REGULAR :
                ProductManagementConfig::PRODUCT_TYPE_BUNDLE;

            $this->getFactory()
                ->getProductFacade()
                ->touchProductConcrete($productConcreteTransfer->getIdProductConcrete());

            $this->addSuccessMessage(
                'The product [%s] was saved successfully.',
                [
                    '%s' => $productConcreteTransfer->getSku(),
                ]
            );

            return $this->createRedirectResponseAfterAdd(
                $productConcreteTransfer->getIdProductConcrete(),
                $type,
                $request
            );
        }

        return $this->viewResponse(
            [
                'form' => $form->createView(),
                'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
                'productAbstract' => $productAbstractTransfer,
                'localeCollection' => $localeProvider->getLocaleCollection(),
                'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
                'productConcreteFormAddTabs' => $this->getFactory()->createProductConcreteFormAddTabs()->createView(),
                'bundledProductTable' => $bundledProductTable->render(),
                'type' => ProductManagementConfig::PRODUCT_TYPE_REGULAR,
            ]
        );
    }
}
