<?php

class Media extends Eloquent{
	/**
	* The database table used by the model.
	*
	* @var string
	*/
	protected $table = 'media';

	public function getThumbnailAttribute(){
		return $this->attributes['thumbnail'] = 'https://www.vdomax.com/clips/thumbs/media_clip_'.$this->attributes['id'].'.jpeg';
	}

	public static function getAlbum($album_id){
		$photos = Media::where('album_id', $album_id)
			->get(array('id','album_id','url','extension'))->take(9);
		
		foreach($photos as $k => $v ) {
			$v['url_thumb'] = "imgd.php?src=".$v['url'].".".$v['extension']."&width=600&height=800";
			
			$v['url'] = $v['url'] . "." . $v['extension'];
			$return[] = $v;
		}

		return $return;
	}

	public static function getAlbumFirst($album_id){
		$photos = Media::where('album_id', $album_id)
			->get(array('id','album_id','url','extension'))->first();
		
		return "imgd.php?src=".$photos->url.".".$photos->extension ;
	}

}