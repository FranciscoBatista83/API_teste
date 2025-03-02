<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'user_id', 'gateway_id', 'total_price', 'status'];

    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }

    
    public function transactionProducts()
    {
        return $this->hasMany(TransactionProduct::class);
    }
}
