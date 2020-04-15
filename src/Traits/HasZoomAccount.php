<?php
namespace Danielmlozano\Zoomel\Traits;

use Danielmlozano\Zoomel\ZoomUserToken;
use Danielmlozano\Zoomel\Zoom;
use Danielmlozano\Zoomel\ZoomMeeting;
use Danielmlozano\Zoomel\ZoomMeetingsList;
use Danielmlozano\Zoomel\ZoomUser;

trait HasZoomAccount {

    /**
     * Get a Danielmlozano\Zoomel\ZoomUserToken instance from a
     * given safe_id string, then saves the relationship to the user
     *
     * @param string $token_safe_id
     * @return \Danielmlozano\Zoomel\ZoomUserToken
    */
    public function attachZoomToken(String $token_safe_id){
        ZoomUserToken::where('user_id',$this->id)->delete();
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
        if($zoom_account['status_code']==200){
            return new ZoomUser($zoom_account['content']);
        }
        else{
            return $zoom_account['response']['message'];
        }
    }

    /**
     * Returns the Zoom user meetings list from the Zoom API
     * @param int $page
     * @param int $page_size
     * @return \Danielmlozano\Zoomel\ZoomMeetingsList
    */
    public function getMeetings(int $page = 1, $page_size = 30){
        $zoom = new Zoom($this);
        $meetings = $zoom->getZoomMeetings($page,$page_size);
        if($meetings['status_code']==200){
            return new ZoomMeetingsList($meetings['content']);
        }
        else{
            return $meetings['response']['message'];
        }
    }

    /**
     * Return a single Zoom Meeting
     * @param int $meeting_id
     * @return \Danielmlozano\Zoomel\ZoomMeeting
    */
    public function getMeeting(int $meeting_id){
        $zoom = new Zoom($this);
        $meetings = $zoom->getZoomMeeting($meeting_id);
        if($meetings['status_code']==200){
            return new ZoomMeeting($meetings['content']);
        }
        else{
            return $meetings['response']['message'];
        }
    }

    /**
     * Creates a Zoom meeting
     * @param array $meeting_data
     * @return \Danielmlozano\Zoomel\ZoomMeeting
     *
     */
    public function createMeeting(array $meeting_data){
        $zoom = new Zoom($this);
        $new_meeting = $zoom->createZoomMeeting($meeting_data);
        if($new_meeting['status_code']==201){
            return new ZoomMeeting($new_meeting['content']);
        }
        else{
            return $new_meeting['response']['message'];
        }
    }

    /**
     * Updates a Zoom meeting
     * @param int $meeting_id
     * @param array $meeting_data
     * @return string
     *
     */
    public function updateMeeting(int $meeting_id, array $meeting_data){
        $zoom = new Zoom($this);
        $new_meeting = $zoom->updateZoomMeeting($meeting_id,$meeting_data);
        if($new_meeting['status_code']==204){
            return 'updated';
        }
        else{
            return $new_meeting['response']['message'];
        }
    }

    /**
     * Deletes a Zoom Meeting
     * @param int $meeting_id
     * @return string
    */
    public function deleteMeeting(int $meeting_id){
        $zoom = new Zoom($this);
        $meetings = $zoom->deleteZoomMeeting($meeting_id);
        if($meetings['status_code']==204){
            return 'deleted';
        }
        else{
            return $meetings['response']['message'];
        }
    }

}
