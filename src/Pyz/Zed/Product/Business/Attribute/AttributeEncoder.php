<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Attribute;

use Spryker\Zed\Product\Business\Attribute\AttributeEncoder as SprykerAttributeEncoder;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;

class AttributeEncoder extends SprykerAttributeEncoder implements AttributeEncoderInterface
{
    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json)
    {
        $value = $this->utilEncodingService->decodeJson($json, true);

        if (!is_array($value)) {
            $value = [];
        }

        return $this->sanitizeBoolValues($value);
    }

    /**
     * TODO: refactor how we deal with boolean values: https://spryker.atlassian.net/browse/MYW-1275
     *
     * @param array $values
     *
     * @return array
     */
    private function sanitizeBoolValues(array $values): array
    {
        foreach ($values as $key => $value) {
            if ($value === '1' || $value === '0') {
                $values[$key] = (bool)$value;
            }
        }

        return $values;
    }
}
