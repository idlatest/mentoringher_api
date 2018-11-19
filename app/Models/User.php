<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
	protected $table = 'users';

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
    'password',
    'education',
    'board_certification',
    'is_doctor',
    'legal_name'
	];

  public function getAvatarAttribute($value)
  {
    if (is_null($value)) {
      return 'https://static.productionready.io/images/smiley-cyrus.jpg';
    }

    return getenv('APP_URL') . '/' . $value;
  }

	// public function recording() 
	// {
	// 	return $this->belongsToMany(Recording::class, 'recording_like');
	// }

	// public function followings($userId = null)
 //  {
 //    return $this->belongsToMany(
 //      User::class,
 //      'users_following',
 //      'user_id',
 //      'following_user_id')
 //      ->withTimestamps();
 //  }

	// public function follow($userId)
	// {
	// 	return $this->followings()->syncWithoutDetaching($userId);
	// }

	// public function unfollow($userId)
	// {
	// 	return $this->followings()->detach($userId);
	// }

	// public function isFollowing($id = null)
  // {
  //   if (is_null($id)) {
  //     return false;
  //   }

  //   if ($id instanceof self) {
  //     $id = $id->id;
  //   }

  //   return $this->newBaseQueryBuilder()
  //     ->from('users_following')
  //     ->where('user_id', $this->id)
  //     ->where('following_user_id', $id)
  //     ->exists();
  // }

  // public function isFollowedBy($id = null)
  //   {
  //     if (is_null($id)) {
  //       return false;
  //     }

  //     if ($id instanceof self) {
  //       $id = $id->id;
  //     }

  //     return $this->newBaseQueryBuilder()
  //       ->from('users_following')
  //       ->where('user_id', $id)
  //       ->where('following_user_id', $this->id)
  //       ->exists();
  // }
}