<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl\Communication;

use Pyz\Zed\Acl\Communication\Form\DeleteRoleForm;
use Spryker\Zed\Acl\Communication\AclCommunicationFactory as SprykerAclCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class AclCommunicationFactory extends SprykerAclCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteRoleForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteRoleForm::class, [], [
            'fields' => [],
        ]);
    }
}
