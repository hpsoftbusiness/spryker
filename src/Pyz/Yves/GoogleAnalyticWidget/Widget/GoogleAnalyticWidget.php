<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\GoogleAnalyticWidget\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\GoogleAnalyticWidget\GoogleAnalyticWidgetConfig getConfig()
 */
class GoogleAnalyticWidget extends AbstractWidget implements WidgetInterface
{
    protected const NAME = 'GoogleAnalyticWidget';

    protected const WEB_PROPERTY_ID = 'webPropertyId';

    public function __construct()
    {
        $this->addParameter('webPropertyId', $this->getData()[static::WEB_PROPERTY_ID]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@GoogleAnalyticWidget/views/google-analytic/google-analytic.twig';
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [
            static::WEB_PROPERTY_ID => $this->getConfig()->getWebPropertyId(),
        ];
    }
}
