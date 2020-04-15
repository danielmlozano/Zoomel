<?php
namespace Danielmlozano\Zoomel;

use Carbon\Carbon;
use Danielmlozano\Zoomel\Exceptions\InvalidUser;
use Danielmlozano\Zoomel\Exceptions\UserHasNoToken;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;

class Zoom
{
    /**
     * The Zoom App Client ID
     *
     * @var string
     */
    private $zoom_client_id;

    /**
     * The Zoom App Client Secret Key
     *
     * @var string
     */
    private $zoom_client_secret;

    /**
     * The Zoom API Base64 Auth Code
     *
     * @var string
     */
    private $zoom_auth_code;

    /**
     * The App redirect URI after a Zoom Authorization process
     *
     * @var string
     */
    private $redirect_uri;

    /**
     * A User model instance
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    private $user;

    /**
     * A User model instance
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * The Zoom OAuth auth and access token request endpoint
     *
     * @var string
     */
    private $zoom_oauth_endpoint = "https://zoom.us/oauth/token";

    /**
     * The Zoom API base url for endpoints
     *
     * @var string
     */
    private $zoom_api_base_url = "https://api.zoom.us/v2";

    /**
     * The Zoom Authorization process access link
     *
     * @var string
     */
    public $access_link;

    /**
     * Determines if the Access token is refreshing
     *
     * @var bool
     */
    public $refreshing_access_token = false;


    /**
     * Create a new Zoom instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $user
     * @return void
     *
     * @throws \Danielmlozano\Zoomel\Exceptions\InvalidUser
     */
    public function __construct($user = null){
        $this->loadConfig();
        $this->makeOAuthLink();
        if($user){
            if(!$user->id){
                throw InvalidUser::invalidUser();
            }
            $this->user = $user;
        }
        $this->client = new Guzzle();
    }

    /**
     * Wrapps a Http Guzzle Request and add tokens as needed
     *
     * @param  string $method
     * @param  string $uri
     * @param  array|null $options
     * @return array
     *
     */
    public function request(String $method, String $uri, array $options = []){
        try{
            if($this->user && !$this->refreshing_access_token){
                if($this->user->zoomToken){
                    $options['headers']['Authorization'] = "Bearer ".$this->refreshToken(
                        $this->user->zoomToken
                    )->auth_token;
                }
                else{
                    throw new UserHasNoToken();
                }
            }
            $request = $this->client->request($method,$uri,$options);
            return [
                'status_code' => $request->getStatusCode(),
                'content' => json_decode($request->getBody(),true)
            ];
        }
        catch(ClientException $e){
            $response = $e->getResponse();
            $exception_data = [
                'status_code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(),true),
            ];
            Log::error("Zoom API Call error:", $exception_data);
            return $exception_data;
        }

    }

    /**
     * Requests to Zoom API OAuth a new token from a refresh token
     *
     * @param \Danielmlozano\Zoomel\ZoomUserToken
     * @return \Danielmlozano\Zoomel\ZoomUserToken
     */
    private function refreshToken(ZoomUserToken $token)
    {
        if($token->expiring){
            $this->refreshing_access_token = true;
            $request_options = $this->basicAuthHeaders([
                'query' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $token->refresh_token,
                ],
            ]);
            $refresh_token_request = $this->request("POST",$this->zoom_oauth_endpoint,$request_options);
            if($refresh_token_request['status_code']==200){
                $token->auth_token = $refresh_token_request['content']['access_token'];
                $token->refresh_token = $refresh_token_request['content']['refresh_token'];
                $token->expires_in = $refresh_token_request['content']['expires_in'];
                $token->save();
                $this->refreshing_access_token = false;
            }
        }
        return $token;
    }

    /**
     * Loads the Zoom configuration to the instance
     *
     * @return void
     */
    private function loadConfig(){
        $this->zoom_client_id = config('zoomel.zoom_client_id');
        $this->zoom_client_secret = config('zoomel.zoom_client_secret');
        $this->zoom_auth_code = base64_encode("$this->zoom_client_id:$this->zoom_client_secret");
        $this->redirect_uri = route('zoomel.oauth');
    }

    /**
     * Creates the Zoom auth link
     *
     * @return void
     */
    private function makeOAuthLink(){
        $this->access_link = "https://zoom.us/oauth/authorize?response_type=code&client_id=$this->zoom_client_id&redirect_uri=$this->redirect_uri";
    }

    /**
     * Get the Zoom Oauth access token
     * from a given OAuth authentication token
     *
     * @param  string $zoom_request_code
     * @return array
     *
     */
    public function getAuthCode(String $zoom_request_code){
        $request_options = $this->basicAuthHeaders([
            'query' => [
                'grant_type' => 'authorization_code',
                'code' => $zoom_request_code,
                'redirect_uri' => $this->redirect_uri,
                'client_id' => $this->zoom_client_id,
            ],
        ]);
        return $this->request("POST",$this->zoom_oauth_endpoint,$request_options);
    }

    /**
     * Get a Request options array with basic auth header
     *
     * @param  array $options
     * @return array
     *
     */
    private function basicAuthHeaders($options = []){
        return [
            'headers' => [
                'Authorization' => "Basic $this->zoom_auth_code",
            ],
        ]+$options;
    }

    /**
     * Add JSON Content type to a request headers
     *
     * @param  array $options
     * @return array
     *
     */
    private function jsonContentHeaders($options = []){
        return [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]+$options;
    }

    /**
     * Get the Zoom User info from the Zoom API
     *
     * @return array
     *
     */
    public function getZoomUser(){
        try{
            $endpoint = $this->zoom_api_base_url."/users/me";
            return $this->request("GET",$endpoint);
        }
        catch(\Exception $e){
            $this->handleException($e);
        }
    }

    /**
     * Get the Zoom User meetings list from the Zoom API
     *
     * @return array
     *
     */
    public function getZoomMeetings(){
        try{
            $endpoint = $this->zoom_api_base_url."/users/me/meetings";
            return $this->request("GET",$endpoint);
        }
        catch(\Exception $e){
            $this->handleException($e);
        }

    }

    /**
     * Gets a single Zoom Meeting
     * @param int $meeting_id
     * @return array
     *
     */
    public function getZoomMeeting(int $meeting_id){
        try{
            $endpoint = $this->zoom_api_base_url."/meetings/".$meeting_id;
            return $this->request("GET",$endpoint);
        }
        catch(\Exception $e){
            $this->handleException($e);
        }

    }

    /**
     * Creates a Zoom meeting
     *
     * @param array $meeting_data
     * @return array
     *
     */
    public function createZoomMeeting(array $meeting_data){
        try{
            $meeting_data = ['body'=>json_encode($meeting_data)];
            $options = $this->jsonContentHeaders($meeting_data);
            $endpoint = $this->zoom_api_base_url."/users/me/meetings";
            return $this->request("POST",$endpoint,$options);
        }
        catch(\Exception $e){
            $this->handleException($e);
        }
    }

    /**
     * Updates a Zoom meeting
     *
     * @param int $meeting_id
     * @param array $meeting_data
     * @return array
     *
     */
    public function updateZoomMeeting(int $meeting_id, array $meeting_data){
        try{
            $meeting_data = ['body'=>json_encode($meeting_data)];
            $options = $this->jsonContentHeaders($meeting_data);
            $endpoint = $this->zoom_api_base_url."/meetings/".$meeting_id;
            return $this->request("PATCH",$endpoint,$options);
        }
        catch(\Exception $e){
            $this->handleException($e);
        }
    }

    /**
     * Deletes a Zoom Meeting
     * @param int $meeting_id
     * @return array
     *
     */
    public function deleteZoomMeeting(int $meeting_id){
        try{
            $endpoint = $this->zoom_api_base_url."/meetings/".$meeting_id;
            return $this->request("DELETE",$endpoint);
        }
        catch(\Exception $e){
            $this->handleException($e);
        }

    }

    /**
     * Handles a exception
     *
     * @param \Exception $e
     * @return array
     *
     */
    private function handleException($e){
        return [
            'status_code' => $e->getCode(),
            'response' => [
                'message' => $e->getMessage()
            ],
        ];
    }



}
