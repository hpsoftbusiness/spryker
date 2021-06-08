<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CategoryDataImport\Business\Model;

use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributesQuery;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery;
use Orm\Zed\Navigation\Persistence\SpyNavigationQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryDataImport\Business\Model\CategoryWriterStep as SprykerCategoryWriterStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Navigation\Dependency\NavigationEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategoryWriterStep extends SprykerCategoryWriterStep
{
    public const KEY_CATEGORY_IMAGE_NAME = 'category_image_name';
    protected const KEY_NODE_ORDER = 'node_order';
    protected const KEY_URL_ID = 'url_id';
    protected const KEY_LOCALE_ID = 'locale_id';

    protected const NAVIGATION_MODE_MOBILE = 'MAIN_NAVIGATION';
    protected const NAVIGATION_MODE_DESKTOP = 'MAIN_NAVIGATION_DESKTOP';

    protected const NODE_KEY_SUFFIX = '_NODE_';

    protected const DEFAULT_ROOT_CATEGORY = 'shop';
    protected const DEFAULT_NODE_TYPE = 'category';

    /**
     * @var array
     */
    protected $idNavigationNodeBuffer = [];

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    protected function findOrCreateNode(SpyCategory $categoryEntity, DataSetInterface $dataSet)
    {
        $categoryNodeEntity = SpyCategoryNodeQuery::create()
            ->filterByCategory($categoryEntity)
            ->findOneOrCreate();

        if (!empty($dataSet[static::KEY_PARENT_CATEGORY_KEY])) {
            $idParentCategoryNode = $this->categoryReader->getIdCategoryNodeByCategoryKey($dataSet[static::KEY_PARENT_CATEGORY_KEY]);
            $categoryNodeEntity->setFkParentCategoryNode($idParentCategoryNode);
        }

        $categoryNodeEntity->fromArray($dataSet->getArrayCopy());

        if ($categoryNodeEntity->isNew() || $categoryNodeEntity->isModified()) {
            $categoryNodeEntity->save();
        }

        $this->addToClosureTable($categoryNodeEntity);
        $this->addPublishEvents(CategoryEvents::CATEGORY_NODE_PUBLISH, $categoryNodeEntity->getIdCategoryNode());

        foreach ($categoryEntity->getAttributes() as $categoryAttributesEntity) {
            $idLocale = $categoryAttributesEntity->getFkLocale();
            $languageIdentifier = $this->getLanguageIdentifier($idLocale, $dataSet);
            $urlPathParts = [$languageIdentifier];
            if (!$categoryNodeEntity->getIsRoot()) {
                $parentUrl = $this->categoryReader->getParentUrl(
                    $dataSet[static::KEY_PARENT_CATEGORY_KEY],
                    $idLocale
                );

                $urlPathParts = explode('/', ltrim($parentUrl, '/'));
                $urlPathParts[] = $categoryAttributesEntity->getName();
            }

            if ($categoryNodeEntity->getIsRoot()) {
                $this->addPublishEvents(CategoryEvents::CATEGORY_TREE_PUBLISH, $categoryNodeEntity->getIdCategoryNode());
            }

            $convertCallback = function ($value) {
                return mb_strtolower(str_replace(' ', '-', $value));
            };
            $urlPathParts = array_map($convertCallback, $urlPathParts);
            $url = '/' . implode('/', $urlPathParts);

            $urlEntity = SpyUrlQuery::create()
                ->filterByFkLocale($idLocale)
                ->filterByFkResourceCategorynode($categoryNodeEntity->getIdCategoryNode())
                ->findOneOrCreate();

            $urlEntity
                ->setUrl($url);

            if ($urlEntity->isNew() || $urlEntity->isModified()) {
                $urlEntity->save();
                $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
            }

            $dataSet[static::KEY_URL_ID] = $urlEntity->getIdUrl();
            $dataSet[static::KEY_LOCALE_ID] = $idLocale;

            if ($dataSet[static::KEY_CATEGORY_KEY] !== static::DEFAULT_ROOT_CATEGORY) {
                $this->createNavigationNode($dataSet, static::NAVIGATION_MODE_DESKTOP);
                $this->createNavigationNode($dataSet, static::NAVIGATION_MODE_MOBILE);
            }
        }

        return $categoryNodeEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $navigationMode
     *
     * @return void
     */
    protected function createNavigationNode(DataSetInterface $dataSet, string $navigationMode)
    {
        if (!isset($this->idNavigationNodeBuffer[$navigationMode])) {
            $this->idNavigationNodeBuffer[$navigationMode] = $this->resolveIdNavigation($navigationMode);
        }

        $navigationNodeKey = strtolower($navigationMode . static::NODE_KEY_SUFFIX . $dataSet[static::KEY_NODE_ORDER]);
        $navigationNodeEntity = SpyNavigationNodeQuery::create()
            ->filterByFkNavigation($this->idNavigationNodeBuffer[$navigationMode])
            ->filterByNodeKey($navigationNodeKey)
            ->findOneOrCreate();

        $navigationNodeEntity
            ->setPosition((int)$dataSet[static::KEY_NODE_ORDER])
            ->setIsActive(true)
            ->setNodeType(static::DEFAULT_NODE_TYPE);

        $navigationNodeLocalizedAttributesEntity = SpyNavigationNodeLocalizedAttributesQuery::create()
            ->filterByFkNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->filterByFkLocale($dataSet[static::KEY_LOCALE_ID])
            ->findOneOrCreate();

        $categoryKey = static::KEY_NAME . '.' . array_search($dataSet[static::KEY_LOCALE_ID], $dataSet['locales']);
        $navigationNodeLocalizedTitle = $dataSet['localizedAttributes'][$dataSet[static::KEY_LOCALE_ID]][static::KEY_NAME] ?? $dataSet[$categoryKey];
        $navigationNodeLocalizedAttributesEntity->setTitle($navigationNodeLocalizedTitle);
        $navigationNodeLocalizedAttributesEntity->setFkUrl($dataSet[static::KEY_URL_ID]);
        $navigationNodeEntity->addSpyNavigationNodeLocalizedAttributes($navigationNodeLocalizedAttributesEntity);
        $navigationNodeEntity->save();

        $this->addPublishEvents(NavigationEvents::NAVIGATION_KEY_PUBLISH, $navigationNodeEntity->getFkNavigation());
    }

    /**
     * @param string $navigationKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveIdNavigation($navigationKey)
    {
        $navigationEntity = SpyNavigationQuery::create()
            ->findOneByKey($navigationKey);

        if (!$navigationEntity) {
            throw new EntityNotFoundException(sprintf('Navigation by key "%s" not found.', $navigationKey));
        }

        return $navigationEntity->getIdNavigation();
    }
}
