<?php

namespace App\Http\Classes;

use App\Models\Tag;

class Tags{
    public function getAllTags(){
        return Tag::get('slug');
    }
}
