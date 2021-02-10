<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductDataImport\Plugin\Twig;

use Spryker\Service\Twig\Plugin\AbstractTwigExtensionPlugin;
use Spryker\Shared\Twig\TwigFilter;

/**
 * @method \Pyz\Service\ProductDataImport\ProductDataImportService getService()
 */
class JsonDecodeTwigExtensionPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'jsonDecode',
                function ($jsonString) {
                    return $this->getService()->jsonDecode($jsonString);
                },
                ['is_safe' => ['html']]
            ),
        ];
    }
}
