<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment\Mapper;

use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class QuoteResponseTransferMapper implements QuoteResponseTransferMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $abstractTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function transferResponseToMyWorldApiResponseTransfer(TransferInterface $abstractTransfer): MyWorldApiResponseTransfer
    {
        return (new MyWorldApiResponseTransfer())->fromArray($abstractTransfer->toArray());
    }
}
