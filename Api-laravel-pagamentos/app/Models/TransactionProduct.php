<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class TransactionProduct extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'price'];

    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
           
            if ($model->quantity < 1) {
                throw new ValidationException("Quantity must be at least 1.");
            }
            
            
            if ($model->price <= 0) {
                throw new ValidationException("Price must be a positive number.");
            }
        });

        static::updating(function ($model) {
            
            if ($model->quantity < 1) {
                throw new ValidationException("Quantity must be at least 1.");
            }

            
            if ($model->price <= 0) {
                throw new ValidationException("Price must be a positive number.");
            }
        });
    }
}
