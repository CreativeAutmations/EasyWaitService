<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audit';    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['bill_number','email','category','action','change_log'];
}

