<?php

namespace App\Service;

use \Google_Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class GoogleDriveClientManager
{
    private $requestStack;
    private $uploader;

    private $client;
    private $authUrl;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function setClient(Google_Client $client) {
        $this->client = $client;
    }

    public function getClient() {
        return $this->client;
    }

    public function getAuthUrl() {
        return $this->authUrl;
    }

    //Get Google client
    public function requestClient(string $clientId, string $clientSecret, string $redirectUri, $scopes)
    {
        $request = $this->requestStack->getCurrentRequest();

        $client = new Google_Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->setScopes($scopes);

        $session = new Session();
        $session->start();

        $code = $request->query->get('code');
        $access_token = $session->get('access_token');

        if (isset($code) || isset($access_token)) {
            if (isset($access_token)) {
                $client->setAccessToken($access_token);
            } else {
                $client->authenticate($code);
                $session->set('access_token', $client->getAccessToken());
            }

            $this->client = $client;
            return true;
        } else {
            $authUrl = $client->createAuthUrl();

            $this->authUrl = $authUrl;
            return false;
        }
    }
}