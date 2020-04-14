<?php

namespace Danielmlozano\Zoomel;

use Danielmlozano\Zoomel\Zoom;

class Zoomel
{
    public static $auth_link;

    public static $register_routes = true;

    public static $register_migrations = true;

    public static function getAuthLink(){
        $zoom = new Zoom();
        return $zoom->access_link;
    }

}
