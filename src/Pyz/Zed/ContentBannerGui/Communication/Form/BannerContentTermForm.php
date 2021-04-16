<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ContentBannerGui\Communication\Form;

use Spryker\Zed\ContentBannerGui\Communication\Form\BannerContentTermForm as SprykerBannerContentTermForm;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class BannerContentTermForm extends SprykerBannerContentTermForm
{
    public const FIELD_OPEN_URL_IN_NEW_TAB = 'openUrlInNewTab';
    public const LABEL_OPEN_URL_IN_NEW_TAB = 'Open URL in new tab';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTitleField($builder);
        $this->addSubtitleField($builder);
        $this->addImageUrlField($builder);
        $this->addClickUrlField($builder);
        $this->addOpenUrlInNewTab($builder);
        $this->addAltTextField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOpenUrlInNewTab(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_OPEN_URL_IN_NEW_TAB, CheckboxType::class, [
            'label' => static::LABEL_OPEN_URL_IN_NEW_TAB,
            'required' => false,
        ]);

        return $this;
    }
}
