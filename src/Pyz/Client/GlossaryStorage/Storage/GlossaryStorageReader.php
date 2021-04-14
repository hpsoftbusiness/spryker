<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\GlossaryStorage\Storage;

use Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface;
use Spryker\Client\GlossaryStorage\Storage\GlossaryStorageReader as SprykerGlossaryStorageReader;
use Spryker\Client\Store\StoreClientInterface;

class GlossaryStorageReader extends SprykerGlossaryStorageReader
{
    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private $storeClient;

    /**
     * @param \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(
        GlossaryStorageToStorageClientInterface $storageClient,
        GlossaryStorageToSynchronizationServiceInterface $synchronizationService,
        GlossaryStorageToUtilEncodingServiceInterface $utilEncodingService,
        StoreClientInterface $storeClient
    ) {
        parent::__construct($storageClient, $synchronizationService, $utilEncodingService);

        $this->storeClient = $storeClient;
    }

    /**
     * Override to return default locale translation if translation missing for provided locale.
     *
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function getTranslation(string $keyName, string $localeName): string
    {
        $translation = parent::getTranslation($keyName, $localeName);
        $defaultLocaleName = $this->getDefaultLocaleName();
        if ($translation === $keyName && $localeName !== $defaultLocaleName) {
            return parent::getTranslation($keyName, $defaultLocaleName);
        }

        return $translation;
    }

    /**
     * Override to return default locale translations if translations missing for provided locale.
     *
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getTranslations(array $keyNames, string $localeName): array
    {
        $translations = parent::getTranslations($keyNames, $localeName);
        $defaultLocaleName = $this->getDefaultLocaleName();
        if ($defaultLocaleName === $localeName) {
            return $translations;
        }

        $notFoundTranslations = array_filter(
            $translations,
            static function (string $translation, string $keyName): bool {
                return $translation === $keyName;
            }
        );

        $defaultLocaleTranslations = parent::getTranslations(array_keys($notFoundTranslations), $defaultLocaleName);

        return array_merge($translations, $defaultLocaleTranslations);
    }

    /**
     * @return string
     */
    private function getDefaultLocaleName(): string
    {
        return current($this->storeClient->getCurrentStore()->getAvailableLocaleIsoCodes());
    }
}
