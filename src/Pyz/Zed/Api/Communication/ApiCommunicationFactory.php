<?php

namespace Pyz\Zed\Api\Communication;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Pyz\Shared\Api\ApiConstants;
use Pyz\Zed\Api\Business\Exception\UnsupportedTransformerTypeException;
use Pyz\Zed\Api\Communication\Transformer\SimpleTransformer;
use Spryker\Zed\Api\Communication\ApiCommunicationFactory as SprykerApiCommunicationFactory;
use Spryker\Zed\Api\Communication\Transformer\Transformer;
use Spryker\Zed\Api\Communication\Transformer\TransformerInterface;

/**
 * @method \Pyz\Zed\Api\ApiConfig getConfig()
 */
class ApiCommunicationFactory extends SprykerApiCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Spryker\Zed\Api\Communication\Transformer\TransformerInterface
     */
    public function createTransformerByType(
        ApiRequestTransfer $apiRequestTransfer,
        ApiResponseTransfer $apiResponseTransfer
    ): TransformerInterface {
        switch ($apiResponseTransfer->getTransformerType()) {
            case ApiConstants::TRANSFORMER_SIMPLE:
                return new SimpleTransformer(
                    $this->createFormatter($apiRequestTransfer->getFormatType()),
                    $this->getConfig()
                );
            default:
                return new Transformer(
                    $this->createFormatter($apiRequestTransfer->getFormatType()),
                    $this->getConfig()
                );
        }
    }
}
