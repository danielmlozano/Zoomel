<?php
namespace Danielmlozano\Zoomel\Exceptions;

use Exception;

class InvalidUser extends Exception
{
    /**
     * Create a new InvalidUser instance.
     *
     * @return static
     */
    public static function invalidUser()
    {
        return new static("The provided User is not an instance from a Database User");
    }
}
