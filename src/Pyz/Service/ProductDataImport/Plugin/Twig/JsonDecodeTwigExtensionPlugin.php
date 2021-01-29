<?php

namespace Pyz\Service\ProductDataImport\Plugin\Twig;

use Spryker\Shared\Twig\TwigFilter;
use Spryker\Service\Twig\Plugin\AbstractTwigExtensionPlugin;

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
