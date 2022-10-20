<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "code",
        "status",
        "customer_id",
    ];

    //===== Relationships =====//

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
