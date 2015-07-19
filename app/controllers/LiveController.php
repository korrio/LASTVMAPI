<?php

class LiveController extends \BaseController {
	public function __construct()
	{
		$token = Request::header('X-Auth-Token');
		$this->user = json_decode(AuthToken::validate($token));
		$this->page = Input::get('page');
		$this->per_page = Input::get('per_page');
	}

	public function historyAll()
	{

		// youlove = 161
		$ids = [1527,2163,1448,14523,1301,14533];
		//,1527,2163,1926,1247,3,6,3082];
		$a = array();
		foreach($ids as $id) {
			$user = Account::find($id);

		$url = 'http://server-a.vdomax.com:8080/record/?user='.$user->username;
		$api_response = cURL::get($url);

		$response = $api_response->toArray();
		$response = $response['body'];

		//$json["sample_url"] = "http://server-a.vdomax.com:8080/record/youlove-310115_23:59:46.flv";
		$history = json_decode($response,true);

		//$data = [];
		$tmp = [];
		$i = 0;

		if($history != null)

		foreach ($history as $obj) {
			$tmp['user_id'] = $id . "";
			$tmp['username'] = $user->username;
			$tmp['avatar'] = "http://www.vdomax.com/".$user->avatar;
			$tmp['last_thumb'] = "https://www.vdomax.com/clips/rtmp/".$user->username.".png";
			$tmp['url'] = "http://server-a.vdomax.com:8080/record/".$obj['name'].".flv";
			$tmp['transcode'] = "http://server-a.vdomax.com:8080/record/transcode/".$obj['name'].".flv-480x272.mp4";
			$tmp['thumb'] = "http://server-a.vdomax.com:8080/record/".$obj['name'].".flv.png";
			$tmp['name'] = $obj['name'];
			$tmp['duration'] = $obj['duration'];
			$tmp['date'] = $obj['date'];
			
			$data[] = $tmp;
		}

		else
			$data = null;

			//$a[] = $data;

				usort($data, function($a, $b) { //Sort the array using a user defined function
		    return $a['date'] > $b['date'] ? -1 : 1; //Compare the scores
		}); 
		}

		$json['count'] = count($data);
		$json['history'] = $data;
		
		return Response::json($json);
	}

	public function historyAllFromDb()
	{

		// youlove = 161
		$ids = [1527,2163,1448,14523,1301,14533];
		//,1527,2163,1926,1247,3,6,3082];
		$a = array();
		$lives = Helpers::SK_liveHistoryAll();
		$json = array();

		$data = [];
		$tmp = [];
		$i = 0;

		foreach($lives as $obj) {
			//$user = Account::find($id);
			$username = $obj['owner'];
			//echo $username . "<br/>";

			$user = Account::where('username', $username)
                ->get()->first();

		//$url = 'http://server-a.vdomax.com:8080/record/?user='.$username;
		//$api_response = cURL::get($url);

		//$response = $api_response->toArray();
		//$response = $response['body'];

		//$json["sample_url"] = "http://server-a.vdomax.com:8080/record/youlove-310115_23:59:46.flv";
		//$history = json_decode($response,true);

		



		//if($history != null) {
			//foreach ($history as $obj) {

    if($user) {
    	$tmp['user_id'] = $user->id;
			$tmp['username'] = $username;
			$tmp['avatar'] = "http://www.vdomax.com/".$user->avatar;
			$tmp['last_thumb'] = "https://www.vdomax.com/clips/rtmp/".$username.".png";
			$tmp['url'] = "http://server-a.vdomax.com:8080/record/".$obj['file_name'].".flv";
			$tmp['transcode'] = "http://server-a.vdomax.com:8080/record/transcode/".$obj['file_name'].".flv-480x272.mp4";
			$tmp['thumb'] = "http://server-a.vdomax.com:8080/record/".$obj['file_name'].".flv.png";
			$tmp['view'] = $obj['view'];
			$tmp['love'] = $obj['love'];
			$tmp['duration']['hours'] = rand(0,1)."";
			$tmp['duration']['minutes'] = rand(10,59)."";
			$tmp['duration']['seconds'] = rand(10,59)."";
			$tmp['date'] = "1430227301";
    }
			

			/*
			"hours": "00",
"minutes": "33",
"seconds": "24.08"
			*/
			//$tmp['duration'] = $obj['duration'];
			//$tmp['date'] = $obj['date'];
			
			$data[] = $tmp;
		 //}
		//}
		} 
			
			/*
		usort($data, function($a, $b) { //Sort the array using a user defined function
		    return $a['date'] > $b['date'] ? -1 : 1; //Compare the scores
		}); 
		*/
		
		

		$json['count'] = count($data);
		$json['history'] = $data;

		//$a = Helpers::SK_liveHistoryAll();

		
		return Response::json($json);
	}

	/**
	 * Show all post regarding to user id.
	 * @return Response
	 */
	public function history($id)
	{
		//return Post::getHistory(array($id), $this->page, $this->per_page, $this->type);

		Log::info('========== Follow post ==========');
		$params = array('id' => $id);

		$user = Account::find($id);
		//$user->birthday;
		//$user->gender;
		//$user->avatar;
		//$user->cover;

		$url = 'http://server-a.vdomax.com:8080/record/?user='.$user->username;
		$api_response = cURL::get($url);

		$response = $api_response->toArray();
		$response = $response['body'];

		//$json["sample_url"] = "http://server-a.vdomax.com:8080/record/youlove-310115_23:59:46.flv";
		$history = json_decode($response,true);

		$data = [];
		$tmp = [];
		$i = 0;

		if($history != null)

		foreach ($history as $obj) {

			$liveId = Helpers::SK_getLiveHistoryId($obj['name']);

			$tmp['user_id'] = $id . "";
			$tmp['username'] = $user->username;
			$tmp['avatar'] = "http://www.vdomax.com/".$user->avatar;
			$tmp['last_thumb'] = "https://www.vdomax.com/clips/rtmp/".$user->username.".png";
			//$tmp['url'] = "http://server-a.vdomax.com:8080/record/".$obj['name'].".flv";
			$tmp['url'] = "http://stream-1.vdomax.com:1935/vod/__definst__/mp4:".$user->username."/".$user->username."_xxx_".$liveId.".mp4/playlist.m3u8";
			//$tmp['transcode'] = "http://server-a.vdomax.com:8080/record/transcode/".$obj['name'].".flv-480x272.mp4";
			$tmp['thumb'] = "http://server-a.vdomax.com:8080/record/".$obj['name'].".flv.png";
			$tmp['name'] = $obj['name'];
			$tmp['duration'] = $obj['duration'];
			$tmp['date'] = $obj['date'];


			if(isset($liveId))
				$data[] = $tmp;
		}

		else
			$data = null;


$json['count'] = count($data);
		$json['history'] = $data;
		
		return Response::json($json);
		
	}

	public function viewer($id) {
		$params = array('id' => $id);
		$user = Account::find($id);
		return Response::json(Helpers::checkViewer($user->username));
	}

	public function now() {

		return Response::json(Helpers::liveNowChannel());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

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
		//
	}


}
