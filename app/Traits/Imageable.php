<?php

namespace App\Traits;
use App\Models\Image;
//use http\Env\Request;

trait Imageable
{
    public static function ssave($getImages,$id)
    {
        foreach($getImages as $getImage) {
            $filename = $getImage->getClientOriginalName();
            $name = pathinfo($filename, PATHINFO_FILENAME) . '' . time() . '.' . $getImage->getClientOriginalExtension();
            $path = 'articles/' . $name;
            $getImage->move(public_path('articles'), $name);
            $save = Image::create([
                'name' => $name,
                'path' => $path,
                'article_id' => $id
            ]);
        }


    }




}

