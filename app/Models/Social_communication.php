<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social_communication extends Model
{
    use HasFactory;
    protected $table = 'social_communications';
    protected $primaryKey ='id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'address'
    ];
}
