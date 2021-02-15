<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException;
use Spryker\Zed\ProductManagement\Communication\Controller\AddController as SprykerAddController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class AddController extends SprykerAddController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createProductFormAddDataProvider();

        $type = $request->query->get('type');

        /** @var array|null $priceDimension */
        $priceDimension = $request->query->get(static::PARAM_PRICE_DIMENSION);
        $form = $this
            ->getFactory()
            ->createProductFormAdd(
                $dataProvider->getData($priceDimension),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $localeProvider = $this->getFactory()->createLocaleProvider();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $productAbstractTransfer = $this->getFactory()
                    ->createProductFormTransferGenerator()
                    ->buildProductAbstractTransfer($form, null)
                    ->setAttributes($this->getFacade()->getDefaultAttributes());

                $concreteProductCollection = $this->createProductConcreteCollection(
                    $type,
                    $productAbstractTransfer,
                    $form
                );

                $idProductAbstract = $this->getFactory()
                    ->getProductFacade()
                    ->addProduct($productAbstractTransfer, $concreteProductCollection);

                $this->addSuccessMessage('The product [%s] was added successfully.', [
                    '%s' => $productAbstractTransfer->getSku(),
                ]);

                return $this->createRedirectResponseAfterAdd($idProductAbstract, $request);
            } catch (CategoryUrlExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            } catch (ProductAbstractExistsException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'concreteProductCollection' => [],
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'attributeLocaleCollection' => $localeProvider->getLocaleCollection(true),
            'productFormAddTabs' => $this->getFactory()->createProductFormAddTabs()->createView(),
            'type' => $type,
        ]);
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function createProductConcreteCollection(
        $type,
        ProductAbstractTransfer $productAbstractTransfer,
        FormInterface $form
    ) {
        $concreteProductCollection = parent::createProductConcreteCollection($type, $productAbstractTransfer, $form);

        foreach ($concreteProductCollection as $concreteProduct) {
            $concreteProduct->setAttributes(array_merge($concreteProduct->getAttributes(), $this->getFacade()->getDefaultAttributes()));
        }

        return $concreteProductCollection;
    }
}
