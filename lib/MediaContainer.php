<?php

namespace Izisaurio\Instagram;

use LiteRequest\Request;

/**
 * Upload a media file to a instagram container for future publishing
 * Resumable uploads not available yet
 * 
 * @package Izisaurio\Instagram
 */
class MediaContainer
{
    /**
     * Instagram endpoint host
     * 
     * @access protected
     * @var string
     */
    protected $host = 'graph.instagram.com';

    /**
     * Instagram Api version
     * 
     * @access protected
     * @var string
     */
    protected $apiVersion = 'v23.0';

    /**
     * Instagram user id
     * 
     * @access private
     * @var string
     */
    private $userId;

    /**
     * Instagram user access token
     * 
     * @access private
     * @var string
     */
    private $accessToken;

    /**
     * Container data
     * 
     * @access public
     * @var array
     */
    public $data;

    /**
     * Sets the the required values
     * 
     * @access public
     * @param string $userId Instagram user id
     * @param string $accessToken Valid access token identifier
     */
    public function __construct($userId, $accessToken)
    {
        $this->userId = $userId;
        $this->accessToken = $accessToken;
    }

    /**
     * Uploads a container and receives a container array with its id
     * Check https://developers.facebook.com/docs/instagram-platform/content-publishing to learn about the available params
     * 
     * @access public
     * @param array $params Media container params
     * @return array
     */
    public function container($params)
    {
        $endpoint = "https://{$this->host}/{$this->apiVersion}/{$this->userId}/media";

        $request = Request::post($endpoint, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $request->headers([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->accessToken}",
        ]);

        $request->postbody($params);
        $response = $request->exec();
        $this->data = $response->json();
        return $this->data;
    }

    /**
     * Gets the container status
     * Returns an array with two keys "status_code" (EXPIRED, ERROR, FINISHED, IN_PROGRESS, PUBLISHED) and "id"
     * 
     * @access public
     * @param $containerId Container id, if none it tries the sotred one
     * @return array
     */
    public function getStatus($containerId = null) {
        if (!isset($containerId) && (!$this->data || !isset($this->data['id']))) {
            throw new InstagramApiException('No container created or created with an error');
        }
        $containerId = isset($containerId) ? $containerId : $this->data['id'];
        $endpoint = "https://{$this->host}/{$this->apiVersion}/{$containerId}";
        $params = [
            'fields' => 'status_code',
        ];
        $url = $endpoint . '?' . http_build_query($params);

        $request = Request::get($url, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $request->headers([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->accessToken}",
        ]);

        $response = $request->exec();
        $json = $response->json();
        return $json;
    }

    /**
     * Publish a container
     * 
     * @access public
     * @param $containerId Container id, if none it tries the sotred one
     * @return array
     */
    public function publish($containerId) {
        if (!isset($containerId) && (!$this->data || !isset($this->data['id']))) {
            throw new InstagramApiException('No container created or created with an error');
        }
        $containerId = isset($containerId) ? $containerId : $this->data['id'];
        $endpoint = "https://{$this->host}/{$this->apiVersion}/{$this->userId}/media_publish";

        $request = Request::post($endpoint, [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $request->headers([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->accessToken}",
        ]);

        $request->postbody([
            'creation_id' => $containerId
        ]);

        $response = $request->exec();
        return $response->json();
    }
}
