<?php

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
