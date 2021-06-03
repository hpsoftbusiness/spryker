<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText;

use Spryker\Service\UtilText\UtilTextServiceInterface as SprykerUtilTextServiceInterface;

interface UtilTextServiceInterface extends SprykerUtilTextServiceInterface
{
    /**
     * Specification:
     * - Converts CamelCased string to snake_case.
     *
     * @api
     *
     * @param string $string
     *
     * @return string
     */
    public function camelCaseToSnakeCase(string $string): string;
}
