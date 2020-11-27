<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Customer;

use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomerQuery;
use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Pyz\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CustomerWriterStep implements DataImportStepInterface
{
    public const COL_CUSTOMER_REFERENCE = 'customer_reference';
    public const COL_CUSTOMER_GROUP = 'customer_group';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $customerEntity = SpyCustomerQuery::create()
            ->filterByCustomerReference($dataSet[self::COL_CUSTOMER_REFERENCE])
            ->findOneOrCreate();

        $customerEntity->fromArray($dataSet->getArrayCopy());
        $customerEntity->save();

        $sequenceNumberEntity = SpySequenceNumberQuery::create()
            ->filterByName(CustomerConstants::NAME_CUSTOMER_REFERENCE)
            ->findOneOrCreate();

        $this->persistCustomerGroupToCustomerEntity($dataSet, $customerEntity);

        $currentId = $this->getCurrentId($dataSet);
        if ($currentId > $sequenceNumberEntity->getCurrentId()) {
            $sequenceNumberEntity->setCurrentId($currentId);
            $sequenceNumberEntity->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function persistCustomerGroupToCustomerEntity(
        DataSetInterface $dataSet,
        SpyCustomer $customerEntity
    ): void {
        if (!$dataSet[static::COL_CUSTOMER_GROUP]) {
            return;
        }

        $customerGroupEntity = SpyCustomerGroupQuery::create()
            ->findOneByName($dataSet[static::COL_CUSTOMER_GROUP]);

        if (!$customerGroupEntity) {
            throw new EntityNotFoundException(
                sprintf('Customer group with name "%s" not found.', $dataSet[static::COL_CUSTOMER_GROUP])
            );
        }

        $customerGroupToCustomerEntity = SpyCustomerGroupToCustomerQuery::create()
            ->filterByFkCustomerGroup($customerGroupEntity->getIdCustomerGroup())
            ->filterByFkCustomer($customerEntity->getIdCustomer())
            ->findOneOrCreate();

        if ($customerGroupToCustomerEntity->isNew()) {
            $customerGroupToCustomerEntity->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return int
     */
    protected function getCurrentId(DataSetInterface $dataSet)
    {
        if (!preg_match('/(\d+)$/', preg_quote($dataSet[self::COL_CUSTOMER_REFERENCE], '/'), $matches)) {
            throw new InvalidDataException(sprintf(
                'Invalid customer reference: "%s". Value expected to end with a number.',
                preg_quote($dataSet[self::COL_CUSTOMER_REFERENCE], '/')
            ));
        }

        return (int)$matches[1];
    }
}
