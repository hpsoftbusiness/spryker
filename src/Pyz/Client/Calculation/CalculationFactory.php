<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Calculation;

use Pyz\Client\Calculation\Zed\CalculationStub;
use Pyz\Client\Calculation\Zed\CalculationStubInterface;
use Spryker\Client\Calculation\CalculationFactory as SprykerCalculationFactory;

class CalculationFactory extends SprykerCalculationFactory
{
    /**
     * @return \Pyz\Client\Calculation\Zed\CalculationStubInterface
     */
    public function createZedStub(): CalculationStubInterface
    {
        return new CalculationStub($this->getZedRequestClient());
    }
}
