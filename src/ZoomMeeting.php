<?php
namespace Danielmlozano\Zoomel;
use Danielmlozano\Zoomel\ZoomObject;

class ZoomMeeting extends ZoomObject
{
    /**
     * The meeting UUID hash
     * @var string
     */
    public $uuid;

    /**
     * The meeting unique ID
     * @var int
     */
    public $id;

    /**
     * ID of the user who is set as host of the meeting
     * @var string
     */
    public $host_id;

    /**
     * Meeting topic
     * @var string
     */
    public $topic;

    /**
     * Meeting types:
     * 1. Instant meeting
     * 2. Scheduled meeting
     * 3. Recurring meeting
     * 4. Recurrng meeting with a fixed time
     * @var int
     */
    public $type;

    /**
     * Meeting status
     * @var string
     */
    public $status;

    /**
     * Meeting start tim in GMT/UTC
     * @var string
     */
    public $start_time;

    /**
     * The meeting duration
     * @var int
     */
    public $duration;

    /**
     * Timezone to format the meeting start time
     * @var string
     */
    public $timezone;

    /**
     * Agenda
     * @var string
     */
    public $agenda;

    /**
     * Time of creation
     * @var string
     */
    public $created_at;

    /**
     * The start url of a meeting is a URL using which a host or an alternative host can start the meeting
     * This URL SHOULD ONLY BE USED BY THE HOST OF THE MEETING AND SHOULD NOT BE SHARED, AS ANYONE WITH THIS URL WILL BE
     * ABLE TO JOIN AS THE MEETING HOST
     * @var string
     */
    public $start_url;

    /**
     * URL for participants to join the meeting. This URL should only be shared with users that you
     * would kike to invite for the meeting
     * @var string
     */
    public $join_url;

    /**
     * Meeting password
     * @var string
     */
    public $password;

    /**
     * H.323/SIP room system password
     * @var string
     */
    public $h323_password;

    /**
     * Encrypted password for third party endpoints
     * @var string
     */
    public $encrypted_password;

    /**
     * The meeting UUID hash
     * @var array
     */
    public $settings;

    /**
     * Create a new ZoomMeeting instance.
     *
     * @param array|null $meeting_data
     * @return void
     *
     */
    public function __construct(array $meeting_data = []){
        $this->fromArray($meeting_data);
    }

}
