<?php
namespace Danielmlozano\Zoomel;
use Danielmlozano\Zoomel\ZoomObject;
use Danielmlozano\Zoomel\ZoomMeeting;

class ZoomMeetingsList extends ZoomObject
{
    /**
     * The number of pages retrned for the request made
     * @var int
     */
    public $page_count;

    /**
     * The page number of the current results
     * @var int
     */
    public $page_number;

    /**
     * The numer of records returned with a single API call
     * @var int
     */
    public $page_size;

    /**
     * The total number of all the records available across pages
     * @var int
     */
    public $total_records;

    /**
     * List of meetings objects
     * @var Illuminate\Support\Collection
     */
    public $meetings;

    /**
     * Create a new ZoomMeeting instance.
     *
     * @param array|null $meeting_list_data
     * @return void
     *
     * @throws \Danielmlozano\Zoomel\Exceptions\InvalidUser
     */
    public function __construct(array $meeting_list_data = []){
        $meetings_list = [];
        if(isset($meeting_list_data['meetings'])){
            foreach($meeting_list_data['meetings'] as $meeting){
                $meetings_list[] = new ZoomMeeting($meeting);
            }
            unset($meeting_list_data['meetings']);
            $this->fromArray($meeting_list_data);
            $this->meetings = collect($meetings_list);
        }
    }

}
