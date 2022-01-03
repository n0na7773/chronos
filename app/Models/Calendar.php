<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'main',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'main' => "boolean",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
