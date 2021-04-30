<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText\Model\Filter;

interface CamelCaseToSnakeCaseInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function filter(string $string): string;
}
