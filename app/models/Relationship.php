<?php

class Relationship extends Eloquent{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'followers';

	function FollowerAccount(){
		return $this->hasOne('Account', 'id', 'follower_id');
	}

	function FollowingAccount(){
		return $this->hasOne('Account', 'id', 'following_id');
	}

	function PageAccount(){
		return $this->hasOne('Account', 'id', 'following_id');
	}


}


