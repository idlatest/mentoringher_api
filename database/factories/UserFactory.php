<?php

$this->factory->define(\App\Models\User::class, function (\Faker\Generator $faker) {
        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email'    => $faker->email,
            'token'    => $faker->password,
            'password' => password_hash($faker->password, PASSWORD_DEFAULT),
            'image' => 'https://static.productionready.io/images/smiley-cyrus.jpg',
        ];
    });

