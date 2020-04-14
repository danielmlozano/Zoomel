<?php

namespace Danielmlozano\Zoomel;

use Danielmlozano\Zoomel\Zoom;

class Zoomel
{
    /**
     * Indicates if Zoomel routes should be registered
     *
     * @var bool
     */
    public static $register_routes = true;

    /**
     * Indicates if Zoomel migrations will be run.
     *
     * @var bool
     */
    public static $register_migrations = true;

    /**
     * Gets a new Zoom Auth Link
     *
     * @return string
     */
    public static function getAuthLink(){
        $zoom = new Zoom();
        return $zoom->access_link;
    }

}
