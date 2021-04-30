<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText;

use Spryker\Service\UtilText\UtilTextService as SprykerUtilTextService;

/**
 * @method \Pyz\Service\UtilText\UtilTextServiceFactory getFactory()
 */
class UtilTextService extends SprykerUtilTextService implements UtilTextServiceInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function camelCaseToSnakeCase(string $string): string
    {
        return $this->getFactory()->createCamelCaseToSnakeCase()->filter($string);
    }
}
