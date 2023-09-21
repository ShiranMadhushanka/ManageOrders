<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $fillable = ['customer_name', 'order_value', 'order_date', 'order_status', 'process_id'];
}
