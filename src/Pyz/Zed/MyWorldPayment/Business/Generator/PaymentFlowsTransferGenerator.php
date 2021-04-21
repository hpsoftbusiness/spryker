<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator;

use ArrayObject;
use Generated\Shared\Transfer\FlowsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class PaymentFlowsTransferGenerator implements PaymentFlowsTransferGeneratorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    private $config;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\DirectPaymentTransferGeneratorInterface[]
     */
    private $directPaymentGenerators;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $config
     * @param \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\DirectPaymentTransferGeneratorInterface[] $directPaymentGenerators
     */
    public function __construct(MyWorldPaymentConfig $config, array $directPaymentGenerators)
    {
        $this->config = $config;
        $this->directPaymentGenerators = $directPaymentGenerators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FlowsTransfer
     */
    public function generate(QuoteTransfer $quoteTransfer): FlowsTransfer
    {
        $directPaymentOptionCollection = $this->buildDirectPaymentTransfersCollection($quoteTransfer);

        return (new FlowsTransfer())
            ->setType($this->config->getDefaultFlowsType())
            ->setMwsDirect($directPaymentOptionCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject
     */
    private function buildDirectPaymentTransfersCollection(QuoteTransfer $quoteTransfer): ArrayObject
    {
        $directPaymentOptionCollection = new ArrayObject();
        foreach ($this->directPaymentGenerators as $generator) {
            if (!$generator->isPaymentUsed($quoteTransfer)) {
                continue;
            }

            $directPaymentOptionCollection->append($generator->generateMwsDirectPaymentOptionTransfer($quoteTransfer));
        }

        return $directPaymentOptionCollection;
    }
}
