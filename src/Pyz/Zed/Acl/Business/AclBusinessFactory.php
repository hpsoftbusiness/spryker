<?php

namespace Pyz\Zed\Acl\Business;

use Spryker\Zed\Acl\Business\AclBusinessFactory as SprykerAclBusinessFactory;
use Pyz\Zed\Acl\Business\Model\RuleValidator;

class AclBusinessFactory extends SprykerAclBusinessFactory
{
    /**
     * @return RuleValidator
     */
    public function createRuleValidatorHelper()
    {
        return new RuleValidator();
    }
}
