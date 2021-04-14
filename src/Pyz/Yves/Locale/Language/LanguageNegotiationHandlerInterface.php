<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale\Language;

interface LanguageNegotiationHandlerInterface
{
    /**
     * @param string $acceptLanguage
     *
     * @return string|null
     */
    public function getLanguageIsoCode(string $acceptLanguage): ?string;
}
