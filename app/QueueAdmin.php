<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueAdmin extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'queue_admins';    //

	public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id'];

}
