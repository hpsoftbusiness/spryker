<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

use Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapper;
use Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface;
use Pyz\Client\MyWorldPayment\Zed\MyWorldPaymentStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class MyWorldPaymentFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\MyWorldPayment\Zed\MyWorldPaymentStub
     */
    public function createZedStub(): MyWorldPaymentStub
    {
        return new MyWorldPaymentStub(
            $this->getZedRequestClient(),
            $this->createQuoteResponseTransferMapper()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface
     */
    public function createQuoteResponseTransferMapper(): QuoteResponseTransferMapperInterface
    {
        return new QuoteResponseTransferMapper();
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
