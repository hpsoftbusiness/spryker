<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\Acl\Business\Model\RuleValidator as SprykerRuleValidator;

class RuleValidator extends SprykerRuleValidator
{
    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $rule
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function assert(RuleTransfer $rule, $bundle, $controller, $action)
    {
        $result = parent::assert($rule, $bundle, $controller, $action);

        // if there isn't access for table action, check also index action
        if (!$result && preg_match('/table$/', $action)) {
            $result = parent::assert($rule, $bundle, $controller, 'index');
        }

        return $result;
    }
}
