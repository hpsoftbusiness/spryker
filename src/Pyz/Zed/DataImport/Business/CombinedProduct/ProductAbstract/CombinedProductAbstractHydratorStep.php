<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract;

use Generated\Shared\Transfer\SpyUrlEntityTransfer;
use Pyz\Zed\DataImport\Business\CombinedProduct\Product\CombinedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Exception\InvalidDataException;
use Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CombinedProductAbstractHydratorStep extends ProductAbstractHydratorStep
{
    public const BULK_SIZE = 5000;

    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';

    public const COLUMN_CATEGORY_KEY = 'product_abstract.category_key';
    public const COLUMN_CATEGORY_PRODUCT_ORDER = 'product_abstract.category_product_order';
    public const COLUMN_URL = 'product_abstract.url';
    public const COLUMN_COLOR_CODE = 'product_abstract.color_code';
    public const COLUMN_TAX_SET_NAME = 'product_abstract.tax_set_name';
    public const COLUMN_META_TITLE = 'product_abstract.meta_title';
    public const COLUMN_META_KEYWORDS = 'product_abstract.meta_keywords';
    public const COLUMN_META_DESCRIPTION = 'product_abstract.meta_description';
    public const COLUMN_NEW_FROM = 'product_abstract.new_from';
    public const COLUMN_NEW_TO = 'product_abstract.new_to';

    public const COLUMN_IS_AFFILIATE = 'product.is_affiliate';
    public const COLUMN_AFFILIATE_DATA = 'product.affiliate_data';

    public const COLUMN_BRAND_NAME = 'product.value_49';

    public const COLUMN_NAME = 'product.name';
    public const COLUMN_DESCRIPTION = 'product.description';

    public const COLUMN_ASSIGNED_PRODUCT_TYPE = 'product.assigned_product_type';

    protected const ASSIGNABLE_PRODUCT_TYPE_ABSTRACT = 'abstract';
    protected const ASSIGNABLE_PRODUCT_TYPE_BOTH = 'both';
    protected const ASSIGNABLE_PRODUCT_TYPE_CONCRETE = 'concrete';

    public const ASSIGNABLE_PRODUCT_TYPES = [
        self::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
        self::ASSIGNABLE_PRODUCT_TYPE_BOTH,
    ];

    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(UtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertAssignableProductTypeColumn($dataSet);

        parent::execute($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Pyz\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function assertAssignableProductTypeColumn(DataSetInterface $dataSet): void
    {
        $isAbstractSkuIsEmpty = $dataSet[static::COLUMN_ABSTRACT_SKU] ?: null;
        $isConcreteSkuIsEmpty = $dataSet[static::COLUMN_CONCRETE_SKU] ?: null;

        if ($isAbstractSkuIsEmpty === null) {
            $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] = static::ASSIGNABLE_PRODUCT_TYPE_BOTH;
        }

        if ($isConcreteSkuIsEmpty === null) {
            $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] = static::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT;
        }

        if ($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] === "") {
            $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] = static::ASSIGNABLE_PRODUCT_TYPE_BOTH;
        }

        if (empty($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" must be defined in the data set. Given: "%s"',
                static::COLUMN_ASSIGNED_PRODUCT_TYPE,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!in_array($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE], static::ASSIGNABLE_PRODUCT_TYPES, true)) {
            throw new InvalidDataException(sprintf(
                '"%s" must have one of the following values: %s. Given: "%s"',
                static::COLUMN_ASSIGNED_PRODUCT_TYPE,
                implode(', ', static::ASSIGNABLE_PRODUCT_TYPES),
                $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE]
            ));
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductAbstract(DataSetInterface $dataSet): void
    {
        parent::importProductAbstract($dataSet);

        $dataSet[static::DATA_PRODUCT_ABSTRACT_TRANSFER]
            ->setPdpAttributes(json_encode($dataSet[CombinedAttributesExtractorStep::KEY_PDP_ATTRIBUTES]))
            ->setBrand($dataSet[static::COLUMN_BRAND_NAME]);

    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductUrls(DataSetInterface $dataSet): void
    {
        $urlsTransfer = [];

        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $abstractProductUrl = $localizedAttributes[static::COLUMN_URL];

            if ($abstractProductUrl === "") {
                $locales = array_flip($dataSet[static::KEY_LOCALES]);
                $localeCode = strtok($locales[$idLocale], '_');
                $sku = $dataSet[static::COLUMN_ABSTRACT_SKU] ?: $dataSet[static::COLUMN_CONCRETE_SKU];
                $abstractProductUrl = $this->utilTextService->generateSlug($localizedAttributes[static::COLUMN_NAME] . '-' . $sku);
                $abstractProductUrl = '/' . $localeCode . '/' . $abstractProductUrl;
            }

            $urlEntityTransfer = new SpyUrlEntityTransfer();

            $urlEntityTransfer
                ->setFkLocale($idLocale)
                ->setUrl($abstractProductUrl);

            $urlsTransfer[] = [
                static::COLUMN_ABSTRACT_SKU => $dataSet[static::COLUMN_ABSTRACT_SKU] ?: $dataSet[static::COLUMN_CONCRETE_SKU],
                static::KEY_PRODUCT_URL_TRASNFER => $urlEntityTransfer,
            ];
        }

        $dataSet[static::DATA_PRODUCT_URL_TRANSFER] = $urlsTransfer;
    }
}
