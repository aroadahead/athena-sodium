<?php

namespace AthenaSodium\Controller;

use AthenaBridge\Facebook\Facebook;
use AthenaCore\Mvc\Application\Config\Exception\NodeNotFound;
use AthenaSodium\Model\User;
use AthenaSodium\Model\UserProfile;
use AthenaSodium\Session\Container\FacebookContainer;
use Facebook\Exceptions\FacebookSDKException;
use Laminas\Http\Response;
use function array_values;
use function implode;

class FacebookController extends SodiumModuleController
{
    /**
     * @throws NodeNotFound
     * @throws FacebookSDKException
     */
    public function loginAction(): Response
    {
        $constructorArgs = $this -> configFacade() -> getApisConfig('facebook');
        $redirectUri = $constructorArgs['redirect_uri'];
        $fbPermissions = array_values($constructorArgs['permissions']);
        unset($constructorArgs['redirect_uri'], $constructorArgs['permissions'], $constructorArgs['graph_fields']);
        $fb = new Facebook($constructorArgs);
        $helper = $fb -> getRedirectLoginHelper();
        $url = $helper -> getLoginUrl($redirectUri, $fbPermissions);
        return $this -> verifyAndRedirectUrl($url);
    }

    /**
     * @throws FacebookSDKException
     * @throws NodeNotFound
     */
    public function authAction(): Response
    {
        $constructorArgs = $this -> configFacade() -> getApisConfig('facebook');
        $fields = $constructorArgs['graph_fields'];
        unset($constructorArgs['redirect_uri'], $constructorArgs['permissions'], $constructorArgs['graph_fields']);
        $fb = new Facebook($constructorArgs);
        $session = new FacebookContainer();
        $helper = $fb -> getRedirectLoginHelper();
        try {
            $accessToken = $helper -> getAccessToken();
        } catch (FacebookSDKException $e) {
            $session -> setExpirationSeconds(0);
            return $this -> login();
        }
        $session -> setAccessToken($accessToken);
        $fb -> setDefaultAccessToken($accessToken);
        $fields = implode(',', $fields);
        $graph = $fb -> get("/me?fields=$fields", $accessToken);
        $graphUser = $graph -> getGraphUser();
        $profile = UserProfile ::byFbId($graphUser['id']);
        $user = User ::byId($profile -> userid, false);
        return $this -> redirect() -> toRoute('directLogin', ['hash' => $user -> getHash()]);
    }
}