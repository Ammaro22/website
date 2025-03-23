<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey ='id';
    public $timestamps = true;
    protected $fillable = [
        'name',
    ];


    public function article(){
        return $this->hasmany(Article::class,'category_id');
    }

}
