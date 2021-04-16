<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText;

use Pyz\Service\UtilText\Model\Slug;
use Spryker\Service\UtilText\UtilTextServiceFactory as SprykerUtilTextServiceFactory;

class UtilTextServiceFactory extends SprykerUtilTextServiceFactory
{
    /**
     * @return \Spryker\Service\UtilText\Model\SlugInterface
     */
    public function createTextSlug()
    {
        return new Slug();
    }
}
