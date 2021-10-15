<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication\Serialize\Decoder;

use Pyz\Glue\WeclappRestApi\WeclappRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface;

class WeclappWebhookDecoder implements DecoderInterface
{
    /**
     * @param string $data
     *
     * @return array
     */
    public function decode($data): array
    {
        $arrayData = json_decode($data, true);
        if (!$arrayData) {
            return [];
        }

        return [
        RestResourceInterface::RESOURCE_DATA => [
            RestResourceInterface::RESOURCE_TYPE => WeclappRestApiConfig::RESOURCE_WEBHOOK,
            RestResourceInterface::RESOURCE_ATTRIBUTES => $arrayData,
        ]];
    }
}
