<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Exception;

use Spryker\Zed\DataImport\Business\Exception\DataImportException;

class InvalidStoreException extends DataImportException
{
    /**
     * @param string $expectedStore
     * @param string $dataSetStore
     */
    public function __construct(string $expectedStore, string $dataSetStore)
    {
        parent::__construct($this->getExceptionMessage($expectedStore, $dataSetStore));
    }

    /**
     * @param string $expectedStore
     * @param string $dataSetStore
     *
     * @return string
     */
    private function getExceptionMessage(string $expectedStore, string $dataSetStore): string
    {
        return sprintf('Wrong store in dataSet. Expected: "%s", got: "%s"', $expectedStore, $dataSetStore);
    }
}
