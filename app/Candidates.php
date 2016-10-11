<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidates extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'candidates';    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','name','college','specialization','mobile','location','role','local','group'];

}
