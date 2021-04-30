<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilText;

use Pyz\Service\UtilText\Model\Filter\CamelCaseToSnakeCase;
use Pyz\Service\UtilText\Model\Filter\CamelCaseToSnakeCaseInterface;
use Pyz\Service\UtilText\Model\Slug;
use Spryker\Service\UtilText\UtilTextServiceFactory as SprykerUtilTextServiceFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class UtilTextServiceFactory extends SprykerUtilTextServiceFactory
{
    /**
     * @return \Spryker\Service\UtilText\Model\SlugInterface
     */
    public function createTextSlug()
    {
        return new Slug();
    }

    /**
     * @return \Pyz\Service\UtilText\Model\Filter\CamelCaseToSnakeCaseInterface
     */
    public function createCamelCaseToSnakeCase(): CamelCaseToSnakeCaseInterface
    {
        return new CamelCaseToSnakeCase($this->createCamelCaseToSnakeCaseNameConverter());
    }

    /**
     * @return \Symfony\Component\Serializer\NameConverter\NameConverterInterface
     */
    public function createCamelCaseToSnakeCaseNameConverter(): NameConverterInterface
    {
        return new CamelCaseToSnakeCaseNameConverter();
    }
}
