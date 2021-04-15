<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\MyWorldPaymentApi\Business\Model;

use DateTime;
use DateTimeInterface;
use Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer;

class AuthorizationHeaderGenerator
{
    private const AUTHORIZATION_TYPE = 'HMACv1';
    private const AUTHORIZATION_HEADER = 'x-mws-apiversion:1.0';

    /**
     * @param \Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer $authorizationHeaderRequest
     *
     * @return string
     */
    public function getAuthorizationHeader(AuthorizationHeaderRequestTransfer $authorizationHeaderRequest): string
    {
        $authorizationHeaderRequest->requireApiKeyId()->requireSecretApiKey()->requireHttpMethod();

        $apiKeyId = $authorizationHeaderRequest->getApiKeyId();
        $signature = $this->generateSignature(
            $authorizationHeaderRequest->getSecretApiKey(),
            $authorizationHeaderRequest->getHttpMethod(),
            $authorizationHeaderRequest->getBody(),
            $authorizationHeaderRequest->getContentType(),
            $authorizationHeaderRequest->getUri()
        );

        return sprintf("MWS %s:%s:%s", self::AUTHORIZATION_TYPE, $apiKeyId, $signature);
    }

    /**
     * @param string $secretKey
     * @param string $method
     * @param string|null $body
     * @param string|null $contentType
     * @param string|null $uri
     *
     * @return string
     */
    private function generateSignature(
        string $secretKey,
        string $method,
        ?string $body,
        ?string $contentType,
        ?string $uri
    ): string {
        $stringToSign = $this->generateStringToSign($method, $body, $contentType, $uri);

        return base64_encode(hash_hmac('sha256', utf8_encode($stringToSign), $secretKey, true));
    }

    /**
     * @param string $method
     * @param string|null $body
     * @param string|null $contentType
     * @param string|null $uri
     *
     * @return string
     */
    private function generateStringToSign(string $method, ?string $body, ?string $contentType, ?string $uri): string
    {
        $bodyHash = $this->generateBodyHash($body);
        $stringDate = (new DateTime())->format(DateTimeInterface::RFC7231);

        $uri = parse_url($uri, PHP_URL_PATH);

        return sprintf(
            "%s\n%s\n%s\n%s\n%s\n%s\n",
            $method,
            $bodyHash,
            $contentType,
            $stringDate,
            self::AUTHORIZATION_HEADER,
            $uri
        );
    }

    /**
     * @param string|null $body
     *
     * @return string
     */
    private function generateBodyHash(?string $body): string
    {
        return $body ? base64_encode(hash('sha256', utf8_encode($body), true)) : '';
    }
}
