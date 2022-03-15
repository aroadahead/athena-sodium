<?php

namespace AthenaSodium\Controller;

use Application\Form\StandardConfigForm;
use AthenaBridge\Laminas\Authentication\AuthenticationService;
use AthenaCore\Mvc\Application\Config\Exception\NodeNotFound;
use AthenaSodium\Adapter\AuthAdapter;
use AthenaSodium\Adapter\Result\AuthenticatedUserResult;
use AthenaSodium\Entity\User;
use Laminas\Filter\Boolean;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;

class AuthController extends SodiumModuleController
{
    /**
     * @throws NodeNotFound
     */
    public function loginAction(): ViewModel|Response
    {
        $redirectUrl = $this -> redirectUrl();
        $authService = $this -> authService();
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
                    $user -> set2fa(true);
                    if ($this -> is2faActive()) {
                        $user -> set2fa(false);
                    }
                    $authService -> getStorage() -> write($user);
                    $filter = new Boolean();
                    $rememberMe = $filter -> filter($filteredData['rememberMe']);
                    if ($rememberMe) {
                        $this -> sessionManager() -> rememberMe($this -> rememberMeSeconds());
                    }
                    if (!empty($redirectUrl)) {
                        return $this -> verifyAndRedirectUrl($redirectUrl);
                    }
                    return $this -> toDashboard();
                }
                $isLoginError = true;
            }
        }
        return $this -> newViewModel(['form' => $loginForm, 'isLoginError' => $isLoginError]);
    }

    /**
     * @throws NodeNotFound
     */
    public function directLoginAction(): Response
    {
        $hash = $this -> params() -> fromRoute('hash');
        $user = \AthenaSodium\Model\User ::entityByHash($hash);
        $user -> setJustLoggedIn(true);
        $user -> set2fa(true);
        $facade = $this -> configFacade();
        if ($this -> is2faActive()) {
            $user -> set2fa(false);
        }
        $redirectUrl = $this -> redirectUrl();
        $rememberMeConfig = $facade -> getApplicationConfig('auth.auto_remember_me_direct_login');
        $authService = $this -> authService();
        if ($authService -> hasIdentity()) {
            return $this -> toDashboard();
        }
        $this -> authService() -> getStorage() -> write($user);
        if ($rememberMeConfig) {
            $this -> sessionManager() -> rememberMe($this -> rememberMeSeconds());
        }
        if (!empty($redirectUrl)) {
            return $this -> verifyAndRedirectUrl($redirectUrl);
        }
        return $this -> redirect() -> toUrl($redirectUrl);
    }

    /**
     * @throws NodeNotFound
     */
    public function logoutAction(): Response
    {
        $redirectToOnLogout = $this -> configFacade()
            -> getApplicationConfig('auth.redirect_route_on_logout');
        $authService = new AuthenticationService();
        if ($authService -> hasIdentity()) {
            $authService -> clearIdentity();
        }
        return $this -> redirect() -> toRoute($redirectToOnLogout);
    }

    /**
     * @throws NodeNotFound
     */
    private function is2faActive(): bool
    {
        return $this -> configFacade() -> getApplicationConfig('auth.enforce_2fa');
    }

    /**
     * @throws NodeNotFound
     */
    private function rememberMeSeconds(): int
    {
        return $this -> configFacade()
            -> getApplicationConfig('auth.rememberMeSeconds');
    }
}