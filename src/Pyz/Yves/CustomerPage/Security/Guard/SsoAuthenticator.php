<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Security\Guard;

use Pyz\Client\Sso\SsoClientInterface;
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
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     * @param \Pyz\Client\Sso\SsoClientInterface $ssoClient
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface $authenticationSuccessHandler
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface $authenticationFailureHandler
     * @param string $locale
     */
    public function __construct(
        HttpUtils $httpUtils,
        SsoClientInterface $ssoClient,
        AuthenticationSuccessHandlerInterface $authenticationSuccessHandler,
        AuthenticationFailureHandlerInterface $authenticationFailureHandler,
        string $locale
    ) {
        $this->httpUtils = $httpUtils;
        $this->ssoClient = $ssoClient;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->authenticationFailureHandler = $authenticationFailureHandler;
        $this->locale = $locale;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        return $this->httpUtils->createRedirectResponse($request, $this->ssoClient->getAuthorizeUrl($this->locale));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return $this->httpUtils->checkRequestPath($request, '/' . $this->ssoClient->getLoginCheckPath());
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
        $exception = new AuthenticationException();

        if (strpos($request->getPathInfo(), $this->ssoClient->getLoginCheckPath()) !== false) {
            if ($request->query->get('error') !== null) {
                throw $exception;
            }

            $code = $request->query->get('code');
            if ($code === null) {
                throw $exception;
            }

            return $this->ssoClient->getAccessTokenByCode($code);
        }

        throw $exception;
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
}
