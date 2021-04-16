<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl\Business;

use Pyz\Zed\Acl\Business\Model\RuleValidator;
use Spryker\Zed\Acl\Business\AclBusinessFactory as SprykerAclBusinessFactory;

class AclBusinessFactory extends SprykerAclBusinessFactory
{
    /**
     * @return \Pyz\Zed\Acl\Business\Model\RuleValidator
     */
    public function createRuleValidatorHelper()
    {
        return new RuleValidator();
    }
}
