<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Security\Guard;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Pyz\Client\Sso\SsoClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class SsoAuthenticator extends AbstractGuardAuthenticator
{
    protected const BUFFER_TIME_TO_REFRESH_ACCESS_TOKEN = 60 * 5;

    /**
     * @var \Symfony\Component\Security\Http\HttpUtils
     */
    protected $httpUtils;

    /**
     * @var \Pyz\Client\Sso\SsoClientInterface
     */
    protected $ssoClient;

    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    protected $authenticationSuccessHandler;

    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    protected $authenticationFailureHandler;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     * @param \Pyz\Client\Sso\SsoClientInterface $ssoClient
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface $authenticationSuccessHandler
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface $authenticationFailureHandler
     * @param string $locale
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct(
        HttpUtils $httpUtils,
        SsoClientInterface $ssoClient,
        AuthenticationSuccessHandlerInterface $authenticationSuccessHandler,
        AuthenticationFailureHandlerInterface $authenticationFailureHandler,
        string $locale,
        CustomerClientInterface $customerClient
    ) {
        $this->httpUtils = $httpUtils;
        $this->ssoClient = $ssoClient;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->authenticationFailureHandler = $authenticationFailureHandler;
        $this->locale = $locale;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        $referer = explode('_', $this->locale);
        $referer = $referer[0];

        if ($request->headers->has('referer')) {
            $referer = $request->headers->get('referer');
        }

        return $this->httpUtils->createRedirectResponse($request, $this->ssoClient->getAuthorizeUrl($this->locale, $referer));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return $this->isRequestPathLoginCheck($request)
            || $this->isAccessTokenAboutToExpire();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer|mixed
     */
    public function getCredentials(Request $request)
    {
        $ssoAccessTokenTransfer = new SsoAccessTokenTransfer();

        if ($this->isRequestPathLoginCheck($request)) {
            $ssoAccessTokenTransfer = $this->getAccessToken($request);
        }

        if ($this->isAccessTokenAboutToExpire()) {
            $ssoAccessTokenTransfer = $this->refreshAccessToken();

            if (!$ssoAccessTokenTransfer->getIdToken()) {
                throw new AuthenticationException();
            }
        }

        return $ssoAccessTokenTransfer;
    }

    /**
     * @param mixed $credentials
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var \SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface $securityUser */
        $securityUser = $userProvider->loadUserByUsername($credentials);
        if ($securityUser->getCustomerTransfer()->getIsActive() !== true) {
            throw new AuthenticationException();
        }

        return $securityUser;
    }

    /**
     * @param mixed $credentials
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->authenticationFailureHandler->onAuthenticationFailure($request, $exception);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $providerKey
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->authenticationSuccessHandler->onAuthenticationSuccess($request, $token);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isRequestPathLoginCheck(Request $request): bool
    {
        return $this->httpUtils->checkRequestPath($request, '/' . $this->ssoClient->getLoginCheckPath());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function setReferer(Request $request): void
    {
        $referer = $request->query->get('state');

        if ($referer) {
            $referer = trim(base64_decode(strtr($referer, '._-', '+/=')));

            $request->headers->set('Referer', $referer);
        }
    }

    /**
     * @return bool
     */
    protected function isAccessTokenAboutToExpire(): bool
    {
        $customer = $this->customerClient->getCustomer();
        if ($customer === null) {
            return false;
        }
        $expireDuration = sprintf('PT%sS', $customer->getSsoAccessToken()->getExpiresIn());
        $expireInterval = new DateInterval($expireDuration);

        $createdAtString = $customer->getSsoAccessToken()->getCreatedAt();
        $createdAt = new DateTime($createdAtString);
        $expiredAt = $createdAt->add($expireInterval);

        $minDuration = sprintf('PT%sS', static::BUFFER_TIME_TO_REFRESH_ACCESS_TOKEN);
        $minInterval = new DateInterval($minDuration);
        $expiredAt->sub($minInterval);

        return $expiredAt < new DateTime();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    protected function getAccessToken(Request $request): SsoAccessTokenTransfer
    {
        $exception = new AuthenticationException();

        if ($request->query->get('error') !== null) {
            throw $exception;
        }

        $code = $request->query->get('code');
        if ($code === null) {
            throw $exception;
        }

        $ssoAccessTokenTransfer = $this->ssoClient->getAccessTokenByCode($code);
        if (!$ssoAccessTokenTransfer->getIdToken()) {
            throw $exception;
        }

        $this->setReferer($request);

        return $ssoAccessTokenTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    protected function refreshAccessToken(): SsoAccessTokenTransfer
    {
        $refreshToken = $this->customerClient
            ->getCustomer()
            ->getSsoAccessToken()
            ->getRefreshToken();

        return $this->ssoClient->getAccessTokenByRefreshToken($refreshToken);
    }
}
