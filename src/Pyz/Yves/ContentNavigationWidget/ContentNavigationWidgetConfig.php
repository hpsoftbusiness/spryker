<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentNavigationWidget;

use Pyz\Shared\Navigation\NavigationConfig;
use SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig as SprykerContentNavigationWidget;

class ContentNavigationWidgetConfig extends SprykerContentNavigationWidget
{
    /**
     * @uses \Pyz\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_HEADER
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_HEADER = 'navigation-header';

    /**
     * @uses \Pyz\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_HEADER_MOBILE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_HEADER_MOBILE = 'navigation-header-mobile';

    /**
     * @uses \Pyz\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_FOOTER
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_FOOTER = 'navigation-footer';

    protected const NAVIGATION_NODE_VISIBILITY_SESSION_KEY = 'navigation_node';

    /**
     * @api
     *
     * @return string[]
     */
    public function getAvailableTemplateList(): array
    {
        $availableTemplates = parent::getAvailableTemplateList();
        $availableTemplates += [
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_HEADER => '@ContentNavigationWidget/views/navigation-header/navigation-header.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_HEADER_MOBILE => '@ContentNavigationWidget/views/navigation-header-mobile/navigation-header-mobile.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST_NAVIGATION_FOOTER => '@ContentNavigationWidget/views/navigation-footer/navigation-footer.twig',
        ];

        return $availableTemplates;
    }

    /**
     * @return string[]
     */
    public function getCategoryNavigationKeys(): array
    {
        return NavigationConfig::NAVIGATION_KEYS_CATEGORY_DRIVEN;
    }

    /**
     * @return string
     */
    public function getNavigationNodeVisibilitySessionKey(): string
    {
        return static::NAVIGATION_NODE_VISIBILITY_SESSION_KEY;
    }
}
