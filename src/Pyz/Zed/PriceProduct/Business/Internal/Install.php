<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct\Business\Internal;

use Spryker\Zed\PriceProduct\Business\Internal\Install as SprykerInstall;

class Install extends SprykerInstall
{
    /**
     * @var \Pyz\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @return void
     */
    public function install(): void
    {
        foreach ($this->getPriceTypes() as $priceTypeName) {
            $this->priceTypeWriter->createPriceType($priceTypeName);
        }
    }

    /**
     * @return string[]
     */
    private function getPriceTypes(): array
    {
        return [
            $this->config->getPriceTypeDefaultName(),
            $this->config->getPriceTypeOriginalName(),
            $this->config->getPriceTypeSPBenefitName(),
        ];
    }
}
