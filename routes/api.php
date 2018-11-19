<?php

use App\Files\FileStore;
use App\Models\Category;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;

$jwtAuth = $container->get('jwt');
$optionalAuth = $container->get('optionalAuth');

// Auth routes
$app->post('/auth/register', 'RegisterController:register');
$app->post('/auth/login', 'LoginController:login');

// User routes
$app->get('/user', 'UserController:show')->add($jwtAuth);
$app->put('/user', 'UserController:update')->add($jwtAuth);
$app->put('/user/changePassword', 'UserController:changePassword')->add($jwtAuth);

// Clinic routes
$app->post('/clinics', 'ClinicController:store')->add($jwtAuth);
$app->get('/clinics', 'ClinicController:index')->add($optionalAuth);
$app->get('/clinics/{id}', 'ClinicController:show')->add($optionalAuth);
$app->put('/clinics/{id}', 'ClinicController:update')->add($jwtAuth);

// Booking routes
$app->get('/clinics/{clinic_id}/bookings', 'BookingController:index')->add($optionalAuth);
$app->post('/clinics/{clinic_id}/bookings', 'BookingController:store')->add($jwtAuth);
$app->delete('/clinics/{clinic_id}/bookings/{id}', 'BookingController:destroy')->add($jwtAuth);

// Review routes
$app->get('/clinics/{clinic_id}/reviews', 'ReviewController:index')->add($optionalAuth);
$app->post('/clinics/{clinic_id}/reviews', 'ReviewController:store')->add($jwtAuth);
$app->delete('/clinics/{clinic_id}/reviews/{id}', 'ReviewController:destroy')->add($jwtAuth);

// Post routes
$app->post('/posts', 'PostController:store')->add($jwtAuth);
$app->get('/posts', 'PostController:index')->add($optionalAuth);
$app->get('/posts/{id}', 'PostController:show')->add($optionalAuth);
$app->post('/posts/{id}', 'PostController:reply')->add($jwtAuth);
$app->put('/posts/{id}', 'PostController:update')->add($jwtAuth);

// Comment routes
$app->get('/posts/{post_id}/comments', 'CommentController:index')->add($optionalAuth);
$app->post('/posts/{post_id}/comments', 'CommentController:store')->add($jwtAuth);
$app->delete('/posts/{post_id}/comments/{id}', 'CommentController:destroy')->add($jwtAuth);


// // Likes recording routes
// $app->post('/recordings/{id}/likes', 'RecordingLikeController:store')->add($jwtAuth);
// $app->delete('/recordings/{id}/likes', 'RecordingLikeController:detroy')->add($jwtAuth);

// Profile routes
$app->get('/profiles', 'ProfileController:index')->add($optionalAuth);
$app->get('/profiles/{user_id}', 'ProfileController:show')->add($optionalAuth);
$app->post('/profiles/{user_id}/follow', 'ProfileController:follow')->add($jwtAuth);
$app->delete('/profiles/{user_id}/unfollow', 'ProfileController:unfollow')->add($jwtAuth);

// Uploads route
$app->post('/uploads', 'UploadController:store')->add($jwtAuth);

// Search route
$app->get('/search/{query}', 'SearchController:index')->add($optionalAuth);

// // Categories route
// $app->get('/categories', function ($request, $response) {
//     return $response->withJson([
//         'categories' => Category::all(),
//     ]);
// });
