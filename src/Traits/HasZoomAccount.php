<?php
namespace Danielmlozano\Zoomel\Traits;

use Danielmlozano\Zoomel\ZoomUserToken;
use Danielmlozano\Zoomel\Zoom;

trait HasZoomAccount {

    public function attachZoomToken(String $token){
        $zoom_token = ZoomUserToken::findSafeId($token);
        $this->zoomToken()->save($zoom_token);
        return $zoom_token;
    }

    public function hasZoomAccount(){
        return !is_null($this->zoomToken);
    }

    public function getZoomAccount(){
        $zoom = new Zoom($this);
        $zoom_account = $zoom->getZoomUser();
        if($zoom_account['statusCode']==200){
            return $zoom_account['content'];
        }
    }

    public function getMeetings(){
        $zoom = new Zoom($this);
        $meetings = $zoom->getZoomMeetings();
        if($meetings['statusCode']==200){
            return $meetings['content'];
        }
    }

}
