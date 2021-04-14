<?php

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
     * @param $builder
     * @return $this
     */
    protected function addOpenUrlInNewTab($builder): self
    {
        $builder->add(static::FIELD_OPEN_URL_IN_NEW_TAB, CheckboxType::class, [
            'label' => static::LABEL_OPEN_URL_IN_NEW_TAB,
            'required' => false,
        ]);

        return $this;
    }
}
