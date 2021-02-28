<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Model\Products;
use Faker\Generator as Faker;

$factory->define(Products::class, function (Faker $faker) {
    
    $title = $faker->sentence(2);
    $slug = str_slug($title, '-');

    return [
        'createdBy' => 1,
        'productName' => $title,
        'productSlug' => $slug,
        'productDescription' => $faker->paragraph
    ];
});
