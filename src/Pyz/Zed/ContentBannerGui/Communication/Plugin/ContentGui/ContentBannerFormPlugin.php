<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ContentBannerGui\Communication\Plugin\ContentGui;

use Pyz\Zed\ContentBannerGui\Communication\Form\BannerContentTermForm;
use Spryker\Zed\ContentBannerGui\Communication\Plugin\ContentGui\ContentBannerFormPlugin as SprykerContentBannerFormPlugin;

class ContentBannerFormPlugin extends SprykerContentBannerFormPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getForm(): string
    {
        return BannerContentTermForm::class;
    }
}
