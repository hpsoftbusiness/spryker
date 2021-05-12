<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CategoryGui\Communication\Table;

use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable as SprykerCategoryTableAlias;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CategoryTable extends SprykerCategoryTableAlias
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config = parent::configure($config);

        $config->setSearchable([
            'spy_category.category_key',
            'attr.name',
        ]);

        return $config;
    }
}
