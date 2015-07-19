<?php

class PostsController extends \BaseController {
	public function __construct()
	{
		$token = Request::header('X-Auth-Token');
		$this->user = json_decode(AuthToken::validate($token));
		$this->api_token = Input::get('api_token');
		$this->page = Input::get('page');
		$this->per_page = Input::get('per_page');
		$this->type = Input::get('type');
		$this->dbConnect = Helpers::dbConnect();
	}

	public static function get_user_timeline($id)
	{
		return Post::getPost(array($id), $this->page, $this->per_page, $this->type);
		
	}

	/**
	 * Show all post regarding to user id.
	 * @return Response
	 */
	public function photos()
	{
		
		if(isset($params['sort'])) {
			$sort = $params['sort'];
		} else {
			$sort = "N";
		}

		return Post::getPost(array(6), $this->page, $this->per_page, "photo",$sort);
	}

	public function videos()
	{
		$params = Input::all();
		if(isset($params['sort'])) {
			$sort = $params['sort'];
		} else {
			$sort = "N";
		}

		return Post::getPost(array(6), $this->page, $this->per_page, "video",$sort);
	}

	public function user_timeline($id)
	{
		return Post::getPost(array($id), $this->page, $this->per_page, $this->type);
		
	}

	/**
	* Returns a collection of the most recent Post  by the authenticating user and the users they follow
	* @return Response
	*/
	public function home_timeline($id)
	{
		//get all follower where user id = follower_id
		$followers = Relationship::where('follower_id', $id)
			->get(array('following_id'));

		if ( $followers->count() > 0 ){
			$follower_id = array();
			array_push($follower_id,$id);
			foreach ( $followers as $follower ){
				array_push($follower_id, $follower->following_id);
			}

			return Post::getPost($follower_id, $this->page, $this->per_page, $this->type);
		}else{
			return Response::json(array('status' => '0',
                               	'message' => 'No user found'));
		}
	}

	/**
	 * Follow specific post.
	 *
	 * @return Response
	 */
	public function follow_post($id)
	{
		/*
		if ( is_null($this->api_token) || empty($this->api_token) ){
			Response::json(array('status' => '0',
								'message' => 'API toke is required'));
		}
		*/

		Log::info('========== Follow post ==========');
		$params = array('id' => $id);

		Log::info('Inputs', $params);
		$url = 'https://www.vdomax.com/ajax.php?t=post&a=follow&post_id='.$id."&token=".$this->api_token."&user_id=".$this->user->id."&user_pass=".$this->user->password."&timeline_id=".$this->user->id;
		$api_response = cURL::get($url);
		Log::info('Result', $api_response->toArray());

		$response = substr($api_response->body, 10,3);

		if ( $response == 200 ){
			return Response::json(array('status' => '1',
                               			'response_code' => '200'));
		}else{
			return Response::json(array('status' => '0',
                               			'response_code' => $response));
		}
	}

	/**
	 * Love specific post.
	 *
	 * @return Response
	 */
	public function love_post($id)
	{
		/*
		if ( is_null($this->api_token) || empty($this->api_token) ){
			return Response::json(array('status' => '0',
								'message' => 'API toke is required'));
		}
		*/

		Log::info('========== Like post ==========');
		$params = array('id' => $id);

		Log::info('Inputs', $params);
		$url = 'https://www.vdomax.com/ajax.php?t=post&a=like&post_id='.$id."&token=".$this->api_token."&user_id=".$this->user->id."&user_pass=".$this->user->password."&timeline_id=".$this->user->id;
		$api_response = cURL::get($url);
		Log::info('Result', $api_response->toArray());

		$response = substr($api_response->body, 10,3);
		$json = json_decode($api_response->body,true);

		if ( $response == 200 ){
			if(Helpers::SK_isPostLoved($id,$this->user->id))
				return Response::json(array('status' => '1',
																		'is_loved' => Helpers::SK_isPostLoved($id,$this->user->id),
                               			'message' => 'Love Success'));
			else
				return Response::json(array('status' => '1',
					'is_loved' => Helpers::SK_isPostLoved($id,$this->user->id),
                               			'message' => 'Break Success'));
		}else{
			return Response::json(array('status' => '0',
                               			'message' => 'Love/Break failed',
                               			'debug' => $json));
		}
	}

	/**
	 * Share specific post.
	 *
	 * @return Response
	 */
	public function share_post($id)
	{
		/*
		if ( is_null($this->api_token) || empty($this->api_token) ){
			Return Response::json(array('status' => '0',
								'message' => 'API toke is required'));
		}
		*/

		Log::info('========== Share post ==========');
		$params = Input::all();

		Log::info('Inputs', $params);
		$url = 'https://www.vdomax.com/ajax.php?t=post&a=share&post_id='.$id."&token=".$this->api_token."&user_id=".$this->user->id."&user_pass=".$this->user->password."&timeline_id=".$this->user->id;;
		$api_response = cURL::post($url, $params);
		Log::info('Result', $api_response->toArray());

		$response = substr($api_response->body, 10,3);

		if ( $response == 200 ){
			if(Helpers::SK_isPostShared($id,$this->user->id))
				return Response::json(array('status' => '1',
																		'is_shared' => Helpers::SK_isPostShared($id,$this->user->id),
                               			'message' => 'Share Success'));
			else
				return Response::json(array('status' => '1',
					'is_shared' => Helpers::SK_isPostLoved($id,$this->user->id),
                               			'message' => 'Unshare Success'));
		}else{
			return Response::json(array('status' => '0',
																		'message' => 'Share failed',
                               			'response_code' => $response));
		}
	}

	/**
	 * Comment to specific post.
	 *
	 * @return Response
	 */
	public function comment($id)
	{
		/*
		if ( is_null($this->api_token) || empty($this->api_token) ){
			return Response::json(array('status' => '0',
								'message' => 'API toke is required'));
		}
		*/

		Log::info('========== Comment to post ==========');
		$params = Input::all();

		Log::info('Inputs', $params);
		$url = 'https://www.vdomax.com/ajax.php?t=post&a=comment&post_id='.$id."&token=".$this->api_token."&user_id=".$this->user->id."&user_pass=".$this->user->password."&timeline_id=".$this->user->id;
		//print $url;
		$api_response = cURL::post($url, $params);
		Log::info('Result', $api_response->toArray());

		$response = substr($api_response->body, 10,3);

		//print_r($params);

		//print $api_response->body;

		if ( $response == 200 ){
			return Response::json(array('status' => '1',
                               			'message' => 'Comment Success'));
		}else{
			return Response::json(array('status' => '0',
																		'message' => 'Comment failed',
                               			'response_code' => $response));
		}
	}
	
	/**
	 * Report specific post.
	 *
	 * @return Response
	 */
	public function report_post($id)
	{
		/*
		if ( is_null($this->api_token) || empty($this->api_token) ){
			return Response::json(array('status' => '0',
								'message' => 'API toke is required'));
		}
		*/

		Log::info('========== Report post ==========');
		$params = array('id' => $id);

		Log::info('Inputs', $params);
		$url = 'https://www.vdomax.com/ajax.php?t=post&a=report&post_id='.$id."&token=".$this->api_token."&user_id=".$this->user->id."&user_pass=".$this->user->password."&timeline_id=".$this->user->id;;
		$api_response = cURL::get($url);
		Log::info('Result', $api_response->toArray());

		$response = substr($api_response->body, 10,3);



		if ( $response == 200 ){
			return Response::json(array('status' => '1',
                               			'response_code' => '200'));
		}else{
			return Response::json(array('status' => '0',
                               			'response_code' => $response));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	public function store_deprecate()
	{
		/*
		if ( is_null($this->api_token) || empty($this->api_token) ){
			return Response::json(array('status' => '0',
								'message' => 'API toke is required'));
		}
		*/

		Log::info('========== Add new post ==========');
		$params = Input::all();
/*
		$photos = "";

		foreach ($params['photos'] as $photo) {
			$photo->move(storage_path().'/tmp', $photo->getClientOriginalName());
			$photos = '@'.storage_path().'/tmp/'.$photo->getClientOriginalName();
		}
		*/

		Log::info('Inputs', $params);
		 $url = 'https://www.vdomax.com/ajax.php?t=post&a=new&user_id='.$this->user->id."&user_pass=".$this->user->password."&token=".$this->api_token;
		//$api_response = cURL::post($url, $params);
		//Log::info('Result', $api_response->toArray());

		
		 $api_response = cURL::newRequest('post', $url, $params)
		     ->setHeaders(array('content-type'=> 'multipart/form-data'))
		     ->send();
	
		$json = json_decode($api_response->body,1);

		//echo $api_response;

		 //$response = $params['photos'];
		 if ( $json['status']  == 200 ){
		 	return Response::json(array('status' => '1',
                                			'response_code' => '200',
                                			'debug'=> $url));
		 }else{
		 	return Response::json(array('status' => '0',
                                			'response_code' => $json['status'],
                                			'response_body' =>$json,
                                			'debug'=> $url));
		 }

		 

/*
		$post = array('timeline_id' => '3082',
					'recipient_id' => '',
					'text' => 'test',
					'soundcloud_title' => '',
					'soundcloud_uri' => '',
					'youtube_title' => '',
					'youtube_description' => '',
					'youtube_video_id' => '',
					'google_map_name' => '',
					'clip_title' => '',
					'clip_description' => '',
					'photos' => $photos,
					'clips' => '',
					'ppv_link' => '',
					'ppv_thumb' => '',
					'ppv_title' => '');
 
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec ($ch);
		curl_close ($ch);

		*/


		echo $result;
	}

		public function hashtag() {

			$params = Input::all();
			$search_query = $params['q'];


		$hashdata = Helpers::SK_getHashtag($search_query);
    
    $posts = Helpers::SK_getHashtagPosts($hashdata);

			
		  return Response::json(array('status' => '1',
		  	
		  	'page' => 1,
		  	'per_page' => 20,
		  	'pages' => 1,
		  	'total' => count($posts),
		  	'offset' => 0,
		  	'sort' => "DEFAULT",
		  	'query' => $search_query,
		  	'hashdata' => $hashdata ,
		  	'user' => null,
		  	'count' => null,
                               			'posts' => $posts));
	}

	

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$data = Helpers::getPost($id);

			return Response::json(array('status' => '1',
                               			'post' => $data));
		//return $data;
	}

	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Log::info('========== Delete post ==========');
		$params = array('id' => $id);

		Log::info('Inputs', $params);
		$url = 'https://www.vdomax.com/ajax.php?t=post&a=delete&post_id='.$id."&token=".$this->api_token."&user_id=".$this->user->id."&user_pass=".$this->user->password."&timeline_id=".$this->user->id;
		$api_response = cURL::get($url);
		Log::info('Result', $api_response->toArray());

		//$response = substr($api_response->body, 10,3);
		$json = json_decode($api_response->body,true);

		if ( $json['status'] == 200 ){
			return Response::json(array('status' => '1',
                               			'response_code' => 'Delete Success'));
		}else{
			return Response::json(array('status' => '0',
                               			'response_code' => 'Delete Incomplete',
                               			'debug' => $json));
		}
	}


}
