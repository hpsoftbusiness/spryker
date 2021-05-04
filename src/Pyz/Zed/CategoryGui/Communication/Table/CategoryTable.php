<?php

namespace Pyz\Zed\CategoryGui\Communication\Table;

use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable as SprykerCategoryTableAlias;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CategoryTable extends SprykerCategoryTableAlias
{
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
