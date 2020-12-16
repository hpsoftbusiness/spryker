<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentNavigationWidget\Twig;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NavigationNodeStorageTransfer;
use Generated\Shared\Transfer\NavigationStorageTransfer;
use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction as SprykerShopContentNavigationTwigFunction;
use Twig\Environment;

class ContentNavigationTwigFunction extends SprykerShopContentNavigationTwigFunction
{
    protected const DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY = 'customer_product_lists_key';
    protected const DATA_KEY_PRODUCT_ASSIGNMENT = 'product_assignment';
    protected const DATA_KEY_MODIFIED_AT = 'modified_at';
    protected const DATA_KEY_NODE_VISIBILITY = 'node_visibility';

    /**
     * @var \Pyz\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig
     */
    protected $contentNavigationWidgetConfig;

    /**
     * @var \Pyz\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient
     * @param \Pyz\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig $contentNavigationWidgetConfig
     * @param \Pyz\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient,
        ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient,
        ContentNavigationWidgetConfig $contentNavigationWidgetConfig,
        CustomerClientInterface $customerClient,
        SessionClientInterface $sessionClient,
        UtilEncodingServiceInterface $utilEncodingService
    ) {
        parent::__construct($twig, $localeName, $contentNavigationClient, $navigationStorageClient, $contentNavigationWidgetConfig);

        $this->customerClient = $customerClient;
        $this->sessionClient = $sessionClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer
     */
    protected function optimizeNavigationStorageNodes(NavigationStorageTransfer $navigationStorageTransfer): NavigationStorageTransfer
    {
        $now = new DateTime();

        $optimizedNavigationNodeStorageTransfers = new ArrayObject();

        $isNavigationNodeCategoryDriven = $this->getIsNavigationNodeCategoryDriven($navigationStorageTransfer);
        $customerTransfer = $this->customerClient->getCustomer();

        if ($isNavigationNodeCategoryDriven && $customerTransfer) {
            // check if customer has another black/white lists combination
            $this->validateCustomerSessionNavigationNodeData($customerTransfer);
        }

        foreach ($navigationStorageTransfer->getNodes() as $navigationNodeStorageTransfer) {
            if ($isNavigationNodeCategoryDriven
                && !$this->getIsCategoryVisible($navigationNodeStorageTransfer, $customerTransfer)
            ) {
                continue;
            }

            $isValidFrom = $navigationNodeStorageTransfer->getValidFrom() === null || new DateTime($navigationNodeStorageTransfer->getValidFrom()) <= $now;
            $isValidTo = $navigationNodeStorageTransfer->getValidTo() === null || new DateTime($navigationNodeStorageTransfer->getValidTo()) >= $now;
            $isActiveAndValid = $navigationNodeStorageTransfer->getIsActive() && $isValidFrom && $isValidTo;
            $hasChildren = $navigationNodeStorageTransfer->getChildren()->count() > 0;

            $navigationNodeStorageTransfer->setIsValidFrom($isValidFrom);
            $navigationNodeStorageTransfer->setIsValidTo($isValidTo);
            $navigationNodeStorageTransfer->setIsActiveAndValid($isActiveAndValid);
            $navigationNodeStorageTransfer->setHasChildren($hasChildren);

            $optimizedNavigationNodeStorageTransfers->append($navigationNodeStorageTransfer);
        }
        $navigationStorageTransfer->setNodes($optimizedNavigationNodeStorageTransfers);

        return $navigationStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     *
     * @return bool
     */
    protected function getIsNavigationNodeCategoryDriven(NavigationStorageTransfer $navigationStorageTransfer): bool
    {
        if (!in_array(
            $navigationStorageTransfer->getKey(),
            $this->contentNavigationWidgetConfig->getCategoryNavigationKeys()
        )) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function validateCustomerSessionNavigationNodeData(CustomerTransfer $customerTransfer): void
    {
        $navigationNodeProductAssignmentData = $this->findNavigationNodeSessionData();
        $customerProductListsUniqueKey = $this->getCustomerProductListsUniqueKey(
            $customerTransfer->getCustomerProductListCollection()
        );

        if (!isset($navigationNodeProductAssignmentData[static::DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY])
            || $navigationNodeProductAssignmentData[static::DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY] !== $customerProductListsUniqueKey
        ) {
            // clear all the cached data for customer if the product list were changed
            $this->setNavigationNodeSessionData([
                static::DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY => $customerProductListsUniqueKey,
            ]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeStorageTransfer $navigationNodeStorageTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function getIsCategoryVisible(
        NavigationNodeStorageTransfer $navigationNodeStorageTransfer,
        ?CustomerTransfer $customerTransfer = null
    ): bool {
        if (!$customerTransfer) {
            return $this->checkProductAssignmentForGuest($navigationNodeStorageTransfer);
        }

        $navigationNodeVisibilityData = $this->findNavigationNodeSessionData();
        $navigationNodeProductAssignmentData = $navigationNodeVisibilityData[static::DATA_KEY_PRODUCT_ASSIGNMENT] ?? [];
        $navigationNodeDataKey = $this->getNavigationNodeDataKey($navigationNodeStorageTransfer);

        if (isset($navigationNodeProductAssignmentData[$navigationNodeDataKey])) {
            $navigationNodeSessionData = $navigationNodeProductAssignmentData[$navigationNodeDataKey];

            if ($navigationNodeSessionData[static::DATA_KEY_MODIFIED_AT] === $navigationNodeStorageTransfer->getStorageModifiedAt()) {
                return $navigationNodeSessionData[static::DATA_KEY_NODE_VISIBILITY];
            }
        }

        $isNavigationNodeVisibleForCustomer = $this->checkProductAssignmentsForCustomer(
            $navigationNodeStorageTransfer,
            $customerTransfer
        );

        $navigationNodeProductAssignmentData[$navigationNodeDataKey] = [
            static::DATA_KEY_MODIFIED_AT => $navigationNodeStorageTransfer->getStorageModifiedAt(),
            static::DATA_KEY_NODE_VISIBILITY => $isNavigationNodeVisibleForCustomer,
        ];
        $navigationNodeVisibilityData[static::DATA_KEY_PRODUCT_ASSIGNMENT] = $navigationNodeProductAssignmentData;
        $this->setNavigationNodeSessionData($navigationNodeVisibilityData);

        return $isNavigationNodeVisibleForCustomer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeStorageTransfer $navigationNodeStorageTransfer
     *
     * @return bool
     */
    protected function checkProductAssignmentForGuest(
        NavigationNodeStorageTransfer $navigationNodeStorageTransfer
    ): bool {
        if (count($navigationNodeStorageTransfer->getProductAssignments())) {
            // category has at least one product assigned
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeStorageTransfer $navigationNodeStorageTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function checkProductAssignmentsForCustomer(
        NavigationNodeStorageTransfer $navigationNodeStorageTransfer,
        CustomerTransfer $customerTransfer
    ): bool {
        $productAssignments = $navigationNodeStorageTransfer->getProductAssignments();
        $customerProductLists = $customerTransfer->getCustomerProductListCollection();

        if (!count($customerProductLists->getBlacklistIds()) && !count($customerProductLists->getWhitelistIds())) {
            // customer has no product lists assigned
            if (count($productAssignments)) {
                // category has at least one product assigned
                return true;
            }

            return false;
        }

        if (!count($productAssignments)) {
            // category has no products, remove it
            return false;
        }

        foreach ($productAssignments as $productAssignment) {
            $isProductVisibleForCustomer = $this->getIsProductVisibleForCustomer(
                $customerProductLists,
                $productAssignment
            );

            if ($isProductVisibleForCustomer) {
                // at least one product is visible in category
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerProductListCollectionTransfer $customerProductLists
     * @param mixed[] $productAssignment
     *
     * @return bool
     */
    protected function getIsProductVisibleForCustomer(
        CustomerProductListCollectionTransfer $customerProductLists,
        array $productAssignment
    ): bool {
        if ($customerProductLists->getBlacklistIds()) {
            $isRestricted = $this->getIsProductRestricted(
                $customerProductLists->getBlacklistIds(),
                $productAssignment['id_blacklists']
            );

            if ($isRestricted) {
                // product is restricted, skipping
                return false;
            }
        }

        if ($customerProductLists->getWhitelistIds()) {
            $isAllowed = $this->getIsProductAllowed(
                $customerProductLists->getWhitelistIds(),
                $productAssignment['id_whitelists']
            );

            if ($isAllowed) {
                // product is allowed, proceeding
                return true;
            }

            // product is restricted since not present in any whitelist
            return false;
        }

        // product is visible since customer has no whitelist groups
        return true;
    }

    /**
     * @param int[] $customerBlacklistIds
     * @param int[] $productBlacklistIds
     *
     * @return bool
     */
    protected function getIsProductRestricted(array $customerBlacklistIds, array $productBlacklistIds): bool
    {
        if (array_intersect($customerBlacklistIds, $productBlacklistIds)) {
            return true;
        }

        return false;
    }

    /**
     * @param int[] $customerWhitelistIds
     * @param int[] $productWhitelistIds
     *
     * @return bool
     */
    protected function getIsProductAllowed(array $customerWhitelistIds, array $productWhitelistIds): bool
    {
        if (array_intersect($customerWhitelistIds, $productWhitelistIds)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeStorageTransfer $navigationNodeStorageTransfer
     *
     * @return string
     */
    protected function getNavigationNodeDataKey(NavigationNodeStorageTransfer $navigationNodeStorageTransfer): string
    {
        return sprintf(
            '%s_%s',
            $navigationNodeStorageTransfer->getNodeType(),
            $navigationNodeStorageTransfer->getId()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerProductListCollectionTransfer $customerProductListCollectionTransfer
     *
     * @return string
     */
    protected function getCustomerProductListsUniqueKey(
        CustomerProductListCollectionTransfer $customerProductListCollectionTransfer
    ): string {
        return md5($this->utilEncodingService->encodeJson($customerProductListCollectionTransfer->toArray()));
    }

    /**
     * @return mixed[]|null
     */
    protected function findNavigationNodeSessionData(): ?array
    {
        return $this->sessionClient->get(
            $this->contentNavigationWidgetConfig->getNavigationNodeVisibilitySessionKey()
        );
    }

    /**
     * @param mixed[] $dataToSet
     *
     * @return mixed[]|null
     */
    protected function setNavigationNodeSessionData(array $dataToSet): ?array
    {
        return $this->sessionClient->set(
            $this->contentNavigationWidgetConfig->getNavigationNodeVisibilitySessionKey(),
            $dataToSet
        );
    }
}
