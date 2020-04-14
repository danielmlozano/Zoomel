<?php
namespace Danielmlozano\Zoomel;

use Illuminate\Database\Eloquent\Model;

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

}
