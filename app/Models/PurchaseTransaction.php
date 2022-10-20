<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "customer_id",
        "total_spent",
        "total_saving",
        "transaction_at",
    ];

    //===== Relationships =====//

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
