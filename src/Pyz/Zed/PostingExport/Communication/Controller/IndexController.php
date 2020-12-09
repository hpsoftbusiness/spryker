<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Communication\Controller;

use Generated\Shared\Transfer\ExportContentsTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\PostingExport\Communication\PostingExportCommunicationFactory getFactory()
 * @method \Pyz\Zed\PostingExport\Business\PostingExportFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    public const FIELD_DATE_FROM = 'date-from';
    public const FIELD_DATE_TO = 'date-to';
    public const ACTION_POSTINGS = 'postings';

    protected const ERROR_MESSAGE_NO_ORDERS_IN_TIME_PERIOD_FOUND = 'export.posting.error.no-orders-in-time-period-found';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $exportForm = $this->getFactory()
            ->createExportForm()
            ->handleRequest($request);

        if ($exportForm->isSubmitted() && $exportForm->isValid()) {
            $data = $exportForm->getData();

            $postingExportContentsTransfer = $this->getPostingExportContentsTransfer($request, $data);
            if ($postingExportContentsTransfer) {
                return $this->getFactory()->createExportJsonResponseBuilder()
                    ->buildResponse($postingExportContentsTransfer);
            }

            $this->addInfoMessage(
                self::ERROR_MESSAGE_NO_ORDERS_IN_TIME_PERIOD_FOUND
            );
        }

        return $this->viewResponse([
            'form' => $exportForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ExportContentsTransfer|null
     */
    protected function getPostingExportContentsTransfer(Request $request, array $data): ?ExportContentsTransfer
    {
        if ($request->request->has(static::ACTION_POSTINGS)) {
            return $this->getFacade()->generatePostingExportContent(
                $data[static::FIELD_DATE_FROM],
                $data[static::FIELD_DATE_TO]
            );
        }

        return null;
    }
}
