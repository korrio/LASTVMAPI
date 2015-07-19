<?php

class ChatController extends \BaseController {
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
	public function groupList($id)
	{

		$url = "https://chat.vdomax.com:1313/api/chat/group/".$id;
		$response = cURL::get($url);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
																	//'users' => $account2,
                               			'groups' => $json
                               			));
		else 
			return Response::json(array('status' => '0',
																	//'users' => $account2,
                               			'groups' => $json
                               			));
		
	}

	/**
	 * Show all post regarding to user id.
	 * @return Response
	 */
	public function conversationList($id)
	{

		$url = "https://chat.vdomax.com:1313/api/chat/list/".$id;
		$response = cURL::get($url);
		
		$response = $response->body;
		$json = json_decode($response,true);
		if(count($json) != 0)
			return Response::json(array('status' => '1',
																	//'users' => $account2,
                               			'conversations' => $json
                               			));
		else 
			return Response::json(array('status' => '0',
																	//'users' => $account2,
                               			'conversations' => $json
                               			));
		
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
