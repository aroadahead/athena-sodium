<?php

namespace AthenaSodium\Controller;

use Application\Form\StandardConfigForm;
use AthenaBridge\http\Exception\InvalidArgumentException;
use AthenaBridge\Laminas\Session\Container;
use AthenaSodium\Adapter\AuthAdapter;
use AthenaSodium\Adapter\Result\AuthenticatedUserResult;
use AthenaSodium\Entity\User;
use Laminas\Authentication\AuthenticationService;
use Laminas\Filter\Boolean;
use Laminas\Http\Response;
use Laminas\Uri\UriFactory;
use Laminas\View\Model\ViewModel;
use function is_null;

class AuthController extends SodiumModuleController
{
    public function loginAction(): ViewModel|Response
    {
        $redirectUrl = $this -> redirectUrl();
        $authService = new AuthenticationService();
        if ($authService -> hasIdentity()) {
            return $this -> toDashboard();
        }
        $isLoginError = false;
        $loginForm = new StandardConfigForm('login');
        if ($this -> getRequest() -> isPost()) {
            $data = $this -> params() -> fromPost();
            $loginForm -> setData($data);
            if ($loginForm -> isValid()) {
                $filteredData = $loginForm -> getFilteredDataAsArray();
                $adapter = new AuthAdapter($this -> container);
                $adapter -> setIdentity($filteredData['identity']);
                $adapter -> setCredential($filteredData['credential']);
                $result = $authService -> authenticate($adapter);
                if ($result instanceof AuthenticatedUserResult) {
                    $user = new User();
                    /* @var $identity \AthenaSodium\Model\User */
                    $identity = $result -> getIdentity();
                    $user -> exchangeArray($identity -> getDataSet() -> toArray([], ['password', 'pin']));
                    $user -> setPinValidated(true);
                    $facade = $this -> container -> get('conf') -> facade();
                    if ($facade -> getApplicationConfig('auth.enforce_pin_validation')) {
                        $user -> setPinValidated(false);
                    }
                    $authService -> getStorage() -> write($user);
                    $filter = new Boolean();
                    $rememberMe = $filter -> filter($filteredData['rememberMe']);
                    if ($rememberMe) {
                        Container ::getDefaultManager() -> rememberMe($facade
                            -> getApplicationConfig('auth.rememberMeSeconds'));
                    }
                    if (!empty($redirectUrl)) {
                        $uri = UriFactory ::factory($redirectUrl);
                        if (!$uri -> isValid() || !is_null($uri -> getHost())) {
                            throw new InvalidArgumentException("invalid redirect uri: $redirectUrl");
                        }
                        return $this -> redirect() -> toUrl($redirectUrl);
                    }
                    return $this -> toDashboard();
                }
                $isLoginError = true;
            }
        }
        return $this -> newViewModel(['form' => $loginForm, 'isLoginError' => $isLoginError]);
    }

    public function logoutAction(): Response
    {
        $redirectToOnLogout = $this -> container -> get('conf') -> facade()
            -> getApplicationConfig('auth.redirect_route_on_logout');
        $authService = new AuthenticationService();
        if ($authService -> hasIdentity()) {
            $authService -> clearIdentity();
        }
        return $this -> redirect() -> toRoute($redirectToOnLogout);
    }
}