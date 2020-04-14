<?php
namespace Danielmlozano\Zoomel\Traits;

use Danielmlozano\Zoomel\ZoomUserToken;
use Danielmlozano\Zoomel\Zoom;

trait HasZoomAccount {

    /**
     * Get a Danielmlozano\Zoomel\ZoomUserToken instance from a
     * given safe_id string, then saves the relationship to the user
     *
     * @param string $token_safe_id
     * @return \Danielmlozano\Zoomel\ZoomUserToken
    */
    public function attachZoomToken(String $token_safe_id){
        $zoom_token = ZoomUserToken::findSafeId($token_safe_id);
        $this->zoomToken()->save($zoom_token);
        return $zoom_token;
    }

    /**
     * Determines if the user has a access token stored
     *
     * @return bool
    */
    public function hasZoomAccount(){
        return !is_null($this->zoomToken);
    }

    /**
     * Returns the Zoom User info from the Zoom API
     *
     * @return array
    */
    public function getZoomAccount(){
        $zoom = new Zoom($this);
        $zoom_account = $zoom->getZoomUser();
        if($zoom_account['statusCode']==200){
            return $zoom_account['content'];
        }
    }

    /**
     * Returns the Zoom user meetings list from the Zoom API
     *
     * @return array
    */
    public function getMeetings(){
        $zoom = new Zoom($this);
        $meetings = $zoom->getZoomMeetings();
        if($meetings['statusCode']==200){
            return $meetings['content'];
        }
    }

}
