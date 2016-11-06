<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_organization';    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','org_id'];
}
