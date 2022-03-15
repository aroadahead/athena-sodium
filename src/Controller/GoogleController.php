<?php

namespace AthenaSodium\Controller;

use AthenaBridge\Laminas\Config\Config;
use AthenaBridge\Laminas\Uri\Uri;
use AthenaCore\Mvc\Application\Config\Exception\NodeNotFound;
use AthenaSodium\Model\User;
use AthenaSodium\Model\UserProfile;
use Google_Client;
use http\Exception\InvalidArgumentException;
use Laminas\Http\Response;

class GoogleController extends SodiumModuleController
{
    /**
     * @throws NodeNotFound
     */
    public function loginAction(): Response
    {
        $gdataConfig = $this -> configFacade() -> getApisConfig('google.oauth');
        $this -> validateGoogleOauthCreds($gdataConfig);
        $redirectUri = $gdataConfig -> get('redirect_uri');
        $uri = new Uri($redirectUri);
        if (!$uri -> isValid()) {
            $this -> throwException(InvalidArgumentException::class, 'google auth redirect uri invalid');
        }

        $client = new Google_Client();
        $client -> setClientId($gdataConfig -> get('client_id'));
        $client -> setClientSecret($gdataConfig -> get('client_secret'));
        $client -> setRedirectUri($redirectUri);
        foreach ($gdataConfig -> get('scopes') as $scope) {
            $client -> addScope($scope);
        }
        return $this -> verifyAndRedirectUrl($client -> createAuthUrl());
    }

    /**
     * @throws NodeNotFound
     */
    public function authAction(): Response
    {
        $gdataConfig = $this -> configFacade() -> getApisConfig('google.oauth');
        $this -> validateGoogleOauthCreds($gdataConfig);
        $client = new Google_Client();
        $client -> setClientId($gdataConfig -> get('client_id'));
        $client -> setClientSecret($gdataConfig -> get('client_secret'));
        $client -> setRedirectUri($gdataConfig -> get('redirect_uri'));
        foreach ($gdataConfig -> get('scopes') as $scope) {
            $client -> addScope($scope);
        }
        $code = $this -> getRequest() -> getQuery('code');
        $token = $client -> fetchAccessTokenWithAuthCode($code);
        $client -> setAccessToken($token['access_token']);
        $oauth = new \Google_Service_Oauth2($client);
        $info = $oauth -> userinfo -> get();
        $profile = UserProfile ::byGoogleId($info -> id);
        $user = User ::byId($profile -> userid, false);
        return $this -> redirect() -> toRoute('directLogin', ['hash' => $user -> getHash()]);
    }

    private function validateGoogleOauthCreds(Config $gdataConfig): void
    {
        if (!$gdataConfig -> has('client_id')
            || empty(trim($gdataConfig -> get('client_id')))) {
            throw new InvalidArgumentException("Client ID Google Oauth missing.");
        }
        if (!$gdataConfig -> has('client_secret')
            || empty(trim($gdataConfig -> get('client_secret')))) {
            throw new InvalidArgumentException("Client Secret Google Oauth missing.");
        }
        if (!$gdataConfig -> has('redirect_uri')
            || empty(trim($gdataConfig -> get('redirect_uri')))) {
            throw new InvalidArgumentException("Redirect Uri Google Oauth missing.");
        }
        if (!$gdataConfig -> has('scopes')) {
            throw new InvalidArgumentException("Scopes Google Oauth missing.");
        }
        if (!is_array($gdataConfig -> get('scopes') -> toArray())
            || (count($gdataConfig -> get('scopes') -> toArray()) < 1)) {
            throw new InvalidArgumentException("Scopes Google Oauth not array or empty array.");
        }
    }
}