<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

use DateTime;

class CreateTurnoverRequest extends TurnoverRequest
{
    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return sprintf(
            '%s/customers/%s/turnovers',
            $this->myWorldMarketplaceApiConfig->getApiUrl(),
            $this->turnoverRequestHelper->getCustomerId($this->orderTransfer)
        );
    }

    /**
     * @return array
     */
    protected function getBody(): array
    {
        return [
            'Reference' => $this->turnoverRequestHelper->getTurnoverReference(
                $this->itemTransfer,
                $this->orderTransfer
            ),
            'Date' => date(DateTime::ISO8601, strtotime($this->orderTransfer->getCreatedAt())),
            'Amount' => bcdiv((string)$this->itemTransfer->getTurnoverAmount(), '100', 2),
            'Currency' => $this->orderTransfer->getCurrencyIsoCode(),
            'SegmentNumber' => $this->itemTransfer->getSegmentNumber(),
            'ProfileIdentifier' => $this->turnoverRequestHelper->getDealerId($this->orderTransfer),
        ];
    }

    /**
     * @return void
     */
    protected function onSuccess(): void
    {
        $id = $this->itemTransfer->getIdSalesOrderItem();
        $reference = $this->turnoverRequestHelper->getTurnoverReference(
            $this->itemTransfer,
            $this->orderTransfer
        );

        $this->myWorldMarketplaceApiEntityManager->setTurnoverCreated($id);
        $this->myWorldMarketplaceApiEntityManager->updateTurnoverReference($id, $reference);
    }
}
