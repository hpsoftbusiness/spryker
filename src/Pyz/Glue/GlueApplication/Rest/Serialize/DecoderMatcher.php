<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication\Rest\Serialize;

use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcher as SprykerDecoderMatcher;

class DecoderMatcher extends SprykerDecoderMatcher
{
    /**
     * @var string
     */
    public const WECLAPP_WEBHOOK_FORMAT = 'weclapp_webhook';
}
