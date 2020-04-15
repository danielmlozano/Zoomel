<?php
namespace Danielmlozano\Zoomel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ZoomUserToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'safe_id',
        'auth_token',
        'refresh_token',
        'scope',
        'expires_in',
    ];

    /**
     * The appends properties
     *
     * @var array
     */
    public $appends = [
        'expiring',
    ];


     /**
     *
     *  Returns the related parent model User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('zoomel.user_model'));
    }


    /**
     *
     *  Returns a self instace from a given safe_id
     * @return \Danielmlozano\Zoomel\ZoomUserToken|null
     */
    public function scopeFindSafeId($q,$safe_id){
        return $this->where('safe_id',$safe_id)->first();
    }

    /**
     *
     * Returns whether the token expired or is expiring
     * @return bool|null
     */
    public function getExpiringAttribute(){
        if($this->updated_at){
            $now = Carbon::now();
            $diff = $this->updated_at->diffInSeconds($now);
            return $diff >= $this->expires_in;

        }
        return null;
    }

}
