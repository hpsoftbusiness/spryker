<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Zed\UserStore\Communication\UserStoreCommunicationFactory getFactory()
 * @method \Pyz\Zed\UserStore\Business\UserStoreFacadeInterface getFacade()
 */
class UserStoreFormExpanderPlugin extends AbstractPlugin implements UserFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expand User form with Store field.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $formExpander = $this->getFactory()->createUserStoreFormExpander();
        $dataProvider = $this->getFactory()->createStoreChoiceFormDataProvider();

        $formExpander->buildForm($builder, $dataProvider->getOptions());
    }
}
