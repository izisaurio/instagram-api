<?php

namespace Izisaurio\Instagram;

use LiteRequest\Request;

/**
 * Validates the authorization code and returns an access token
 * 
 * @package Izisaurio\Instagram
 */
class AccessToken
{
    /**
     * Instagram app id
     * 
     * @access private
     * @var string
     */
    private $clientId;

    /**
     * Instagram app secret
     * 
     * @access private
     * @var string
     */
    private $clientSecret;

    /**
     * Redirect uri
     * 
     * @access private
     * @var string
     */
    private $redirect;

    /**
     * Token info in an array
     * The important type is the key "access_token"
     * 
     * @access public
     * @var array
     */
    public $token;

    /**
     * Sets the the required values
     * 
     * @access public
     * @param string $clientId Instagram app id
     * @param string $clientSecret Instagram app secret
     * @param string $redirect Redirect uri, maker sure it ends with a backslash
     */
    public function __construct($clientId, $clientSecret, $redirect)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirect = $redirect;
    }

    /**
     * Gets the access token with an authorization code
     * 
     * @access public
     * @param string $code Authorization code
     * @return array
     */
    public function getToken($code)
    {
        $endpoint = 'https://api.instagram.com/oauth/access_token';
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirect,
            'grant_type' => 'authorization_code',
            'code' => $code,
        ];

        $request = Request::post($endpoint, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $request->postfields($params);
        $response = $request->exec();
        $this->token = $response->json();
        return $this->token;
    }

    /**
     * Gets a long lived access token from a short token
     * 
     * @access public
     * @param string $accessToken Valid access token identifier
     * @return array
     */
    public function getLongLivedToken($accessToken)
    {
        $endpoint = 'https://graph.instagram.com/access_token';
        $params = [
            'client_secret' => $this->clientSecret,
            'grant_type' => 'ig_exchange_token',
            'access_token' => $accessToken,
        ];
        $url = $endpoint . '?' . http_build_query($params);
        
        $request = Request::get($url, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $response = $request->exec();
        $this->token = $response->json();
        return $this->token;
    }

    /**
     * Refreshes an unexpired long lived token
     * 
     * @access public
     * @param string $longLivedToken Valid long lived access token identifier
     * @return array
     */
    public function refreshLongLivedToken($longLivedToken)
    {
        $endpoint = 'https://api.instagram.com/oauth/refresh_access_token';
        $params = [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $longLivedToken,
        ];
        $url = $endpoint . '?' . http_build_query($params);
        
        $request = Request::get($url, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $response = $request->exec();
        $this->token = $response->json();
        return $this->token;
    }
}
