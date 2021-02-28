<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Model\Locations;
use App\Model\Images;
use App\Model\SpeakerDay;
use App\Model\Speaker;
use App\Model\Social;
use App\Model\Impact;
use App\Model\DressCode;
use App\Model\Attendee;
use App\Model\TravelCoache;
use App\Model\TravelCoacheDayDetail;
use App\Model\TravelFlight;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/*$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});*/

/*$factory->define(Images::class, function (Faker $faker) {

    $imageType = $faker->randomElement(['Social', 'Location']);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'imageType' => $imageType,
        'moduleId' => $moduleId,
        'imagePath' => $imagePath,
    ];
});

$factory->define(Images::class, function (Faker $faker) {

    $imageType = $faker->randomElement(['Social', 'Location']);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'name' => $imageType,
        'moduleId' => $moduleId,
        'about' => $imagePath,
    ];
});

$factory->define(SpeakerDay::class, function (Faker $faker) {

    $imageType = $faker->randomElement(['Social', 'Location']);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'speakerDate' => now(),
    ];
});

$factory->define(Speaker::class, function (Faker $faker) {

    $imageType = $faker->randomElement(['Social', 'Location']);
    $dayId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'dayId' => $dayId,
        'name' => $faker->name,
        'profilePic' => $imagePath,
        'about' => Str::random(50),
    ];
});

$factory->define(Impact::class, function (Faker $faker) {

    $imageType = $faker->randomElement(['Social', 'Location']);
    $dayId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'endTitle' => Str::random(12),
        'image' => $imagePath,
        'description' => Str::random(50),
    ];
});

$factory->define(Social::class, function (Faker $faker) {

    $dressCode = $faker->randomElement(['Smart Casual', 'Cocktail Attire', 'Others']);
    $dayId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'title' => Str::random(12),
        'dateTime' => now(),
        'dressCode' => $dressCode,
        'description' => Str::random(50),
    ];
});

$factory->define(DressCode::class, function (Faker $faker) {

    $dressCode = $faker->randomElement(['Smart Casual', 'Business Casual', 'Cocktail Attire', 'Others']);
    $dayId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
    $imagePath = $faker->randomElement(['game_1572586186_gprxcf.jpeg', 'game_1572604127_n7dhu8.jpeg', 'game_1572604219_1ezh6s.jpg', 'game_1575113369_0caicm.png',
                                        'game_1580993068_udop4t.jpeg', 'game-listing-play-item-01.jpg', 'game-listing-play-item-02.jpg', 'game-listing-play-item-04.jpg',
                                        'game-listing-play-item-05.jpg', 'game_1572604127_n7dhu8.jpeg', 'AvatarImage_1571637822_atccww.jpg']);

    $moduleId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'title' => Str::random(12),
        'image' => $imagePath,
        'description' => Str::random(50),
        'staticDescription' => $dressCode,
    ];
});

$factory->define(Attendee::class, function (Faker $faker) {
    return [
        'firstName' => Str::random(8),
        'lastName' => Str::random(8),
        'organization' => Str::random(16),
    ];
});

$factory->define(TravelCoache::class, function (Faker $faker) {
    return [
        'description' => Str::random(12),
        'dateTime' => now(),
    ];
});*/

$factory->define(TravelCoacheDayDetail::class, function (Faker $faker) {

    $coacheId = $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);

    return [
        'coacheId' => $coacheId,
        'source' => Str::random(8),
        'destination' => Str::random(8),
        'depart' => now(),
        'arrive' => now(),
    ];
});

$factory->define(TravelFlight::class, function (Faker $faker) {
    return [
        'firstName' => Str::random(12),
        'lastName' => Str::random(8),
        'arrivingFrom' => now(),
        'departureTo' => now(),
    ];
});