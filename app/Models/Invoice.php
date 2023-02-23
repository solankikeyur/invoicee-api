<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = "invoice";

    protected $fillable = [
        "customer_id",
        "customer_name",
        "user_id",
        "items",
        "total_amount"
    ];

    public function customer()
    {
        return $this->hasOne("App\Models\Customer", "id", "customer_id");
    }
}
