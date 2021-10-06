<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Reader;

interface ProductReaderInterface
{
    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findRegularProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findBenefitVoucherProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findShoppingPointProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findEliteClubProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findOneSenseProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findLyconetProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findFeaturedProducts(array $requestParameters): array;

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findOnlyEliteClubDealProducts(array $requestParameters): array;
}
