<?php
namespace Danielmlozano\Zoomel;
use Danielmlozano\Zoomel\ZoomObject;

class ZoomMeetingRecurrence extends ZoomObject
{
    /**
     * The recurrence type
     * 1: daily
     * 2: weekly
     * 3: monthly
     * @var int
     */
    public $type;

    /**
     * Tefine the interval at which the meeting dhould recur
     * @var int
     */
    public $repeat_interval;

    /**
     * If a meeting is type 2, these are te days of week the meeting will recur
     * @var string
     */
    public $weekly_days;

    /**
     * If a meeting is type 3, this is the day of the month the meeting will recur
     * @var int
     */
    public $monthly_day;

    /**
     * If a meeting is type 3, this is the week of the month the meeting will recur
     * @var int
     */
    public $monthly_week;

    /**
     * If a meeting is type 3, this is the day of the week of the month the meeting will recur
     * @var int
     */
    public $monthly_week_day;

    /**
     * The times the meeting should repeat until it is cancelled
     * @var int
     */
    public $end_times;

    /**
     * The final date on hich the meeting will recur
     * @var string
     */
    public $end_date_time;



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
