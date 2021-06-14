<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Form\Constrain;

use Generated\Shared\Transfer\FileUploadTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\Constrain\Exception\UnexpectedFileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CsvFileConstrainValidator extends ConstraintValidator
{
    private const AVAILABLE_VALUE_TYPES = [
        FileUploadTransfer::class,
        UploadedFile::class,
    ];

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @throws \Pyz\Zed\ProductDataImport\Communication\Form\Constrain\Exception\UnexpectedFileType
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CsvFileConstrain) {
            throw new UnexpectedTypeException($constraint, CsvFileConstrain::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if ($constraint->getDefaultMimeType() !== $value->getClientOriginalExtension()) {
            return;
        }

        if (!($value instanceof FileUploadTransfer)) {
            throw new UnexpectedFileType(gettype($value), implode(', ', self::AVAILABLE_VALUE_TYPES));
        }

        if (!$this->isDelimiterValid($value->getRealPath(), $constraint->delimiter)) {
            $this->context->buildViolation($constraint->incorrectSeparatorMessage)
                ->setParameter('{{delimiter}}', $constraint->delimiter)
                ->addViolation();
        }
    }

    /**
     * @param string $filePath
     * @param string $delimiter
     *
     * @return bool
     */
    private function isDelimiterValid(string $filePath, string $delimiter): bool
    {
        $stream = fopen($filePath, 'r');
        $headerContent = fgetcsv($stream, 0, $delimiter);

        return count($headerContent) > 1;
    }
}
