<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Business;

use DateTime;
use Generated\Shared\Transfer\ExportContentsTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\PostingExport\Business\PostingExportBusinessFactory getFactory()
 */
class PostingExportFacade extends AbstractFacade implements PostingExportFacadeInterface
{
    /**
     * @inheritDoc
     */
    public function generatePostingExportContent(?DateTime $dateFrom, ?DateTime $dateTo): ?ExportContentsTransfer
    {
        return $this->getFactory()
            ->createPostingExportContentBuilder()
            ->generateContent($dateFrom, $dateTo);
    }
}
