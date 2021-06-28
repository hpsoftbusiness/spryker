<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\PriceProduct\Communication\Plugin\Product;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Psr\Log\LoggerInterface;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Pyz\Zed\PriceProduct\Communication\Plugin\Product\BenefitPriceDataHealerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group PriceProduct
 * @group Communication
 * @group Plugin
 * @group Product
 * @group BenefitPriceDataHealerPluginTest
 * Add your own group annotations below this line
 */
class BenefitPriceDataHealerPluginTest extends Unit
{
    /**
     * @var \PyzTest\Zed\PriceProduct\PriceProductCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\PriceProduct\Communication\Plugin\Product\BenefitPriceDataHealerPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new BenefitPriceDataHealerPlugin();
    }

    /**
     * @return void
     */
    public function testCreatesBenefitPrices(): void
    {
        $productConcreteTransfer_1 = $this->tester->haveFullProduct();
        $this->updateAbstractProductBenefitSalesAttributeValue(
            $productConcreteTransfer_1->getFkProductAbstract(),
            '18.99'
        );

        $productConcreteTransfer_2 = $this->tester->haveFullProduct();
        $this->updateAbstractProductBenefitSalesAttributeValue(
            $productConcreteTransfer_2->getFkProductAbstract(),
            '20.29'
        );
        $this->updateConcreteProductBenefitSaleAttributeValue(
            $productConcreteTransfer_2->getIdProductConcrete(),
            '19.99'
        );

        $productConcreteTransfer_3 = $this->tester->haveFullProduct();

        $this->sut->execute($this->mockConsoleLogger());

        self::assertEquals(1899, $this->findBenefitPriceBySku($productConcreteTransfer_1->getAbstractSku()));
        self::assertEquals(1899, $this->findBenefitPriceBySku($productConcreteTransfer_1->getSku()));

        self::assertEquals(2029, $this->findBenefitPriceBySku($productConcreteTransfer_2->getAbstractSku()));
        self::assertEquals(1999, $this->findBenefitPriceBySku($productConcreteTransfer_2->getSku()));

        self::assertNull($this->findBenefitPriceBySku($productConcreteTransfer_3->getAbstractSku()));
        self::assertNull($this->findBenefitPriceBySku($productConcreteTransfer_3->getSku()));
    }

    /**
     * @param int $idProduct
     * @param string $value
     *
     * @return void
     */
    private function updateConcreteProductBenefitSaleAttributeValue(int $idProduct, string $value): void
    {
        $spyProduct = SpyProductQuery::create()
            ->findOneByIdProduct($idProduct);

        $spyProduct->setAttributes(json_encode([$this->getBenefitStoreSalesPriceAttributeName() => $value]));
        $spyProduct->save();
    }

    /**
     * @param int $idProductAbstract
     * @param string $value
     *
     * @return void
     */
    private function updateAbstractProductBenefitSalesAttributeValue(int $idProductAbstract, string $value): void
    {
        $spyProductAbstract = SpyProductAbstractQuery::create()
            ->findOneByIdProductAbstract($idProductAbstract);

        $spyProductAbstract->setAttributes(json_encode([$this->getBenefitStoreSalesPriceAttributeName() => $value]));
        $spyProductAbstract->save();
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    private function findBenefitPriceBySku(string $sku): ?int
    {
        return $this->getFacade()->findPriceBySku($sku, $this->getBenefitPriceTypeName());
    }

    /**
     * @return \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockConsoleLogger(): LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);
    }

    /**
     * @return string
     */
    private function getBenefitStoreSalesPriceAttributeName(): string
    {
        return $this->tester->getModuleConfig()->getProductAttributeKeyBenefitSalesPrice();
    }

    /**
     * @return string
     */
    private function getBenefitPriceTypeName(): string
    {
        return $this->getFacade()->getBenefitPriceTypeName();
    }

    /**
     * @return \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    private function getFacade(): PriceProductFacadeInterface
    {
        return $this->tester->getLocator()->priceProduct()->facade();
    }
}
