<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group AbstractProductAbstractMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class AbstractProductAbstractMapExpanderPluginTest extends Unit
{
    /**
     * @var \PyzTest\Zed\ProductPageSearch\ProductPageSearchCommunicationTester
     */
    protected $tester;

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $parameterKey
     * @param mixed $value
     *
     * @return void
     */
    protected function assertMappedSearchResultDataValue(
        PageMapTransfer $pageMapTransfer,
        string $parameterKey,
        $value
    ): void {
        $mappedValue = $this->findMappedSearchResultDataValue($pageMapTransfer, $parameterKey);

        self::assertNotNull($mappedValue);
        self::assertEquals($mappedValue, $value);
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $parameterKey
     *
     * @return string|null
     */
    protected function findMappedSearchResultDataValue(PageMapTransfer $pageMapTransfer, string $parameterKey)
    {
        foreach ($pageMapTransfer->getSearchResultData() as $searchResultDataMapTransfer) {
            if ($searchResultDataMapTransfer->getName() === $parameterKey) {
                return $searchResultDataMapTransfer->getValue();
            }
        }

        return null;
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface
     */
    protected function createPageMapBuilder(): PageMapBuilderInterface
    {
        return new PageMapBuilder();
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function getProductDataFromAttributes(array $productAttributes): array
    {
        $productData = [];
        foreach ($productAttributes as $key => $value) {
            $productData[ProductPageSearchTransfer::ATTRIBUTES][$key][] = $value;
        }

        return $productData;
    }

    /**
     * @param array $productTestedData
     *
     * @return array
     */
    protected function getProductData(array $productTestedData): array
    {
        $productData = [];
        foreach ($productTestedData as $key => $value) {
            $productData[$key] = $value;
        }

        return $productData;
    }
}
