<?php
namespace Danielmlozano\Zoomel\Exceptions;

use Exception;

class CantRefreshToken extends Exception
{
    /**
     * The main exception message
     *
     * @var string
     */
    protected $message = "There was a problem refreshing the Zoom OAuth access token, please re-connect your Zoom account and try again";

    /**
     * The main exception message
     *
     * @var int
     */
    protected $code = 422;

    /**
     * Create a new UserHasNoToken instance.
     *
     * @return void
     */
    public function __construct(Exception $previous = null) {

        // make sure everything is assigned properly
        parent::__construct($this->message, $this->code, $previous);
    }

    /**
     * Returns the string exception message
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }


}
