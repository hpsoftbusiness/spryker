<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication;

use Pyz\Zed\UserStore\Communication\FormExpander\DataProvider\StoreChoiceFormDataProvider;
use Pyz\Zed\UserStore\Communication\FormExpander\UserStoreFormExpander;
use Pyz\Zed\UserStore\Communication\TableConfigExpander\UserStoreTableConfigExpander;
use Pyz\Zed\UserStore\Communication\TableDataExpander\UserStoreTableDataExpander;
use Pyz\Zed\UserStore\UserStoreDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \Pyz\Zed\UserStore\Business\UserStoreFacadeInterface getFacade()
 */
class UserStoreCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(UserStoreDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createUserStoreFormExpander(): FormTypeInterface
    {
        return new UserStoreFormExpander();
    }

    /**
     * @return \Pyz\Zed\UserStore\Communication\FormExpander\DataProvider\StoreChoiceFormDataProvider
     */
    public function createStoreChoiceFormDataProvider(): StoreChoiceFormDataProvider
    {
        return new StoreChoiceFormDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Pyz\Zed\UserStore\Communication\TableDataExpander\UserStoreTableDataExpander
     */
    public function createUserStoreTableDataExpander(): UserStoreTableDataExpander
    {
        return new UserStoreTableDataExpander($this->getStoreFacade());
    }

    /**
     * @return \Pyz\Zed\UserStore\Communication\TableConfigExpander\UserStoreTableConfigExpander
     */
    public function createUserStoreTableConfigExpander(): UserStoreTableConfigExpander
    {
        return new UserStoreTableConfigExpander();
    }
}
