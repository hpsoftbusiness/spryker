<?php

namespace Pyz\Client\MyWorldMarketplaceApi\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerInformationMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapDataToCustomerTransfer(array $data): CustomerTransfer;
}
