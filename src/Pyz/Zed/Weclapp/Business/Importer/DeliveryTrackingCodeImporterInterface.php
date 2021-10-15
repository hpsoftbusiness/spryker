<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Importer;

interface DeliveryTrackingCodeImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function saveDeliveryTrackingCodesByWeclapp(array $restWeclappWebhooksAttributesTransfer): void;
}
