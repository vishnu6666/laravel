<?php

namespace App\Model;

class PageContent extends CustomModel
{
    protected $table ='page_content';
    protected $fillable = [
        'name',
        'title',
        'slug',
        'content',
        'isTitleShow',
        'isContentShow',
    ];

}
