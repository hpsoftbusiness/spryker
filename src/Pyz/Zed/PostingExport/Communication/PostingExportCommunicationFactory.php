<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Communication;

use Pyz\Zed\PostingExport\Communication\Form\ExportForm;
use Pyz\Zed\PostingExport\Communication\ResponseBuilder\ExportCsvResponseBuilder;
use Pyz\Zed\PostingExport\Communication\ResponseBuilder\ExportResponseBuilderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Pyz\Zed\PostingExport\PostingExportConfig getConfig()
 * @method \Pyz\Zed\PostingExport\Business\PostingExportFacadeInterface getFacade()
 */
class PostingExportCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createExportForm(): FormInterface
    {
        return $this->getFormFactory()->create(ExportForm::class);
    }

    /**
     * @return \Pyz\Zed\PostingExport\Communication\ResponseBuilder\ExportResponseBuilderInterface
     */
    public function createExportCsvResponseBuilder(): ExportResponseBuilderInterface
    {
        return new ExportCsvResponseBuilder();
    }
}
