<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductLabelDataImport\Business;

use Pyz\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductLabelDataImport\Business\ProductLabelDataImportBusinessFactory as SprykerProductLabelDataImportBusinessFactory;

class ProductLabelDataImportBusinessFactory extends SprykerProductLabelDataImportBusinessFactory
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
        return new LocalizedAttributesExtractorStep($defaultAttributes);
    }
}
