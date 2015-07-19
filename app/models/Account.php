<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Account extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected function getDateFormat() {
		return 'U';
	}

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'accounts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

	public function getAvatarAttribute(){
		$avatar = Media::where('id', $this->attributes['avatar_id'])
			->get(array('id','url','extension'))->first();
		if ( !empty($avatar->id) ){
				$response = $avatar->url."_100x100.".$avatar->extension;
		}else{
			$response = "themes/vdomax1.1/images/default-male-avatar.png";
		}
		return $this->attributes['avatar'] = $response;
	}

	public function getCoverAttribute(){
		$cover = Media::where('id', $this->attributes['cover_id'])
			->get(array('url','extension'))->first();
		if ( !empty($cover->url) ){
				$response = $cover->url."_cover.".$cover->extension;
		}else{
			$response = "themes/vdomax1.1/images/default-cover.png";
		}
		return $this->attributes['cover'] = $response;
	}

	public function getBirthdayAttribute(){
		$birthday = User::where('id', $this->attributes['id'])
			->get(array('birthday'))->first();
		if ( !empty($avatar->birthday) ){
				$response = $birthday->birthday;
		}else{
			$response = "01/01/1990";
		}
		return $this->attributes['birthday'] = $response;
	}

	public function getGenderAttribute(){
		$gender = User::where('id', $this->attributes['id'])
			->get(array('gender'))->first();
		if ( !empty($avatar->gender) ){
				$response = $gender->gender;
		}else{
			$response = "male";
		}
		return $this->attributes['gender'] = $response;
	}

	public function getLiveUrlAttribute(){
		/*$user = User::where('id', $this->attributes['id'])
			->get()->first();
		return "rtmp://server-a.vdomax.com:1935/live/".$user->username;
		*/
		return "";
	}

	/**
	 * Eloquent DB relation.
	 *
	 */
	function Follow(){
		return $this->hasMany('Post')->select(array('following_id'));
	}

	function Post(){
		return $this->belongsTo('Post')->select(array('id', 'name'));
	}

	function User(){
		return $this->hasOne('User')->select(array('birthday', 'gender'));
	}


}
