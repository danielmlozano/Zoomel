<?php
namespace Danielmlozano\Zoomel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Danielmlozano\Zoomel\Zoom;
use Danielmlozano\Zoomel\Zoomel;
use Danielmlozano\Zoomel\ZoomUserToken;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller{

    public function oauthAccessRequest(){
        return redirect(
            Zoomel::getAuthLink()
        );
    }

    public function oauthAccessResponse(Request $request){
        if($request->filled('code')){
            $access_code = $request->input('code');
            $zoom = new Zoom();
            $auth_request = $zoom->getAuthCode($access_code);
            if($auth_request['statusCode']==200){

                $request_content = $auth_request['content'];

                $token_prefix = substr($request_content['access_token'],0,64);
                $zoom_user_token = ZoomUserToken::create([
                    'safe_id' => uniqid("$token_prefix."),
                    'auth_token' => $request_content['access_token'],
                    'refresh_token' => $request_content['refresh_token'],
                    'scope' => $request_content['scope'],

                ]);
                return redirect(config('zoomel.oauth_redirect_uri')."?status=".$auth_request['statusCode']."&token_id=".$zoom_user_token->safe_id);
            }
        }
    }

}