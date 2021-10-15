<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Persistence;

use Pyz\Zed\ApiLog\Persistence\Propel\Mapper\ApiInboundLogMapper;
use Pyz\Zed\ApiLog\Persistence\Propel\Mapper\ApiInboundLogMapperInterface;
use Pyz\Zed\ApiLog\Persistence\Propel\Mapper\ApiOutboundLogMapper;
use Pyz\Zed\ApiLog\Persistence\Propel\Mapper\ApiOutboundLogMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\ApiLog\Persistence\ApiLogEntityManagerInterface getEntityManager()
 */
class ApiLogPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Pyz\Zed\ApiLog\Persistence\Propel\Mapper\ApiOutboundLogMapperInterface
     */
    public function createApiOutboundLogMapper(): ApiOutboundLogMapperInterface
    {
        return new ApiOutboundLogMapper();
    }

    /**
     * @return \Pyz\Zed\ApiLog\Persistence\Propel\Mapper\ApiInboundLogMapperInterface
     */
    public function createApiInboundLogMapper(): ApiInboundLogMapperInterface
    {
        return new ApiInboundLogMapper();
    }
}
