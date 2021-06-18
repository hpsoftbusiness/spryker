<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Business;

interface CustomerGroupStorageFacadeInterface
{
    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    public function publish(int $idCustomerGroup): void;

    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    public function unpublish(int $idCustomerGroup): void;
}
