<?php

class SearchController extends \BaseController {
	public function __construct()
	{
		$token = Request::header('X-Auth-Token');
		$this->user = json_decode(AuthToken::validate($token));
		$this->page = Input::get('page');
		$this->per_page = Input::get('per_page');
	}

	/**
	 * Show all post regarding to user id.
	 * @return Response
	 */
	public function search($q)
	{
		
		
	}

	

	public function social() {
		$params = Input::all();

		if(!isset($params['q'])) {
			$params['q'] = "";
		}

		if(!isset($params['sort']))
			$sort = "N";
		else
			$sort = $params['sort'];

		if(!isset($params['user_id']))
			$userId = 6;
		else
			$userId = $params['user_id'];

		if(!isset($params['page']))
			$page = 1;
		else
			$page = $params['page'];

		if(!isset($params['limit'])) {
			$params['limit'] = 20;
		}

		if(!isset($params['page']))
		$params['from'] = 0;
	else
	{
		if($page == 1)
			$params['from'] = 0;
		else
			$params['from'] = (($page-1) * $params['limit']) + 1;
	}

			$url = "https://www.vdomax.com/ajax.php?t=getSocial&mobile=1&query=".$params['q']."&from=".$params['from']."&limit=".$params['limit']."&sort=".$sort."&user_id=".$userId;
		$response = cURL::get($url,$params);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'user_id' => $userId,
																		'sort' => $sort,
                               			'users' => $json
                               			));
		else 
			return Response::json(array('status' => '0',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'user_id' => $userId,
																		'sort' => $sort,
                               			'users' => $json
                               			));
	}

	public function channel() {
		$params = Input::all();

		if(!isset($params['user_id']))
			$userId = 6;
		else
			$userId = $params['user_id'];

		if(!isset($params['q'])) {
			$params['q'] = "";
		}

		if(!isset($params['sort']))
			$sort = "&sort=N";
		else
			$sort = "&sort=".$params['sort'];

		if(!isset($params['page']))
			$page = 1;
		else
			$page = $params['page'];

		if(!isset($params['limit'])) {
			$params['limit'] = 20;
		}

  if(!isset($params['page']))
		$params['from'] = 0;
	else
	{
		if($page == 1)
			$params['from'] = 0;
		else
			$params['from'] = (($page-1) * $params['limit']) + 1;
	}
		

			$url = "https://www.vdomax.com/ajax.php?t=getChannel&mobile=1&query=".$params['q']."&from=".$params['from']."&limit=".$params['limit'].$sort."&user_id=".$userId;
		$response = cURL::get($url,$params);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'sort' => $sort,
																		'debug' => $url,
                               			'channels' => $json
                               			));
		else 
			return Response::json(array('status' => '0',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'sort' => $sort,
                               			'channels' => $json,
                               			'debug' => $url
                               			));
	}

	public function photo() {
		$params = Input::all();

		if(!isset($params['user_id']))
			$userId = 6;
		else
			$userId = $params['user_id'];

		if(!isset($params['q'])) {
			$params['q'] = "";
		}

		if(!isset($params['sort']))
			$sort = "N";
		else
			$sort = $params['sort'];

		if(!isset($params['page']))
			$page = 1;
		else
			$page = $params['page'];

		if(!isset($params['limit'])) {
			$params['limit'] = 20;
		}

		if(!isset($params['page']))
		$params['from'] = 0;
	else
	{
		if($page == 1)
			$params['from'] = 0;
		else
			$params['from'] = (($page-1) * $params['limit']) + 1;
	}

			$url = "https://www.vdomax.com/ajax.php?t=getPhoto&mobile=1&query=".$params['q']."&from=".$params['from']."&limit=".$params['limit']."&sort=".$sort."&user_id=".$userId;
		$response = cURL::get($url,$params);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'sort' => $sort,
																		'debug' => $url,
                               			'photos' => $json

                               			));
		else 
			return Response::json(array('status' => '0',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'sort' => $sort,
                               			'photos' => $json,
                               			'debug' => $url
                               			));
	}

	public function video() {
		$params = Input::all();

		if(isset($params['user_id']))
			$user_id = "&user_id=" . $params['user_id'];
		else
			$user_id = "";

		if(!isset($params['q'])) {
			$params['q'] = "";
		}

		if(!isset($params['sort']))
			$sort = "N";
		else
			$sort = $params['sort'];

		if(!isset($params['page']))
			$page = 1;
		else
			$page = $params['page'];

		if(!isset($params['limit'])) {
			$params['limit'] = 20;
		}

		if(!isset($params['page']))
		$params['from'] = 0;
	else
	{
		if($page == 1)
			$params['from'] = 0;
		else
			$params['from'] = (($page-1) * $params['limit']) + 1;
	}
			

			$url = "https://www.vdomax.com/ajax.php?t=getVideo&mobile=1&query=".$params['q']."&from=".$params['from']."&limit=".$params['limit']."&sort=".$sort.$user_id;
		$response = cURL::get($url,$params);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'sort' => $sort,
																		'debug' => $url,
                               			'videos' => $json
                               			));
		else 
			return Response::json(array('status' => '0',
				'page' => (int) $page,
				'per_page' => (int) $params['limit'],
				'pages' => ceil (count($json) / $params['limit']), 
																		'total' => count($json),
																		'sort' => $sort,
                               			'videos' => $json,
                               			'debug' => $url
                               			));
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
	public function show()
	{

		$params = Input::all();
		if(isset($params['q']))
			$q = $params['q'];

		$res = Helpers::SK_getSearchMobile($q);


		//return Post::getHistory(array($id), $this->page, $this->per_page, $this->type);

		//Log::info('========== Follow post ==========');
		//$params = array('id' => $id);

		//$user = Account::find($id);

/*
		$url = 'https://www.vdomax.com/ajax.php?t=search&a=mobile&q='.$q;
		$api_response = cURL::get($url);

		$response = $api_response->toArray();
		$response = $response['body'];

		//$json["sample_url"] = "http://server-a.vdomax.com:8080/record/youlove-310115_23:59:46.flv";
		$search_result = json_decode($response,true);

		if($search_result['status'] == 200) {
			$res['status'] = 1;
		$res['result'] = $search_result['result'];
	} else {
		$res['status'] = 0;
		$res['result'] = null;
	}
	*/
		

		
		return Response::json($res);
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
