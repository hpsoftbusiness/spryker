<?php

namespace Pyz\Zed\Api\Business\Model;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiMetaTransfer;
use Generated\Shared\Transfer\ApiOptionsTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Pyz\Shared\Api\ApiConstants;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Dispatcher as SprykerDispatcher;

class Dispatcher extends SprykerDispatcher
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    protected function dispatchToResource(ApiRequestTransfer $apiRequestTransfer)
    {
        $resource = $apiRequestTransfer->getResource();
        $method = $apiRequestTransfer->getResourceAction();
        $id = $apiRequestTransfer->getResourceId();
        $params = $apiRequestTransfer->getResourceParameters();
        $params[ApiConstants::API_REQUEST_TRANSFER_PARAM_KEY] = $apiRequestTransfer;

        $apiResponseTransfer = new ApiResponseTransfer();

        try {
            $errors = $this->getValidationErrors($apiRequestTransfer);

            if ($errors) {
                $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_VALIDATION_ERRORS);
                $apiResponseTransfer->setMessage('Validation errors.');
                $apiResponseTransfer->setValidationErrors(new ArrayObject($errors));
            } else {
                $apiPluginCallResponseTransfer = $this->callApiPlugin($resource, $method, $id, $params);
                $apiResponseTransfer->setType(get_class($apiPluginCallResponseTransfer));
                $apiResponseTransfer->setOptions($apiPluginCallResponseTransfer->getOptions());
                $apiResponseTransfer->setTransformerType($apiPluginCallResponseTransfer->getTransformerType());

                if ($apiPluginCallResponseTransfer instanceof ApiOptionsTransfer) {
                    return $apiResponseTransfer;
                }

                $data = (array)$apiPluginCallResponseTransfer->getData();
                $apiResponseTransfer->setData($data);

                if ($apiPluginCallResponseTransfer instanceof ApiCollectionTransfer) {
                    $apiResponseTransfer->setPagination($apiPluginCallResponseTransfer->getPagination());
                    if (!$apiResponseTransfer->getMeta()) {
                        $apiResponseTransfer->setMeta(new ApiMetaTransfer());
                    }
                } elseif ($apiPluginCallResponseTransfer instanceof ApiItemTransfer) {
                    if (!$apiResponseTransfer->getMeta()) {
                        $apiResponseTransfer->setMeta(new ApiMetaTransfer());
                    }
                    $apiResponseTransfer->getMeta()->setResourceId($apiPluginCallResponseTransfer->getId());
                }
            }
        } catch (Exception $e) {
            $apiResponseTransfer->setCode($this->resolveStatusCode($e->getCode()));
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        } catch (Throwable $e) {
            $apiResponseTransfer->setCode($this->resolveStatusCode($e->getCode()));
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        }

        return $apiResponseTransfer;
    }
}
