<?php
namespace Danielmlozano\Zoomel;

use Illuminate\Database\Eloquent\Model;

class ZoomUserToken extends Model
{
    protected $fillable = [
        'user_id',
        'safe_id',
        'auth_token',
        'refresh_token',
        'scope',
    ];

    public function user(){
        return $this->belongsTo(config('zoomel.user_model'));
    }

    public function scopeFindSafeId($q,$safe_id){
        return $this->where('safe_id',$safe_id)->first();
    }

}
