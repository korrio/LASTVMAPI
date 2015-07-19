<?php

class UserController extends \BaseController {

	public function __construct()
	{

		//not sure
		$token = Request::header('X-Auth-Token');
		$this->user = json_decode(AuthToken::validate($token));
		$this->api_token = Input::get('api_token');
		//end not sure
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$params = Input::all();
		$params['captcha'] = "123456";
		$url = "https://www.vdomax.com/ajax.php?t=register&mobile=1";
		$api_response = cURL::post($url,$params);
		Log::info('Result', $api_response->toArray());

		$response = $api_response->body;
		$json["api"] = json_decode($response,true);

		$username = $params['username'];
		$password = $params['password'];

		if ( $json["api"]["status"] == 200 ){
			$json["api"]["user"]['avatar'] = $json["api"]["user"]['avatar_url'];
			$json["api"]["user"]['cover'] = $json["api"]["user"]['cover_url'];

			unset($json["api"]["user"]['avatar_url']);
			unset($json["api"]["user"]['cover_url']);
			$credential = array('username'  => $username,
								'password'  => $password);

			// attempt to do the login
			if (Auth::attempt($credential)) {
				$authToken = AuthToken::create(Auth::user());
  				$publicToken = AuthToken::publicToken($authToken);
  				return Response::json(array('status' => '1',
                               			'message' => $json["api"]["message"],
                               			'token' => $publicToken,
                               			'user' => $json["api"]["user"]
                               			//,'debug' => $json["api"]
                               			));
			}

		}else{
			return Response::json(array('status' => '0',
                               			'message' => $json["api"]["error_message"],
										'debug' => $json["api"]));
		}

		return Response::json(array('status' => '1',
                               		'response_api' => $json));



	}

	public function requestOTP() {
		$params = Input::all();
		$mobile = $params['mobile'];
		$otp = Helpers::generateOTP();
		if(!isset($params['message']) || $params['message'] != "")
			$message = "Your OTP is: {$otp}";
		else
			$message = $params['message'];
		$res = Helpers::send_sms($mobile,$message);

		return Response::json(array('status' => '1',
                               			'response_api' => $res));
	}

	public function facebookLogin() {
		$params = Input::all();
		$user = Helpers::fbAuth($params['access_token']);
		if($user != null){
			$authToken = AuthToken::create($user['user_info']);
  			$publicToken = AuthToken::publicToken($authToken);
			return Response::json(array('status' => '1',
										'message' => 'Success Facebook Auth',
										'token' => $publicToken,
										'state' => $user['state'],
                               			'user' => $user['user_info']));
		}else{
			return Response::json(array('status' => '0',
										'message' => 'Wrong access_token',
										'state' => 'wrong_access_token',
                               			'user' => $user));
		}
	}

	/**
	 * Display the specified user by id.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// GET api/v1/user/$id
		$user = Account::find($id);
		$user->birthday;
		$user->gender;
		$user->avatar;
		$user->cover;

		

		$user->is_live = Helpers::isLive($user->username);
		$user->live = "http://150.107.31.13:1935/live/".$user->username."/playlist.m3u8";

		$user->online = false;


		$count = array("post" => Helpers::SK_countPosts($id),
									 "follower" => Helpers::SK_countFollowers($id),
									 "following" => Helpers::SK_countFollowing($id),
									 "friend" => Helpers::SK_countFriends($id),
									 "love" => Helpers::SK_countPageLikes($id),
									 "group" => Helpers::SK_countGroupJoined($id),
									 //"follow_request" => Helpers::SK_countGroupJoined($id)
									 );
		
		if ( $user->count() > 0){
			return Response::json(array('status' => '1',
								'user' => $user,

								'count' => $count));
		}else{
			return Response::json(array('status' => '0',
								'message' => 'No user found'));
		}
	}

	/**
	 * Display the specified user by id.
	 *
	 * @param  int  $username
	 * @return Response
	 */
	public function showUsername($username)
	{
		// GET api/v1/user/$id
		$user = Account::where('username', $username)
                ->get()->first();

		$user->avatar;
		$user->cover;
		$user->birthday;
		$user->gender;

		

		//$user->is_following = Helpers::SK_isFollowing($id,$this->user->id);
		$user->is_live = Helpers::isLive($user->username);
		//$user->live_url = "http://150.107.31.13:1935/live/".$user->username."/playlist.m3u8";
		$user->live = "http://150.107.31.13:1935/live/".$user->username."/playlist.m3u8";

		$user->online = false;


		$count = array("post" => Helpers::SK_countPosts($user->id),
									 "follower" => Helpers::SK_countFollowers($user->id),
									 "following" => Helpers::SK_countFollowing($user->id),
									 "friend" => Helpers::SK_countFriends($user->id),
									 "love" => Helpers::SK_countPageLikes($user->id),
									 "group" => Helpers::SK_countGroupJoined($user->id),
									 //"follow_request" => Helpers::SK_countGroupJoined($id)
									 );
		
		if ( $user->count() > 0){
			return Response::json(array('status' => '1',
								'user' => $user,

								'count' => $count));
		}else{
			return Response::json(array('status' => '0',
								'message' => 'No user found'));
		}
	}

	public function page($id) {
		$params = Input::all();
		$url = "https://www.vdomax.com/ajax.php?t=getLovedPage&id=".$id."&mobile_api=1";
		$response = cURL::get($url);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
																		'count' => count($json),
																		'user_id' => $id,
                               			'page' => $json
                               			));
		else 
			return Response::json(array('status' => '0',
																		'count' => count($json),
																		'user_id' => $id,
                               			'page' => $json
                               			));
	}

	public function changePassword() {
		$params = Input::all();
		$old_password = md5(Helpers::SK_secureEncode($params['old_password']));
		$new_password = md5(Helpers::SK_secureEncode($params['new_password']));
		//$hash = md5($password);
		$userId = (int) $this->user->id;
		$dbConnect = Helpers::dbConnect();

		if($old_password && $old_password != $new_password) {
			$find = mysqli_query($dbConnect, "SELECT password from accounts WHERE id = {$userId} AND password = '{$old_password}'");
			$sql_numrows = mysqli_num_rows($find);

			if($sql_numrows == 1) {
				//$sql_fetch = mysqli_fetch_assoc($sql_query);
				$res = mysqli_query($dbConnect, "UPDATE accounts SET password = '{$new_password}' WHERE id = {$userId}");

				if($res)
					return Response::json(array('status' => '1','message'=>'Success, your password is changed','user_id'=>$userId));
				else
					return Response::json(array('status' => '0','message'=>'Failed','user_id'=>$userId));
			}
			return Response::json(array('status' => '0','message'=>'Failed, more than 1 user found','user_id'=>$userId));
		
		}
		return Response::json(array('status' => '0','message'=>'Failed, new password should not be same as old password','user_id'=>$userId));
	}

	/**
	 * Display the specified by username.
	 *
	 * @param  int  $username
	 * @return Response
	 */
	public function check($username)
	{
		// GET api/v1/user/$id
		$user = Account::where('username', $username)->get();
		/*
		$user->birthday;
		$user->gender;
		$user->avatar;
		$user->cover;
		*/
		
		
		
		if ( $user->count() > 0){
			return Response::json(array('status' => '1',
								'user' => $user->first()));
		}else{
			return Response::json(array('status' => '0',
								'message' => 'No user found'));
		}
	}

	



	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = Account::find($id);
		$password = $user->password;
		$url = "https://www.vdomax.com/ajax.php?t=user&a=settings&mobile=1&user_id=" . $id . "&user_pass=" . $password;
		$params = Input::all();
		$api_response = cURL::post($url,$params);
		Log::info('Result', $api_response->toArray());

		$response = $api_response->body;
		$json["url"] = $url;
		$json["api"] = json_decode($response,true);

		if ( $json["api"]["status"] == 200 ){
			$a['status'] = '1';
			$a['message'] = 'Update profile success !';
			$a['params'] = $params;
			return Response::json($a);
		} else {
			return Response::json(array('status'=>'0','message'=>'something went wrong','params'=>$params,'debug' => $json));
		}

		

		/*
		username:manual
name:Yo Cool
about:
email:manual@gmail.com
birthday[0]:1
birthday[1]:1
birthday[2]:1990
gender:male
current_city:
hometown:
timezone:Pacific/Midway
*/
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
