<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Communication;

use Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper\ApiInboundLogMapper;
use Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper\ApiInboundLogMapperInterface;
use Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper\ApiOutboundLogMapper;
use Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper\ApiOutboundLogMapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\ApiLog\Business\ApiLogFacadeInterface getFacade()
 * @method \Pyz\Zed\ApiLog\Persistence\ApiLogEntityManagerInterface getEntityManager()
 */
class ApiLogCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper\ApiOutboundLogMapperInterface
     */
    public function createApiOutboundLogMapper(): ApiOutboundLogMapperInterface
    {
        return new ApiOutboundLogMapper();
    }

    /**
     * @return \Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper\ApiInboundLogMapperInterface
     */
    public function createApiInboundLogMapper(): ApiInboundLogMapperInterface
    {
        return new ApiInboundLogMapper();
    }
}
