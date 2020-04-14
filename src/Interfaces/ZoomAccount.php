<?php
namespace Danielmlozano\Zoomel\Interfaces;

interface ZoomAccount
{
    /**
     *  Returns the related model ZoomUserToken
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
    public function zoomToken();

}
