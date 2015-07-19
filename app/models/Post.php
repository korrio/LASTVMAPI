<?php

//use Elasticquent\ElasticquentTrait;

class Post extends Eloquent{

	//use ElasticquentTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	public function Media()
    {
        return $this->hasOne('Media', 'id', 'media_id');
    }

    public function Clip()
    {
        return $this->hasOne('Media', 'id', 'clip_id');
    }

    public function Account()
    {
        return $this->hasOne('Account', 'id', 'timeline_id');
    }

    //get user timeline
    public static function getPost($user_id, $page, $per_page, $type,$sort='DEFAULT'){
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

    	if($sort == "DEFAULT")  {

    		$posts	= Post::whereIn('timeline_id', $user_id)
					->with(array('account'=>function($query){
						$query->select('id','username','name','avatar_id');
					}),'media', 'clip')
					->where('type2', 'none')
					->where('type1', 'story')
					->take($per_page)
					->skip($ofset)
					->ofType($type)
					->orderBy('time', 'DESC')
					->get();
    	}
    	//get all posts accoirding to user' input criteria
    	
			elseif($sort == "N") 
				$posts	= Post::with('media', 'clip')
					->where('type2', 'none')
					->where('type1', 'story')
					->take($per_page)
					->skip($ofset)
					->ofType($type)
					->orderBy('time', 'DESC')
					->get();


			elseif($sort == "V")
				$posts	= Post::with('media', 'clip')
					->where('type2', 'none')
					->where('type1', 'story')
					->take($per_page)
					->skip($ofset)
					->ofType($type)
					->orderBy('view', 'DESC')
					->get();
			elseif($sort == "L")
				$posts	= Post::whereIn('timeline_id', $user_id)
					->with(array('account'=>function($query){
						$query->select('id','username','name','avatar_id');
					}),'media', 'clip')
					->where('type2', 'none')
					->where('type1', 'story')
					->take($per_page)
					->skip($ofset)
					->ofType($type)
					->orderBy('time', 'DESC')
					->get();
			elseif($sort == "SEARCH") {
				$postId = $user_id;
				$posts = Post::with(array('account'=>function($query){
                        $query->select('id','username','name','avatar_id');
                    }),'media', 'clip')
                    ->where('id',$postId)
                    ->where('type2', 'none')
                    ->where('type1', 'story')
                    //->take($per_page)
                    //->skip($ofset)
                    //->ofType($type)
                    ->orderBy('time', 'DESC')
                    ->get()
                    ;


					//$posts->addToIndex();
			}

		if($sort == "DEFAULT")
		//count all posts
		$total_post = Post::whereIn('timeline_id', $user_id)
					->where('type2', 'none')
					->where('type1', 'story')
					//->orWhere('type2', 'share')
					->ofType($type)
					->orderBy('time', 'DESC')
					->count();
		else
			$total_post = Post::where('type2', 'none')
					->where('type1', 'story')
					//->orWhere('type2', 'share')
					->ofType($type)
					->orderBy('time', 'DESC')
					->count();

		foreach ( $posts as $post){
			$data = array();
			$data['id'] = $post->id;
			$data['active'] = $post->active;

			$post->account->avatar;
			//$post->account->cover;

			$user = Account::find($post->account->id);

							$user->avatar;
							$user->cover;

			$data['author'] = $user;
			//$data['author'] = $post->account;
			$data['author']['live_cover'] = "https://www.vdomax.com/clips/imgd.php?src=rtmp/".$post->account->username.".png&width=800&height=600&crop-to-fit";
			$data['author']['live'] = "http://150.107.31.13:1935/live/".$post->account->username."/playlist.m3u8";

			if ( $post->google_map_name != ""){
				$data['google_map_name'] = $post->google_map_name;
			}else{
				$data['google_map_name'] = null;
			}

			$data['hidden'] = $post->hidden;

			if ( $post->link_title != ""){
				$data['link_title'] = $post->link_title;
			}else{
				$data['link_title'] = null;
			}

			if ( $post->link_url != ""){
				$data['link_url'] = $post->link_url;
			}

			$data['post_id'] = $post->post_id;

			if ( $post->recipient_id != ""){
				$data['recipient_id'] = $post->recipient_id;
			}else{
				$data['recipient_id'] = null;
			}

			$data['seen'] = $post->seen;

			$tattoo_src = "";
			
			if ( $post->text != ""){
				$data['text'] = Helpers::SK_getMarkup($post->text);
				$data['emoticonized'] = Helpers::SK_emoticonize($post->text,$post->account->id);

				$html = $data['emoticonized'];

				preg_match( '@src="([^"]+)"@' , $html, $match );

				$tattoo_src = array_pop($match);

				$data['tattoo_url'] = $tattoo_src;
			}else{
				$data['text'] = null;
				$data['emoticonized'] = null;
				$data['tattoo_url'] = null;
			}



			$data['time'] = $post->time;
			$data['timeline_id'] = $post->timeline_id;
			$data['timestamp'] = $post->timestamp;
			$data['type1'] = $post->type1;
			$data['type2'] = $post->type2;
			$data['view'] = $post->view;

			$followers = Post::getFollower($user_id,$post->id);
			$lovers = Post::getLovers($post->id);
			$comments = Post::getComment($post->id);
			$share = Post::getShare($post->id);

			if (empty($followers)){
				$data['follow_count'] = 0;
				$data['follow'] = null;
			}else{
				$data['follow_count'] = count($followers);
				$data['follow'] = $followers;
			}

			if (empty($lovers)){
				$data['love_count'] = 0;
				$data['love'] = null;
			}else{
				$data['love_count'] = count($lovers);
				$data['love'] = $lovers;
			}

			if (empty($comments)){
				$data['comment_count'] = 0;
				$data['comment'] = null;
			}else{
				$data['comment_count'] = count($comments);
				$data['comment'] = $comments;
			}

			if (empty($share)){
				$data['share_count'] = 0;
				$data['share'] = null;
			}else{
				$data['share_count'] = count($share);
				$data['share'] = $share;
			}

$data['post_type'] = "text";

			// assign media type.
			if ( $post->media_id != 0 ){
				$data['post_type'] = "photo";
			}

			if ( $post->clip_id != 0 ){
					$data['post_type'] = "clip";
			}

			if($post->google_map_name != "") {
				$data['post_type'] = "map";
				$data['text'] = $post->google_map_name;
			}

			if ( $post->soundcloud_uri != "" ){
				$data['post_type'] = "soundcloud";
			}
			
			if ( $post->youtube_video_id != "" ){
				$data['post_type'] = "youtube";
			} 

			if($data['tattoo_url'] != ""){
				$data['post_type'] = "tattoo";
				$data['tattoo']['tattoo_url'] = $tattoo_src;
				$data['tattoo']['tattoo_code'] = $data['text'];
			}

			if($post->clip != null && $post->clip->type == "livestreaming") {
				$data['post_type'] = "live";
			} 

			if ($post->media != ""){


				$data['media'] = $post->media;

				$data['media']['url_thumb'] = "imgd.php?src=".$post->media->url.".".$post->media->extension."&width=600&height=800";

				$data['media']['url'] = $post->media->url . "." . $post->media->extension;
				

				if($post->media->type == "album") {
					$data['media']['album_photos'] = Media::getAlbum($post->media->id);
					$data['media']['url'] = Media::getAlbumFirst($post->media->id);
				} else {
					$data['media']['album_photos'] = array(0 => array("url_thumb" => "imgd.php?src=".$post->media->url.".".$post->media->extension."&width=600&height=800","url" => $post->media->url));
				}
				

				
			}else{
				$data['media'] = null;
			}

			if ($post->clip != ""){
				$obj = $post->clip;

				if($post->clip->type == "ppv") {
					$post->clip->url = $obj->url;
				$post->clip->thumbnail;
				$data['clip'] = $post->clip;
				} else {
					$post->clip->url = $obj->url ;
				$post->clip->thumbnail;
				$data['clip'] = $post->clip;
				}
				//$post->clip->url = "https://www.vdomax.com/".$obj->url."_ori.".$obj->extension ;
				
			}else{
				$data['clip'] = null;
			}

			if ($post->soundcloud_uri != ""){

				$ax = explode("/tracks/",$post->soundcloud_uri . "");

				$track_api_url = "http://api.soundcloud.com/tracks/".$ax[1]."?client_id=954240dcf4b7c0a46e59369a69db7215";

				//$api_response = cURL::get($track_api_url);
				
				//$json = json_decode($api_response->body,1);

				//var_dump($json["kind"]) ;

				//http://api.soundcloud.com/tracks/140000410?client_id=954240dcf4b7c0a46e59369a69db7215
				$data['soundcloud'] = array('title' => $post->soundcloud_title,
											'uri' => $post->soundcloud_uri,
											'track_id' => $ax[1],
											'track_api_url' => $track_api_url,
											//'track_artwork'=> $json["artwork_url"],
											'stream_url' => "https://api.soundcloud.com/tracks/".$ax[1]."/stream",
											'json_response' => ""
											);
			}else{
				$data['soundcloud'] = null;
			}

			if ($post->youtube_video_id != ""){
				$data['youtube'] = array('id' => $post->youtube_video_id,
										'title' => $post->youtube_title,
										'description' => $post->youtube_description,
										'thumbnail' => 'http://img.youtube.com/vi/'.$post->youtube_video_id.'/0.jpg');
			}else{
				$data['youtube'] = null;
			}

			$data['is_loved'] = Helpers::SK_isPostLoved($post->id,intval($user_id[0]));
			$data['is_commented'] = Helpers::SK_isPostCommented($post->id,intval($user_id[0]));
			$data['is_shared'] = Helpers::SK_isPostShared($post->id,intval($user_id[0]));

			//append new post to response and clear the array
			if($data['media'] != null) {
				if($post->media->album_id != "0" && $post->media->type == "photo") {

				} else {
					array_push($content, $data);
				}
			} elseif($data['text'] == null && $data['post_type'] == "text") {
				
			} else {
				array_push($content, $data);
			}
			
			unset($data);
		}

		$user = Account::find($user_id);

							$user[0]->avatar;
							$user[0]->cover;
							$user[0]->birthday;
							$user[0]->gender;

							$user[0]->is_live = Helpers::isLive($user[0]->username);
							$user[0]->online = false;
		$user[0]->live = "http://150.107.31.13:1935/live/".$user[0]->username."/playlist.m3u8";

		




		$count = array("post" => Helpers::SK_countPosts($user[0]->id),
									 "follower" => Helpers::SK_countFollowers($user[0]->id),
									 "following" => Helpers::SK_countFollowing($user[0]->id),
									 "friend" => Helpers::SK_countFriends($user[0]->id),
									 "love" => Helpers::SK_countPageLikes($user[0]->id),
									 "group" => Helpers::SK_countGroupJoined($user[0]->id),
									 //"follow_request" => Helpers::SK_countGroupJoined($id)
									 );

		$response = array('status' => '1',
						//'whereIn' => $user_id,
						'page' => intval($page),
						'per_page' => intval($per_page),
						'pages' => intval(ceil($total_post/$per_page)),
						'total' => count($content),
						'offset' => $ofset,
						'sort' => $sort,
						'user' => $user[0],
						'count' => $count,
						'posts' => $content);

		//return Response::json($response);
		return $response;
    }

    //get all liker for each post
    public static function getLovers($post_id)
    {	
    	$posts = Post::where('post_id', $post_id)
			->with(array('account'=>function($query){
						$query->select('id','name','avatar_id');
					}))
			->where('type2', 'like')
			->get();
    	$liker = array();

    	foreach ($posts as $post){
				$user = Account::find($post->account->id);
				$user->avatar;
				$user->cover;
    		array_push($liker, $user);
    	}

        return $liker;
    }


    //get all followers for each post
    public static function getFollower($user_id, $post_id)
    {	
    	$posts = Post::where('post_id', $post_id)
    		->with(array('account'=>function($query){
						$query->select('id','name','avatar_id');
					}))
			->where('timeline_id','!=', $user_id)
			->where('type2', 'follow')
			->get();
    	$follower = array();

    	foreach ($posts as $post){
    		//$account = Account::where('id', $post->timeline_id)->get();
    		$user = Account::find($post->account->id);
				$user->avatar;
				$user->cover;
    		array_push($follower, $user);
    	}

    	return $follower;
    	
    }

    //get all comments for each post
    public static function getComment($post_id)
    {	
    	$posts = Post::where('post_id', $post_id)
			->with(array('account'=>function($query){
						$query->select('id','name','avatar_id');
					}))
			->where('type2', 'comment')
			->get();
    	$comments = array();

    	foreach ($posts as $post){
    		//$account = Account::where('id', $post->timeline_id)->get();
    		$post->account->avatar;
    		$lovers = Post::getLovers($post->id);
    		$comment['id'] = $post->id;
    		$comment['text'] = $post->text;
				$comment['emoticonized'] = Helpers::SK_emoticonize($post->text,$post->account->id);
				$html = $comment['emoticonized'];

				preg_match( '@src="([^"]+)"@' , $html, $match );

				$tattoo_src = array_pop($match);

				$data['tattoo_url'] = $tattoo_src;

				$comment['time'] = $post->time;
			$comment['timestamp'] = $post->timestamp;
			$comment['user'] = $post->account;
			$comment['love_count'] = count($lovers);
			$comment['love'] = $lovers;
    		array_push($comments, $comment);
    		unset($comment);
    	}
    	return $comments;
    }

        //get all liker for each post
    public static function getShare($post_id)
    {	
    	$posts = Post::where('post_id', $post_id)
			->with(array('account'=>function($query){
						$query->select('id','name','avatar_id');
					}))
			->where('type2', 'share')
			->get();
    	$share = array();

    	foreach ($posts as $post){
    		//$account = Account::where('id', $post->timeline_id)->get();
    		$user = Account::find($post->account->id);
				$user->avatar;
				$user->cover;
    		array_push($share, $user);
    	}

        return $share;
    }




    public function scopeOfType($query, $type)
    {
    	switch ($type) {
    	case 'live':
    	//if($post->clip != null && $post->clip->type == "livestreaming") {
    		return $query->where('text','like','%กำลังถ่ายทอดสด%')->orWhere('youtube_title', 'like', '%streaming%');
    		break;
    	case 'place':
    		return $query->where('google_map_name', '!=', '')
    					->where('media_id', '=', '0')
							 ->where('clip_id', '=', '')
							 ->where('soundcloud_uri', '=', '')
							 ->where('youtube_video_id', '=', '')
							 ->where('text','not like','%กำลังถ่ายทอดสด%')
							 ->where('text', 'not like', ':%:');
    		break;
    	case 'tattoo':
				return $query->where('text', 'like', ':%:');
				break;
			case 'map':
				return $query->where('google_map_name', '!=', '');
				break;
			case 'photo':
				return $query->where('media_id', '!=', '0');
				break;
			case 'clip':
				return $query->where('clip_id', '!=', '');
				break;
			case 'soundcloud':
				return $query->where('soundcloud_uri', '!=', '');
				break;
			case 'youtube':
				return $query->where('youtube_video_id', '!=', '');
				break;
			case 'video':
				return $query->where('youtube_video_id', '!=', '')
							 ->orWhere('clip_id', '!=', '');
							 break;
			case 'text':
				return $query->where('google_map_name', '=', '')
							 ->where('media_id', '=', '0')
							 ->where('clip_id', '=', '')
							 ->where('soundcloud_uri', '=', '')
							 ->where('youtube_video_id', '=', '')
							 ->where('text','not like','%กำลังถ่ายทอดสด%')
							 ->where('text', 'not like', ':%:');
				break;
			default:
				return $query;
				break;
    	}
    }


}
