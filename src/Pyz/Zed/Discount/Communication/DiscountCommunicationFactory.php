<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Communication;

use Pyz\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory as SprykerDiscountCommunicationFactory;
use Spryker\Zed\Glossary\Business\GlossaryFacadeInterface;

class DiscountCommunicationFactory extends SprykerDiscountCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface
     */
    public function getGlossaryFacade(): GlossaryFacadeInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_GLOSSARY);
    }
}
