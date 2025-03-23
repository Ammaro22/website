<?php

namespace App\Models;

use App\Traits\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory,Imageable;
    protected $table = 'articles';
    protected $primaryKey ='id';
    public $timestamps = true;
    protected $fillable = [
        'article_name',
        'website_name',
        'explain',
        'url',
        'category_id',
    ];

    public function image(){
        return $this->hasmany(Image::class,'article_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

}
