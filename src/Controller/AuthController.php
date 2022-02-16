<?php

namespace AthenaSodium\Controller;

use Application\Form\StandardConfigForm;
use AthenaSodium\Adapter\AuthAdapter;
use AthenaSodium\Adapter\Result\AuthenticatedUserResult;
use AthenaSodium\Entity\User;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;

class AuthController extends SodiumModuleController
{
    public function loginAction(): ViewModel|Response
    {
        $redirectUrl = $this -> redirectUrl();
        $authService = new AuthenticationService();
        if ($authService -> hasIdentity()) {
            return $this -> sodiumService() -> redirectToDashboard();
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
                    if ($this -> container -> get('conf')
                        -> facade() -> getApplicationConfig('auth.enforce_pin_validation')) {
                        $user -> setPinValidated(false);
                    }
                    return $this -> toDashboard();
                }
                $isLoginError = true;
            }
        }
        return $this -> newViewModel(['form' => $loginForm, 'isLoginError' => $isLoginError]);
    }

    public function logoutAction(): ViewModel
    {
        return $this -> newViewModel();
    }
}