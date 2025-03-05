<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount'];

   
    public function transactionProducts()
    {
        return $this->hasMany(TransactionProduct::class);
    }
}
