<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Communication\Console;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Communication\MyWorldMarketplaceApiCommunicationFactory getFactory()
 */
class UpdateEmptyTurnoverReferenceConsole extends Console
{
    public const COMMAND_NAME = 'sales:update-empty-turnover-reference';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('This command has to be run once in order to update turnover reference for older order items');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderItems = SpySalesOrderItemQuery::create()
            ->filterByTurnoverReference()
            ->filterByIsTurnoverCreated(true)
            ->find();

        foreach ($orderItems as $item) {
            $item->setTurnoverReference($this->getTurnoverReferenceByOrder($item->getOrder()));
            $item->save();
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return string
     */
    public function getTurnoverReferenceByOrder(SpySalesOrder $order)
    {
        return sprintf(
            '%s-%s-%s',
            $this->getFactory()->getConfig()->getOrderReferencePrefix(),
            $order->getOrderReference(),
            $order->getCreatedAt()->getTimestamp()
        );
    }
}
