<?php
namespace Danielmlozano\Zoomel;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Log;

class Zoom
{
    private $zoom_client_id, $zoom_client_secret, $zoom_auth_code, $redirect_uri, $user, $client;

    private $zoom_oauth_endpoint = "https://zoom.us/oauth/token";
    private $zoom_api_base_url = "https://api.zoom.us/v2/users/me";
    public $access_link;

    public function __construct($user = null){
        $this->loadConfig();
        $this->makeOAuthLink();
        if($user){
            $this->user = $user;
        }
        $this->client = new Guzzle();
    }

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

    private function loadConfig(){
        $this->zoom_client_id = config('zoomel.zoom_client_id');
        $this->zoom_client_secret = config('zoomel.zoom_client_secret');
        $this->zoom_auth_code = base64_encode("$this->zoom_client_id:$this->zoom_client_secret");
        $this->redirect_uri = route('zoomel.oauth');
    }

    private function makeOAuthLink(){
        $this->access_link = "https://zoom.us/oauth/authorize?response_type=code&client_id=$this->zoom_client_id&redirect_uri=$this->redirect_uri";
    }

    public function getAuthCode(String $zoom_request_code){
        $request_options = [
            'headers' => [
                'Authorization' => "Basic $this->zoom_auth_code",
            ],
            'query' => [
                'grant_type' => 'authorization_code',
                'code' => $zoom_request_code,
                'redirect_uri' => route('zoomel.oauth'),
                'client_id' => $this->zoom_client_id,
            ],
        ];
        return $this->request("POST",$this->zoom_oauth_endpoint,$request_options);
    }

    public function getZoomUser(){
        $endpoint = $this->zoom_api_base_url;
        return $this->request("GET",$endpoint);
    }

    public function getZoomMeetings(){
        $endpoint = $this->zoom_api_base_url."/meetings";
        return $this->request("GET",$endpoint);
    }

}
