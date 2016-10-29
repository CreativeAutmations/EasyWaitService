<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'receipts';    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['bill_number','bill_date','b17_debit','description','unit','customs_station','warehouse_details','eou_details','other_procurement_source','invoice_no','invoice_date','procurement_certificate','procurement_date','unit_weight','unit_quantity','value','duty','transport_registration','receipt_timestamp','balance_quantity','balance_value'];


	
}

