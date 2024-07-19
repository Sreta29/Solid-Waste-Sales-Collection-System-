<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Category;

class OrderList extends Model
{
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'quantity',
        'order_date',
        'collect_date',
        'collector_id',
        'status',
        'waste_image'
    ];

    public static function generateCustomOrderId()
    {
        
        //get current year
        $year = date('Y');

        //get lastest data
        $count = self::whereYear('created_at', $year)->count();

        $newNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "$newNumber/$year";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
