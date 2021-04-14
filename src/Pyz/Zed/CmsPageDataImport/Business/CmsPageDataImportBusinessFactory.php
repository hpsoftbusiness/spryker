<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsPageDataImport\Business;

use Pyz\Zed\CmsPageDataImport\Business\CmsPage\PlaceholderExtractorStep;
use Pyz\Zed\DataImport\Business\Model\DataImportStep\UrlAwareLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPageDataImportBusinessFactory as SprykerCmsPageDataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

class CmsPageDataImportBusinessFactory extends SprykerCmsPageDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createAddLocalesStep(): DataImportStepInterface
    {
        return new AddLocalesStep($this->getStore());
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep
     */
    protected function createLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new UrlAwareLocalizedAttributesExtractorStep($defaultAttributes, $this->getStore());
    }

    /**
     * @param array $defaultPlaceholder
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createPlaceholderExtractorStep(array $defaultPlaceholder = []): DataImportStepInterface
    {
        return new PlaceholderExtractorStep($defaultPlaceholder);
    }
}
