<?php
namespace Danielmlozano\Zoomel;
use Danielmlozano\Zoomel\ZoomObject;

class ZoomUser extends ZoomObject
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
     * User's first name
     * @var string
     */
    public $first_name;

    /**
     * User's second name
     * @var string
     */
    public $last_name;

    /**
     * User's email
     * @var string
     */
    public $email;

    /**
     * User's plan type:
     * 1. Basic
     * 2. Licensed
     * 3. On-prem
     * @var string
     */
    public $type;

    /**
     * User's role name
     * @var string
     */
    public $role_name;

    /**
     * User's personal meeting ID
     * @var string
     */
    public $pmi;

    /**
     * Use pmi for instant meetings
     * @var bool
     */
    public $use_pmi;

    /**
     * User's personal meeting url
     * @var string
     */
    public $personal_meeting_url;

    /**
     * User's timezone
     * @var string
     */
    public $timezone;

    /**
     * Whether the user is verified or not
     * @var string
     */
    public $verified;

     /**
     * Deparment
     * @var string
     */
    public $dept;

     /**
     * User's create time
     * @var string
     */
    public $created_at;

     /**
     * User's last login time
     * @var string
     */
    public $last_login_time;

    /**
     * User's last login client version
     * @var string
     */
    public $last_client_version;

    /**
     * User's host key
     * @var string
     */
    public $host_key;

    /**
     * Don't know what the fuck is this
     * @var string
     */
    public $jid;

    /**
     * IDs of the web groups the user belongs to
     * @var string
     */
    public $group_ids;

    /**
     * IMD IDs of the groups the user belongs to
     * @var string
     */
    public $im_group_ids;

    /**
     * User's account id
     * @var string
     */
    public $account_id;

    /**
     * User's default language for the Zoom web portal
     * @var string
     */
    public $language;

    /**
     * User's country for the Company Phone Number
     * @var string
     */
    public $phone_country;

    /**
     * User's phone number
     * @var string
     */
    public $phone_number;

    /**
     * Status of the User's account
     * @var string
     */
    public $status;

    /**
     * Create a new ZoomMeeting instance.
     *
     * @param array|null $user_data
     * @return void
     *
     */
    public function __construct(array $user_data = []){
        $this->fromArray($user_data);
    }

}
