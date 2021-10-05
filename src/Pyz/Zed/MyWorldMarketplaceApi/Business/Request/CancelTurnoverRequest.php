<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

class CancelTurnoverRequest extends TurnoverRequest
{
    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return sprintf(
            '%s/dealers/%s/turnovers/%s/cancel',
            $this->myWorldMarketplaceApiConfig->getApiUrl(),
            $this->turnoverRequestHelper->getDealerId($this->orderTransfer),
            $this->itemTransfer->getTurnoverReference()
        );
    }

    /**
     * @return array
     */
    protected function getBody(): array
    {
        return [
            'Amount' => bcdiv((string)$this->itemTransfer->getTurnoverAmount(), '100', 2),
            'Currency' => $this->orderTransfer->getCurrencyIsoCode(),
        ];
    }

    /**
     * @return void
     */
    protected function onSuccess(): void
    {
        $this->myWorldMarketplaceApiEntityManager->setTurnoverCancelled($this->itemTransfer->getIdSalesOrderItem());
    }
}
