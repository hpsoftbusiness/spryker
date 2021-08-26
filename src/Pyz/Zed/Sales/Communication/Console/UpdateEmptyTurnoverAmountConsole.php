<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Console;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Pyz\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Pyz\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class UpdateEmptyTurnoverAmountConsole extends Console
{
    public const COMMAND_NAME = 'sales:update-empty-turnover-amount';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('This command has to be run once in order to update turnover amount for older order items');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderItems = SpySalesOrderItemQuery::create()->filterByTurnoverAmount()->find();

        foreach ($orderItems as $item) {
            $turnoverAmount = $item->getPrice();
            $benefitDeal = $item->getPyzSalesOrderItemBenefitDeals()->getFirst();

            if ($benefitDeal !== null) {
                $turnoverAmount = $benefitDeal->getUnitBenefitPrice();
            }

            $item->setTurnoverAmount($turnoverAmount * $item->getQuantity());
            $item->save();
        }

        return static::CODE_SUCCESS;
    }
}
