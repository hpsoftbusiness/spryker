<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Dependency\Plugin;

use Psr\Log\LoggerInterface;

interface ProductDataHealerPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function execute(?LoggerInterface $logger = null): void;
}
