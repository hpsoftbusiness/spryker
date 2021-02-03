<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Controller;

use League\Csv\Writer;
use SplTempFileObject;
use Spryker\Zed\Sales\Communication\Controller\IndexController as SprykerIndexController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Pyz\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Pyz\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class IndexController extends SprykerIndexController
{
    private const FILE_NAME = 'orderlist.csv';

    /**
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createOrdersTable();
        $stateIds = $this->getQueryContainer()->querySalesOrderItem()->distinct()->select(['fk_oms_order_item_state'])->find()->getData();

        $states = $this->getRepository()->getSpyOmsOrderItemStatesByIds($stateIds);

        $request = $this->getApplication()->get('request');

        return [
            'orders' => $table->render(),
            'states' => $states,
            'idOmsOrderItemState' => $request->query->getInt('id-state') ?? '',
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportAction()
    {
        if ($searchQuery = $this->getApplication()->get('session')->get('search')) {
            $this->getApplication()->get('request')->query->set('search', $searchQuery);
        }

        $table = $this->getFactory()->createOrdersTable();

        $exportData = $table->fetchData()['exportData'];

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setDelimiter(';');
        $csv->insertOne(array_keys($exportData[0] ?? []));
        $csv->insertAll($exportData);
        $csvContent = $csv->getContent();

        $response = new Response($csvContent);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            self::FILE_NAME
        );

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
