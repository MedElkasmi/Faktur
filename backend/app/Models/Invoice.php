<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['kassen_id', 'receipt_number', 'total_amount', 'net_amount']; // Add all fields here

    public function user() {

        return $this->belongsTo(User::class);
    }

    public function information() {

        return $this->hasOne(Information::class);
    }
}
