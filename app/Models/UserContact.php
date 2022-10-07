<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'user_id',
        'user_category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userCategory()
    {
        return $this->belongsTo(UserCategory::class);
    }
}
