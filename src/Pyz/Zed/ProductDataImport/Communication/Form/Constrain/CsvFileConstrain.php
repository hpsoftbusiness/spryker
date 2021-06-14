<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Form\Constrain;

use Symfony\Component\Validator\Constraint;

class CsvFileConstrain extends Constraint
{
    private const REQUIRED_MIME_TYPE = 'csv';

    /**
     * @var string $messageIncorrectSeparator
     */
    public $incorrectSeparatorMessage = 'Delimiter isn\'t correct please change it to {{delimiter}}';

    /**
     * @var string $separator
     */
    public $delimiter = ',';

    /**
     * @return string
     */
    public function getDefaultMimeType(): string
    {
        return self::REQUIRED_MIME_TYPE;
    }
}
