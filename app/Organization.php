<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'organizations';    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['org_name','address','tax_registration','tax_commissionar'];
}

