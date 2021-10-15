<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication;

use Pyz\Glue\GlueApplication\Rest\Request\HttpRequestValidator;
use Pyz\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractor;
use Pyz\Glue\GlueApplication\Rest\Serialize\DecoderMatcher;
use Pyz\Glue\GlueApplication\Serialize\Decoder\WeclappWebhookDecoder;
use Spryker\Glue\GlueApplication\GlueApplicationFactory as SprykerGlueApplicationFactory;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface;

class GlueApplicationFactory extends SprykerGlueApplicationFactory
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    public function createRestHttpRequestValidator(): HttpRequestValidatorInterface
    {
        return new HttpRequestValidator($this->getValidateRequestPlugins(), $this->createRestResourceRouteLoader(), $this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    public function createRestRequestMetaDataExtractor(): RequestMetaDataExtractorInterface
    {
        return new RequestMetaDataExtractor(
            $this->createRestVersionResolver(),
            $this->createRestContentTypeResolver(),
            $this->createLanguageNegotiation()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface
     */
    public function createRestDecoderMatcher(): DecoderMatcherInterface
    {
        return new DecoderMatcher([
            DecoderMatcher::DEFAULT_FORMAT => $this->createJsonDecoder(),
            DecoderMatcher::WECLAPP_WEBHOOK_FORMAT => $this->createBase64JsonDataDecoder(),
        ]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface
     */
    protected function createBase64JsonDataDecoder(): DecoderInterface
    {
        return new WeclappWebhookDecoder();
    }
}
