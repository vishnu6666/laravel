<?php

namespace App\Model;

class Faq extends CustomModel
{
    protected $fillable = [
        'question',
        'answer',
        'title'
    ];

}
