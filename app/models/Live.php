<?php

class Live extends Eloquent{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'users';
    //get user timeline
    public static function getHistory($user_id, $page, $per_page){
    	$content = array();
    	$ofset = 0;

    	if ( is_null($page) || empty($page)){
    		$page = 1;
    	}

    	if ( is_null($per_page) || empty($per_page)  ){
    		$per_page = 20;
    	}

    	if ( $page != 1){
    		$ofset = (($page-1)*$per_page);
    	}

    	$content = array(array("id"=>1,"name"=>"youlove-310115_23:59:46","date"=>1422729836,"duration"=>array("hours":"01","minutes":"51","seconds":"20.14")));
    	$total_post = 1;
		$response = array('status' => '1',
						'page' => $page,
						'per_page' => $per_page,
						'pages' => ceil($total_post/$per_page),
						'total' => $total_post,
						'lives' => $content);

		//return Response::json($response);
		return $response;
    }

    


}
