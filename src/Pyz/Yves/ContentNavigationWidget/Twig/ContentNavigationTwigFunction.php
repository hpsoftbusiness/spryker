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
use Pyz\Yves\ContentNavigationWidget\Reader\CategoryReaderInterface;
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
     * @var \Pyz\Yves\ContentNavigationWidget\Reader\CategoryReaderInterface $categoryReader
     */
    protected $categoryReader;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient
     * @param \Pyz\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig $contentNavigationWidgetConfig
     * @param \Pyz\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Yves\ContentNavigationWidget\Reader\CategoryReaderInterface $categoryReader
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient,
        ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient,
        ContentNavigationWidgetConfig $contentNavigationWidgetConfig,
        CustomerClientInterface $customerClient,
        SessionClientInterface $sessionClient,
        UtilEncodingServiceInterface $utilEncodingService,
        CategoryReaderInterface $categoryReader
    ) {
        parent::__construct($twig, $localeName, $contentNavigationClient, $navigationStorageClient, $contentNavigationWidgetConfig);

        $this->customerClient = $customerClient;
        $this->sessionClient = $sessionClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->categoryReader = $categoryReader;
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

        if ($isNavigationNodeCategoryDriven) {
            // check if customer received another black/white lists combination
            $this->validateSessionNavigationNodeData($customerTransfer);
        }

        foreach ($navigationStorageTransfer->getNodes() as $navigationNodeStorageTransfer) {
            if ($isNavigationNodeCategoryDriven
                && !$this->getIsCategoryVisible($navigationNodeStorageTransfer)
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
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return void
     */
    protected function validateSessionNavigationNodeData(?CustomerTransfer $customerTransfer): void
    {
        $navigationNodeProductAssignmentData = $this->findNavigationNodeSessionData();
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();

        if ($customerTransfer && $customerTransfer->getCustomerProductListCollection()) {
            $customerProductListCollectionTransfer = $customerTransfer->getCustomerProductListCollection();
        }

        $navigationNodeSessionUniqueKey = $this->getNavigationNodeSessionUniqueKey($customerProductListCollectionTransfer);

        if (!isset($navigationNodeProductAssignmentData[static::DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY])
            || $navigationNodeProductAssignmentData[static::DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY] !== $navigationNodeSessionUniqueKey
        ) {
            // clear all the cached data for customer if the product list were changed
            $this->setNavigationNodeSessionData([
                static::DATA_KEY_CUSTOMER_PRODUCT_LISTS_KEY => $navigationNodeSessionUniqueKey,
            ]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeStorageTransfer $navigationNodeStorageTransfer
     *
     * @return bool
     */
    protected function getIsCategoryVisible(NavigationNodeStorageTransfer $navigationNodeStorageTransfer): bool
    {
        $navigationNodeVisibilityData = $this->findNavigationNodeSessionData();
        $navigationNodeProductAssignmentData = $navigationNodeVisibilityData[static::DATA_KEY_PRODUCT_ASSIGNMENT] ?? [];
        $navigationNodeDataKey = $this->getNavigationNodeDataKey($navigationNodeStorageTransfer);

        if (isset($navigationNodeProductAssignmentData[$navigationNodeDataKey])) {
            $navigationNodeSessionData = $navigationNodeProductAssignmentData[$navigationNodeDataKey];

            if ($navigationNodeSessionData[static::DATA_KEY_MODIFIED_AT] === $navigationNodeStorageTransfer->getStorageModifiedAt()) {
                return $navigationNodeSessionData[static::DATA_KEY_NODE_VISIBILITY];
            }
        }

        $isNavigationNodeVisible = $this->categoryReader->getIsCatalogVisible(
            $navigationNodeStorageTransfer->getIdCategory()
        );

        $navigationNodeProductAssignmentData[$navigationNodeDataKey] = [
            static::DATA_KEY_MODIFIED_AT => $navigationNodeStorageTransfer->getStorageModifiedAt(),
            static::DATA_KEY_NODE_VISIBILITY => $isNavigationNodeVisible,
        ];
        $navigationNodeVisibilityData[static::DATA_KEY_PRODUCT_ASSIGNMENT] = $navigationNodeProductAssignmentData;
        $this->setNavigationNodeSessionData($navigationNodeVisibilityData);

        return $isNavigationNodeVisible;
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
    protected function getNavigationNodeSessionUniqueKey(
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
