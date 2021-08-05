<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Gui\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Symfony\Component\Form\FormView;

class ExtendedTableConfiguration extends TableConfiguration
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var \Symfony\Component\Form\FormView|null
     */
    protected $groupActions;

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     *
     * @return void
     */
    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    /**
     * @return \Symfony\Component\Form\FormView|null
     */
    public function getGroupActions(): ?FormView
    {
        return $this->groupActions;
    }

    /**
     * @param \Symfony\Component\Form\FormView $groupActions
     *
     * @return void
     */
    public function setGroupActions(FormView $groupActions): void
    {
        $this->groupActions = $groupActions;
    }
}
