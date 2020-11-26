<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Sso\Security\Guard;

use Pyz\Client\Sso\SsoClientInterface;
use Pyz\Yves\Sso\SsoConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
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
     * @var \Pyz\Yves\Sso\SsoConfig
     */
    protected $ssoConfig;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     * @param \Pyz\Client\Sso\SsoClientInterface $ssoClient
     * @param \Pyz\Yves\Sso\SsoConfig $ssoConfig
     * @param string $locale
     */
    public function __construct(
        HttpUtils $httpUtils,
        SsoClientInterface $ssoClient,
        SsoConfig $ssoConfig,
        string $locale
    ) {
        $this->httpUtils = $httpUtils;
        $this->ssoClient = $ssoClient;
        $this->ssoConfig = $ssoConfig;
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
        return $this->httpUtils->createRedirectResponse($request, $this->buildAuthorizeUrl($this->ssoConfig, $this->locale));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return $this->httpUtils->checkRequestPath($request, '/' . $this->ssoConfig->getLoginCheckPath());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer|mixed
     */
    public function getCredentials(Request $request)
    {
        $exception = new AuthenticationException();

        if (strpos($request->getPathInfo(), $this->ssoConfig->getLoginCheckPath()) !== false) {
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
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials);
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
     * @return void
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * @return void
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param \Pyz\Yves\Sso\SsoConfig $ssoConfig
     * @param string $locale
     *
     * @return string
     */
    protected function buildAuthorizeUrl(SsoConfig $ssoConfig, string $locale): string
    {
        $httpQuery = http_build_query(
            [
                'response_type' => $ssoConfig->getResponseType(),
                'client_id' => $ssoConfig->getClientId(),
                'redirect_uri' => $ssoConfig->getRedirectUrl(),
                'scope' => $ssoConfig->getScope(),
                'lang' => str_replace('_', '-', $locale),
            ]
        );

        return sprintf('%s?%s', $ssoConfig->getAuthorizeUrl(), $httpQuery);
    }
}
