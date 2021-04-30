<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText\Plugin\Twig;

use Spryker\Service\Twig\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFilter;

/**
 * @method \Pyz\Service\UtilText\UtilTextServiceInterface getService()
 */
class TextUtilTwigExtensionPlugin extends AbstractTwigExtensionPlugin
{
    private const FILTER_NAME_PASCAL_TO_SNAKE_CASE = 'pascalToSnakeCase';

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                self::FILTER_NAME_PASCAL_TO_SNAKE_CASE,
                function (string $string): string {
                    return $this->getService()->camelCaseToSnakeCase($string);
                }
            ),
        ];
    }
}
