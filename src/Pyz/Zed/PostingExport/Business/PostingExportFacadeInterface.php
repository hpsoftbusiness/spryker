<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Business;

use DateTime;
use Generated\Shared\Transfer\ExportContentsTransfer;

interface PostingExportFacadeInterface
{
    /**
     * @param \DateTime|null $dateFrom
     * @param \DateTime|null $dateTo
     *
     * @return \Generated\Shared\Transfer\ExportContentsTransfer|null
     */
    public function generatePostingExportContent(?DateTime $dateFrom, ?DateTime $dateTo): ?ExportContentsTransfer;
}
