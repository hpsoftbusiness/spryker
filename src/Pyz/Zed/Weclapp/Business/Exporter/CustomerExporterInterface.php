<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

interface CustomerExporterInterface
{
    /**
     * @param array $customersIds
     * @param bool $exportNotExisting
     *
     * @return void
     */
    public function exportCustomers(array $customersIds, bool $exportNotExisting): void;
}
