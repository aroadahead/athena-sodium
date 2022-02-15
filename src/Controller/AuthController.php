<?php

namespace AthenaSodium\Controller;

use Application\Form\StandardConfigForm;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\PhpEnvironment\Response;
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
        $loginForm = new StandardConfigForm('login');
        return $this -> newViewModel(['form' => $loginForm]);
    }

    public function logoutAction(): ViewModel
    {
        return $this -> newViewModel();
    }
}