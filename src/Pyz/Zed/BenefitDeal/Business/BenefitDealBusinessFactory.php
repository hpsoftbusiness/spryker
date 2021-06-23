<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business;

use Pyz\Zed\BenefitDeal\BenefitDealDependencyProvider;
use Pyz\Zed\BenefitDeal\Business\Label\ProductAbstractRelationReader;
use Pyz\Zed\BenefitDeal\Business\Label\ProductAbstractRelationReaderInterface;
use Pyz\Zed\BenefitDeal\Business\Model\BenefitDealReader;
use Pyz\Zed\BenefitDeal\Business\Model\BenefitDealReaderInterface;
use Pyz\Zed\BenefitDeal\Business\Model\BenefitDealWriter;
use Pyz\Zed\BenefitDeal\Business\Model\BenefitDealWriterInterface;
use Pyz\Zed\BenefitDeal\Business\Model\Item\Expander\ItemBenefitDealChargeAmountExpander;
use Pyz\Zed\BenefitDeal\Business\Model\Item\Expander\ItemBenefitDealChargeAmountExpanderInterface;
use Pyz\Zed\BenefitDeal\Business\Model\Item\Expander\ItemBenefitExpander;
use Pyz\Zed\BenefitDeal\Business\Model\Item\Expander\ItemBenefitExpanderInterface;
use Pyz\Zed\BenefitDeal\Business\Model\Item\ItemBenefitDealReader;
use Pyz\Zed\BenefitDeal\Business\Model\Item\ItemBenefitDealReaderInterface;
use Pyz\Zed\BenefitDeal\Business\Quote\QuoteEqualizer;
use Pyz\Zed\BenefitDeal\Business\Quote\QuoteEqualizerInterface;
use Pyz\Zed\BenefitDeal\Business\Sales\ItemPreSaveExpander;
use Pyz\Zed\BenefitDeal\Business\Sales\ItemPreSaveExpanderInterface;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;

/**
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface getRepository()
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealEntityManagerInterface getEntityManager()
 */
class BenefitDealBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Model\BenefitDealReaderInterface
     */
    public function createBenefitDealReader(): BenefitDealReaderInterface
    {
        return new BenefitDealReader($this->getRepository());
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Model\BenefitDealWriterInterface
     */
    public function createBenefitDealWriter(): BenefitDealWriterInterface
    {
        return new BenefitDealWriter($this->getEntityManager());
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Model\Item\ItemBenefitDealReaderInterface
     */
    public function createItemBenefitDealReader(): ItemBenefitDealReaderInterface
    {
        return new ItemBenefitDealReader(
            $this->getRepository(),
            $this->getItemBenefitDealHydratorPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Sales\ItemPreSaveExpanderInterface
     */
    public function createItemPreSaveExpander(): ItemPreSaveExpanderInterface
    {
        return new ItemPreSaveExpander($this->getItemEntityExpanderPlugins());
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Quote\QuoteEqualizerInterface
     */
    public function createQuoteEqualizer(): QuoteEqualizerInterface
    {
        return new QuoteEqualizer();
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Label\ProductAbstractRelationReaderInterface
     */
    public function createProductAbstractRelationReader(): ProductAbstractRelationReaderInterface
    {
        return new ProductAbstractRelationReader(
            $this->getProductLabelFacade(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Model\Item\Expander\ItemBenefitExpanderInterface
     */
    public function createItemBenefitExpander(): ItemBenefitExpanderInterface
    {
        return new ItemBenefitExpander(
            $this->getPriceProductFacade(),
            $this->getStoreClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\Model\Item\Expander\ItemBenefitDealChargeAmountExpanderInterface
     */
    public function createItemBenefitDealChargeAmountExpander(): ItemBenefitDealChargeAmountExpanderInterface
    {
        return new ItemBenefitDealChargeAmountExpander();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    public function getProductLabelFacade(): ProductLabelFacadeInterface
    {
        return $this->getProvidedDependency(BenefitDealDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(BenefitDealDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->getProvidedDependency(BenefitDealDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemEntityExpanderPluginInterface[]
     */
    private function getItemEntityExpanderPlugins(): array
    {
        return $this->getProvidedDependency(BenefitDealDependencyProvider::PLUGINS_ITEM_ENTITY_EXPANDER);
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemBenefitDealHydratorPluginInterface[]
     */
    private function getItemBenefitDealHydratorPlugins(): array
    {
        return $this->getProvidedDependency(BenefitDealDependencyProvider::PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR);
    }
}
