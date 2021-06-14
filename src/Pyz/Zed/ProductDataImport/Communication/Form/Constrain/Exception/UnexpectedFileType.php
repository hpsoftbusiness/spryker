<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Form\Constrain\Exception;

use Exception;

class UnexpectedFileType extends Exception
{
    private const MESSAGE_FORMAT = 'Unexpected file type provided: %s. It should be %s';

    /**
     * @param string $value
     * @param string $expectedTypesNames
     */
    public function __construct(string $value, string $expectedTypesNames)
    {
        $message = sprintf(self::MESSAGE_FORMAT, $value, $expectedTypesNames);

        parent::__construct($message);
    }
}
