<?php
namespace Danielmlozano\Zoomel;

use Danielmlozano\Zoomel\Exceptions\InvalidUser;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Log;

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
    private $zoom_api_base_url = "https://api.zoom.us/v2/users/me";

    /**
     * The Zoom Authorization process access link
     *
     * @var string
     */
    public $access_link;


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

        if($this->user){
            $options['headers']['Authorization'] = "Bearer ".$this->user->zoomToken->auth_token;
        }

        $request = $this->client->request($method,$uri,$options);
        return [
            'statusCode' => $request->getStatusCode(),
            'content' => json_decode($request->getBody(),true)
        ];
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
        $request_options = [
            'headers' => [
                'Authorization' => "Basic $this->zoom_auth_code",
            ],
            'query' => [
                'grant_type' => 'authorization_code',
                'code' => $zoom_request_code,
                'redirect_uri' => $this->redirect_uri,
                'client_id' => $this->zoom_client_id,
            ],
        ];
        return $this->request("POST",$this->zoom_oauth_endpoint,$request_options);
    }

    /**
     * Get the Zoom User info from the Zoom API
     *
     * @return array
     *
     */
    public function getZoomUser(){
        $endpoint = $this->zoom_api_base_url;
        return $this->request("GET",$endpoint);
    }

    /**
     * Get the Zoom User meetings list from the Zoom API
     *
     * @return array
     *
     */
    public function getZoomMeetings(){
        $endpoint = $this->zoom_api_base_url."/meetings";
        return $this->request("GET",$endpoint);
    }

}
