<?php

namespace App\Models;

use App\Providers\AppServiceProvider;
use App\Traits\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory,Imageable;
    protected $table ='images';
    protected $fillable =[
        'name',
        'path',
        'article_id',
    ];

    public function user(){
        return $this->belongsTo(Article::class,'article_id');
    }

}
