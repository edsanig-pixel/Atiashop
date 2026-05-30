<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'party_id',
        'amount',
        'type',
        'bank_account',
        'description',
		'user_id'
    ];
}


