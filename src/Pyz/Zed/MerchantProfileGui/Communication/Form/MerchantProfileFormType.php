<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfileGui\Communication\Form;

use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileFormType as SprykerMerchantProfileFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class MerchantProfileFormType extends SprykerMerchantProfileFormType
{
    protected const FIELD_MY_WORLD_ID = 'my_world_id';
    protected const FIELD_STANDARD_CASHBACK = 'standard_cashback';
    protected const FIELD_DETAIL_PAGE_URL = 'detail_page_url';
    protected const FIELD_STANDARD_STORY_POINTS = 'standard_story_points';

    protected const LABEL_MY_WORLD_ID = 'MyWorld Id';
    protected const LABEL_STANDARD_CASHBACK = 'Standard cashback';
    protected const LABEL_DETAIL_PAGE_URL = 'External link to merchant detail page';
    protected const LABEL_STANDARD_STORY_POINTS = 'Standard Shopping Points';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this
            ->addMyWorldId($builder)
            ->addStandardCashback($builder)
            ->addDetailPageUrl($builder)
            ->addStandardStoryPoints($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMyWorldId(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_MY_WORLD_ID,
            TextType::class,
            [
                'label' => static::LABEL_MY_WORLD_ID,
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStandardCashback(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STANDARD_CASHBACK,
            TextType::class,
            [
                'label' => static::LABEL_STANDARD_CASHBACK,
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDetailPageUrl(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_DETAIL_PAGE_URL,
            TextType::class,
            [
                'label' => static::LABEL_DETAIL_PAGE_URL,
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStandardStoryPoints(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STANDARD_STORY_POINTS,
            TextType::class,
            [
                'label' => static::LABEL_STANDARD_STORY_POINTS,
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ]
        );

        return $this;
    }
}
