<?php

namespace Izisaurio\Instagram;

/**
 * Gets the authorization url
 * Requires a meta developer app with instagram product
 * This only works for Instagram login only (No facebook link)
 * 
 * @package Izisaurio\Instagram
 */
class Authorization {
    /**
     * Instagram app id
     * 
     * @access private
     * @var string
     */
    private $clientId;

    /**
     * Redirect uri
     * 
     * @access private
     * @var string
     */
    private $redirect;

    /**
     * Requested scopes
     * 
     * @access private
     * @var array
     */
    private $scopes;

    /**
     * Sets the the required values
     * 
     * @access public
     * @param string $clientId Instagram app id
     * @param string $redirect Redirect uri
     * @param array $scopes Requested scopes for the user
     */
    public function __construct($clientId, $redirect, array $scopes) {
        $this->clientId = $clientId;
        $this->redirect = $redirect;
        $this->scopes = $scopes;
    }

    /**
     * Get the authorization url
     * This must be set as the href of a link <a>
     * 
     * @access public
     * @return string
     */
    public function getAuthorizationUrl() {
        $endpoint = 'https://www.instagram.com/oauth/authorize';
        $params = [
            'client_id' => $this->clientId,
            'scope' => join(',', $this->scopes),
            'response_type' => 'code',
            'enable_fb_login' => 0,
            'force_authentication' => 1
        ];
        return $endpoint . '?' . http_build_query($params) . '&redirect_uri=' . $this->redirect;
    }
}