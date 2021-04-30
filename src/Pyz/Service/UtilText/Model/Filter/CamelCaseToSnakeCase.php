<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText\Model\Filter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class CamelCaseToSnakeCase implements CamelCaseToSnakeCaseInterface
{
    /**
     * @var \Symfony\Component\Serializer\NameConverter\NameConverterInterface
     */
    private $nameConverter;

    /**
     * @param \Symfony\Component\Serializer\NameConverter\NameConverterInterface $nameConverter
     */
    public function __construct(NameConverterInterface $nameConverter)
    {
        $this->nameConverter = $nameConverter;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function filter(string $string): string
    {
        return $this->nameConverter->normalize($string);
    }
}
