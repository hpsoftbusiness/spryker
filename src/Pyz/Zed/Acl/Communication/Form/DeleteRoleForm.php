<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl\Communication\Form;

use Spryker\Zed\Acl\Communication\Form\DeleteRoleForm as SprykerDeleteRoleForm;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteRoleForm extends SprykerDeleteRoleForm
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('action');
        $resolver->setRequired('fields');

        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-inline',
            ],
            'allow_extra_fields' => true,
        ]);
    }
}
