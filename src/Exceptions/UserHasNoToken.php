<?php
namespace Danielmlozano\Zoomel\Exceptions;

use Exception;

class UserHasNoToken extends Exception
{
    /**
     * The main exception message
     *
     * @var string
     */
    protected $message = "The provided User has no token registered. Please request a token and attach it to the User";

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
        // some code

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
