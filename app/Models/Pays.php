<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'is_paid',
        'total_pay',
        'bank_name',
        'account_num'
    ];
}
