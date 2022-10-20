<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "gender",
        "date_of_birth",
        "contact_number",
        "email",
    ];

    //===== Relationships =====//

    public function PurchaseTransactions()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }

    public function Voucher()
    {
        return $this->hasOne(Voucher::class);
    }
}
