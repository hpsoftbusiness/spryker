<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText\Model;

use Spryker\Service\UtilText\Model\Slug as SprykerSlug;

class Slug extends SprykerSlug
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function generate($value)
    {
        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        }

        $value = preg_replace('/[^a-zA-Z0-9 -]/', '', trim($value));
        $value = mb_strtolower($value);
        $value = str_replace(' ', '-', $value);
        $value = preg_replace('/(\-)\1+/', '$1', $value);

        return $value;
    }
}
