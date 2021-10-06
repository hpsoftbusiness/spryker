<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment\Controller;

use Exception;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method \Pyz\Yves\MyWorldPayment\MyWorldPaymentFactory getFactory()
 */
class MyWorldPaymentController extends AbstractController
{
    protected const LAST_REQUEST_TIME_SMS_CODE_SESSION_KEY = 'sms_code_timeout';
    protected const TIMEOUT_IN_SECONDS = 30;
    protected const MESSAGE_WAIT = 'checkout.step.summary.modal.please_wait_30_seconds';
    protected const MESSAGE_ERROR = 'checkout.step.summary.modal.error_when_resending_by_sms';
    protected const MESSAGE_ERROR_SESSION_EXPIRED = 'checkout.step.summary.modal.error_session_expired';
    protected const SERVICE_TRANSLATOR = 'translator';

    protected const HTTP_STATUS_SUCCESS = 200;
    protected const HTTP_STATUS_TOO_MANY_REQUESTS = 429;
    protected const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    protected const HTTP_STATUS_BAD_REQUEST = 400;
    protected const HTTP_STATUS_UNAUTHORIZED = 401;

    protected const ERROR_CODE_SESSION_EXPIRED = 3;

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendSmsCodeToCustomerAction(): JsonResponse
    {
        if (!$this->isWaitTimeFinished()) {
            return $this->getJsonResponse(static::HTTP_STATUS_TOO_MANY_REQUESTS, $this->getTranslator()->trans(static::MESSAGE_WAIT));
        }

        $quote = $this
            ->getFactory()
            ->getCartClient()
            ->getQuote();

        try {
            $myWorldResponse = $this
                ->getFactory()
                ->getMyWorldPaymentClient()
                ->sendSmsCodeToCustomerByQuote($quote);

            if ($myWorldResponse->getIsSuccess()) {
                $this->updateLastRequestTime();
                $status = static::HTTP_STATUS_SUCCESS;
            } else {
                switch ($myWorldResponse->getError()->getErrorCode()) {
                    case static::ERROR_CODE_SESSION_EXPIRED:
                        $status = static::HTTP_STATUS_UNAUTHORIZED;
                        $message = $this->getTranslator()->trans(static::MESSAGE_ERROR_SESSION_EXPIRED);
                        break;
                    default:
                        $status = static::HTTP_STATUS_INTERNAL_SERVER_ERROR;
                        $message = $this->getTranslator()->trans(static::MESSAGE_ERROR);
                }
            }
        } catch (Exception $e) {
            $status = static::HTTP_STATUS_INTERNAL_SERVER_ERROR;
            $message = $this->getTranslator()->trans(static::MESSAGE_ERROR);
        }

        return $this->getJsonResponse($status, $message ?? null);
    }

    /**
     * @param int $status
     * @param string|null $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getJsonResponse(int $status, ?string $message): JsonResponse
    {
        return $this->jsonResponse([
            'message' => $message,
        ], $status);
    }

    /**
     * @return void
     */
    protected function updateLastRequestTime(): void
    {
        $this
            ->getFactory()
            ->getSessionClient()
            ->set(static::LAST_REQUEST_TIME_SMS_CODE_SESSION_KEY, time());
    }

    /**
     * @return bool
     */
    protected function isWaitTimeFinished(): bool
    {
        $lastTime = $this
            ->getFactory()
            ->getSessionClient()
            ->get(static::LAST_REQUEST_TIME_SMS_CODE_SESSION_KEY);

        return time() - $lastTime > static::TIMEOUT_IN_SECONDS;
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface
     */
    protected function getTranslator(): TranslatorInterface
    {
        return $this->getFactory()->getTranslatorService();
    }
}
