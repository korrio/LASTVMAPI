<?php

define('DB_ACCOUNTS_API', 'accounts');
define('DB_BLOCKERS_API', 'blocks');
define('DB_FOLLOWERS_API', 'followers');
define('DB_HASHTAGS_API', 'hashtags');
define('DB_NOTIFICATIONS_API', 'notifications');
define('DB_MEDIA_API', 'media');
define('DB_POSTS_API', 'posts');
define('DB_USERS_API', 'users');
define('DB_PAGES_API', 'pages');
define('DB_PAGE_ADMINS_API', 'page_admins');
define('DB_PAGE_CATEGORIES_API', 'page_categories');
define('DB_GROUPS_API', 'groups');
define('DB_GROUP_ADMINS_API', 'group_admins');
define('DB_CONFIGURATIONS_API', 'configurations');
define('DB_REPORTS_API', 'reports');
define('DB_GROUPCHAT_API', 'groupchat');
define('DB_LIVEHISTORY_API', 'live_history');
define('DB_CLIPCONV', 'clip_convert');


class Helpers {

public static function dbConnect() {
	// MySQL Hostname / Server (for eg: 'localhost')
		$sql_host = '150.107.31.15';
		$sql_port = '3306';

		// MySQL Database Name
		$sql_name = 'vmmax';


		// MySQL Database User
		$sql_user = 'root';


		// MySQL Database Password
		$sql_pass = 'Nchl33&rVx';

		$dbConnect = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_name,$sql_port);
		mysqli_set_charset($dbConnect,"utf8");
		return $dbConnect;
}
    
// not completed
public static function SK_notification() {

    $query_one = "SELECT id FROM " . DB_NOTIFICATIONS . " WHERE timeline_id=" . $recipient['id'] . " AND post_id=" . $data['post_id'] . " AND type='$type' AND active=1";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    
    if (mysqli_num_rows($sql_query_one) > 0) {
        $query_two = "DELETE FROM " . DB_NOTIFICATIONS . " WHERE timeline_id=" . $recipient['id'] . " AND post_id=" . $data['post_id'] . " AND type='$type' AND active=1";
        $sql_query_two = mysqli_query($dbConnect, $query_two);
    }
    
    if (!isset($data['undo']) or $data['undo'] != true) {
        $query_three = "INSERT INTO " . DB_NOTIFICATIONS . " (timeline_id,active,notifier_id,post_id,text,time,type,url) VALUES (" . $recipient['id'] . ",1," . $notifier['id'] . "," . $data['post_id'] . ",'$text'," . time() . ",'$type','$url')";
        $sql_query_three = mysqli_query($dbConnect, $query_three);
        
        if ($sql_query_three) {
            return true;
        }
    }
}

// not completed
public static function SK_registerChatUploadPhoto($data) {

    

        
        if (count($data['files']['name']) == 1) {
            $photo_param = array(
                'tmp_name' => $data['files']['tmp_name'][0],
                'name' => $data['files']['name'][0],
                'size' => $data['files']['size'][0],
                'mobile_api' => $data['mobile_api']
            );
            $photo_data = Helpers::SK_registerMedia($photo_param);

            // heyhey
            //print_r($photo_data);
            //print_r($photo_param);
            
            if (isset($photo_data['id'])) {
                return $photo_data;
            }
        } 

        // for albums upload
        // else {
        //     $query_one = "INSERT INTO " . DB_MEDIA_API . " (timeline_id,active,name,type) VALUES (" . $timeline['id'] . ",1,'temp_" . Helpers::SK_generateKey() . "','album')";
        //     $sql_query_one = mysqli_query($dbConnect, $query_one);
            
        //     if ($sql_query_one) {
        //         $media_id = mysqli_insert_id($dbConnect);
                
        //         for ($i = 0; $i < count($data['photos']['name']); $i++) {
        //             $photo_param = array(
        //                 'tmp_name' => $data['photos']['tmp_name'][$i],
        //                 'name' => $data['photos']['name'][$i],
        //                 'size' => $data['photos']['size'][$i]
        //             );
        //             $photo_data = Helpers::SK_registerMedia($photo_param,$dataInput, $media_id);
                    
        //             // if (!empty($photo_data['id'])) {
        //             //     $query_one = "INSERT INTO " . DB_POSTS . " (active,google_map_name,hidden,media_id,time,timeline_id,recipient_id,type1,type2) VALUES (1,'$google_map_name',1," . $photo_data['id'] . "," . time() . "," . $timeline['id'] . ",$recipient_id,'$type1','$type2')";
        //             //     $sql_query_one = mysqli_query($dbConnect, $query_one);
                        
        //             //     if ($sql_query_one) {
        //             //         $media_story_id = mysqli_insert_id($dbConnect);
                            
        //             //         //mysqli_query($dbConnect, "UPDATE " . DB_POSTS_API . " SET post_id=id WHERE id=$media_story_id");
                            
        //             //         //mysqli_query($dbConnect, "UPDATE " . DB_MEDIA_API . " SET post_id=$media_story_id WHERE id=" . $photo_data['id']);
        //             //     }
        //             // }
        //         }
                
        //         $other_media = true;
        //         $post_ability = true;
        //     }
        // }
    
    
}

public static function SK_registerChatUploadClip($data) {
    if (isset($data['files']['name'])) {
        
        if (count($data['files']['name']) == 1) {
            //print $data['files']['size'][0];
            $clip_param = array(
                'tmp_name' => $data['files']['tmp_name'][0],
                'name' => $data['files']['name'][0],
                'size' => $data['files']['size'][0],
                'mobile_api' => $data['mobile_api']
            );



            $clip_data = Helpers::SK_registerMediaClip($clip_param);
            
            if (isset($clip_data['id'])) {
                return $clip_data;
            }
        } 

        // for album uploads
        // else {
        //     $query_one = "INSERT INTO " . DB_MEDIA_API . " (timeline_id,active,name,type) VALUES (" . $timeline['id'] . ",1,'temp_" . Helpers::SK_generateKey() . "','clip')";
        //     $sql_query_one = mysqli_query($dbConnect, $query_one);
            
        //     if ($sql_query_one) {
        //         $clip_id = mysqli_insert_id($dbConnect);
                
        //         for ($i = 0; $i < count($data['clips']['name']); $i++) {
        //             $clip_param = array(
        //                 'tmp_name' => $data['clips']['tmp_name'][$i],
        //                 'name' => $data['clips']['name'][$i],
        //                 'size' => $data['clips']['size'][$i]
        //             );
        //             $clip_data = Helpers::SK_registerMediaClip($clip_param, $clip_id);
                    
        //             // if (!empty($clip_data['id'])) {
        //             //     $query_one = "INSERT INTO " . DB_POSTS . " (active,google_map_name,hidden,media_id,clip_id,time,timeline_id,recipient_id,type1,type2) VALUES (1,'$google_map_name',1,0," . $clip_data['id'] . "," . time() . "," . $timeline['id'] . ",$recipient_id,'$type1','$type2')";
        //             //     $sql_query_one = mysqli_query($dbConnect, $query_one);
                        
        //             //     if ($sql_query_one) {
        //             //         $media_story_id = mysqli_insert_id($dbConnect);
                            
        //             //         //mysqli_query($dbConnect, "UPDATE " . DB_POSTS . " SET post_id=id WHERE id=$media_story_id");
                            
        //             //         mysqli_query($dbConnect, "UPDATE " . DB_MEDIA . " SET post_id=$media_story_id WHERE id=" . $clip_data['id']);
        //             //     }
        //             // }
        //         }
                
        //         $other_media = true;
        //         $post_ability = true;
        //     }
        // }
}
}

// not completed
public static function SK_registerMedia($upload, $album_id=0) {

    global $dbConnect;
    $dbConnect = Helpers::dbConnect();
    set_time_limit(0);

    $photo_dir = 'photos/' . date('Y') . '/' . date('m');

    //if(!isset($upload['mobile_api'])) {
    
        if (!file_exists('photos/' . date('Y'))) {
            //echo "chmod here";
            mkdir('photos/' . date('Y'), 0777, true);
            chmod('photos/' . date('Y'), 0777);
        }
        
        if (!file_exists('photos/' . date('Y') . '/' . date('m'))) {
            //echo "chmod here2";
            mkdir('photos/' . date('Y') . '/' . date('m'), 0777, true);
            chmod('photos/' . date('Y') . '/' . date('m'), 0777);
        }


    //} 

    if (is_uploaded_file($upload['tmp_name']) || $upload['mobile_api']) {
        $upload['name'] = Helpers::SK_secureEncode($upload['name']);
        $name = preg_replace('/([^A-Za-z0-9_\-\.]+)/i', '', $upload['name']);
        $ext = strtolower(substr($upload['name'], strrpos($upload['name'], '.') + 1, strlen($upload['name']) - strrpos($upload['name'], '.')));
        
        if ($upload['size'] > 1024) {
            
            if (preg_match('/(jpg|jpeg|png)/', $ext)) {
                
                list($width, $height) = getimagesize($upload['tmp_name']);
                
                $query_one = "INSERT INTO " . DB_MEDIA_API . " (extension,name,type) VALUES ('$ext','$name','photo')";
                $sql_query_one = mysqli_query($dbConnect, $query_one);
                
                if ($sql_query_one) {
                    $sql_id = mysqli_insert_id($dbConnect);
                    $original_file_name = $photo_dir . '/' . Helpers::SK_generateKey() . '_' . $sql_id . '_' . md5($sql_id);
                    $original_file = $original_file_name . '.' . $ext;
                    
                    //$upload_success = $fileObj->move($original_file, $upload['tmp_name']);
                    //if($upload_success) {
                    $cmd = "mv /var/www/html/public/" . $upload['tmp_name'] . " /var/www/html/public/" . $original_file;
                    exec($cmd);
                    if (@move_uploaded_file($upload['tmp_name'], $original_file) || $upload['mobile_api']) {
                        //echo $upload['tmp_name'];
                        //echo " #### ";
                        //echo $original_file;

                        $min_size = $width;
                        $wall = floor($width);
                        $chat = 480;
                        $cover = floor($width);
                        
                        if ($width > $height) {
                            $min_size = $height;
                        }
                        
                        $min_size = floor($min_size);                       
                        
                        if ($min_size > 1600) {
                            $min_size = 1600;
                            $wall = 800;
                            $cover = 1600;
                        }elseif($width <= 1600 && $width >= 800){
                            $wall = 800;
                        }
                        
                        $imageSizes = array(
                            'thumb' => array(
                                'type' => 'resize',
                                'width' => $chat,
                                'height' => 0,
                                'name' => $original_file_name . '_thumb'
                            ),
                            '100x100' => array(
                                'type' => 'crop',
                                'width' => 170,
                                'height' => 170,
                                'name' => $original_file_name . '_100x100'
                            ),
                            '100x75' => array(
                                'type' => 'crop',
                                'width' => 100,
                                'height' => 75,
                                'name' => $original_file_name . '_100x75'
                            ),

                            'wall' => array(
                                'type' => 'resize',
                                'width' => $wall,
                                'height' => 0,
                                'name' => $original_file_name . '_wall'
                            ),
                            
                        );
                        
                        foreach ($imageSizes as $ratio => $data) {
                            $save_file = $data['name'] . '.' . $ext;
                            Helpers::SK_processMedia($data['type'], $original_file, $save_file, $data['width'], $data['height']);
                        }

                        //heyhey
                                               
                        Helpers::SK_processMedia('resize', $original_file, $original_file, $cover, 0);                        
                        mysqli_query($dbConnect, "UPDATE " . DB_MEDIA_API . " SET album_id=$album_id,url='$original_file_name',temp=0,active=1 WHERE id=$sql_id");
                        //SK_createCover($sql_id);
                        $get = array(
                            'id' => $sql_id,
                            'active' => 1,
                            'extension' => $ext,
                            'name' => $name,
                            'url' => $original_file_name,
                            'path' => $original_file_name . "." . $ext,
                            'full_path' => "http://chat.vdomax.com/" . $original_file_name . "." . $ext,
                            'thumb' => "http://chat.vdomax.com/" . $original_file_name . "_thumb." . $ext,
                        );
                        //print_r($get);
                        return $get;
                    } else {
                        return "debug";
                    }
                }
            } else {
                return "debug";
            }
        }
    } 
}

public static function SK_registerMediaClip($upload, $clip_id=0) {

    global $dbConnect;
    $dbConnect = Helpers::dbConnect();
    set_time_limit(0);
    
    if (!file_exists('clips/' . date('Y'))) {
        mkdir('clips/' . date('Y'), 0777, true);
    }
    
    if (!file_exists('clips/' . date('Y') . '/' . date('m'))) {
        mkdir('clips/' . date('Y') . '/' . date('m'), 0777, true);
    }
    
    $clip_dir = 'clips/' . date('Y') . '/' . date('m');

    
    
    if (is_uploaded_file($upload['tmp_name']) || $upload['mobile_api']) {
        $upload['name'] = Helpers::SK_secureEncode($upload['name']);
        $name = preg_replace('/([^A-Za-z0-9_\-\.]+)/i', '', $upload['name']);
        $ext = strtolower(substr($upload['name'], strrpos($upload['name'], '.') + 1, strlen($upload['name']) - strrpos($upload['name'], '.')));
        
        //print "aaa<br/>";
        //print_r($upload);


        if ($upload['size'] > 1024) {
            
            if (preg_match('/(mp4|flv|3gp|avi|mpg|webm|mov)/', $ext)) { 
                $query_one = "INSERT INTO " . DB_MEDIA_API . " (extension,name,type) VALUES ('$ext','$name','clip')";
                $sql_query_one = mysqli_query($dbConnect, $query_one);
                
                if ($sql_query_one) {
                    $sql_id = mysqli_insert_id($dbConnect);
                    $fn = Helpers::SK_generateKey() . '_' . $sql_id . '_' . md5($sql_id);
                    $original_file_name = $clip_dir . '/' . $fn;
                    $original_file = $original_file_name . '.' . $ext;


                    $cmd = "mv /var/www/html/public/" . $upload['tmp_name'] . " /var/www/html/public/" . $original_file;
                    exec($cmd);


                    if (@move_uploaded_file($upload['tmp_name'], $original_file) || $upload['mobile_api']) {  

                        echo "string";                     
                        if (!file_exists('clips/thumbs')) {
                            mkdir('clips/thumbs', 0777, true);
                        }
                        $clip_thumb_dir = 'clips/thumbs';
                        $cmd = "ffmpeg -i /var/www/html/public/" . $original_file ." -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg /var/www/html/public/".$clip_thumb_dir."/media_clip_".$sql_id.".jpg 2>&1";
                        //$cmd = "&& /usr/bin/ffmpeg -i /home/vdomax/" . $original_file ." -c:v libx264 -b:v 1000k /home/vdomax/" . $original_file_name .".".$ext." 2>&1";
                        //$cmd = "&& rm /home/vdomax/" . $original_file;
                        exec($cmd);


                        
                        mysqli_query($dbConnect, "UPDATE " . DB_MEDIA_API . " SET clip_id=$clip_id,url='$original_file_name',temp=0,active=1 WHERE id=$sql_id");                       
                        $q = "INSERT INTO ". DB_CLIPCONV ." (clip_id,source,filename) VALUES ('".$sql_id."','".$original_file_name."','".$fn.".".$ext."')";
                        exec("echo \"".$q."\" > /var/www/html/public/log.txt");
                        $resp = mysqli_query($dbConnect, $q);
                        $get = array(
                           // 'cmd' => $cmd,
                            'id' => $sql_id,
                            'active' => 1,
                            'extension' => $ext,
                            'name' => $name,
                            'url' => $original_file_name,
                            'path' => $original_file_name . "." . $ext,
                            'full_path' => "http://chat.vdomax.com/" . $original_file_name . "." . $ext,
                            'thumb' => "http://chat.vdomax.com/" . $clip_thumb_dir."/media_clip_".$sql_id.".jpg"
                        );
                        
                        return $get;
                    }
                }
            }
        }
    }
}

public static function SK_processMedia($run, $photo_src, $save_src, $width=0, $height=0) {
    
//echo $photo_src;

    if (file_exists($photo_src)) {


        
        if (strrpos($photo_src, '.')) {
            $ext = substr($photo_src, strrpos($photo_src,'.') + 1, strlen($photo_src) - strrpos($photo_src, '.'));
            $fxt = (!in_array($ext, array('jpeg', 'png'))) ? "jpeg" : $ext;
        } else {
            $ext = $fxt = 0;
        }
        
        if (preg_match('/(jpg|jpeg|png)/', $ext)) {
            list($photo_width, $photo_height) = getimagesize($photo_src);
            $create_from = "imagecreatefrom" . $fxt;
            $photo_source = $create_from($photo_src);
            
            if ($run == "crop") {
                
                if ($width > 0 && $height > 0) {
                    $crop_width = $photo_width;
                    $crop_height = $photo_height;
                    $k_w = 1;
                    $k_h = 1;
                    $dst_x = 0;
                    $dst_y = 0;
                    $src_x = 0;
                    $src_y = 0;
                    
                    if ($width == 0 or $width > $photo_width) {
                        $width = $photo_width;
                    }
                    
                    if ($height == 0 or $height > $photo_height) {
                        $height = $photo_height;
                    }
                    
                    $crop_width = $width;
                    $crop_height = $height;
                    
                    if ($crop_width > $photo_width) {
                        $dst_x = ($crop_width - $photo_width) / 2;
                    }
                    
                    if ($crop_height > $photo_height) {
                        $dst_y = ($crop_height - $photo_height) / 2;
                    }
                    
                    if ($crop_width < $photo_width || $crop_height < $photo_height) {
                        $k_w = $crop_width / $photo_width;
                        $k_h = $crop_height / $photo_height;
                        
                        if ($crop_height > $photo_height) {
                            $src_x  = ($photo_width - $crop_width) / 2;
                        } elseif ($crop_width > $photo_width) {
                            $src_y  = ($photo_height - $crop_height) / 2;
                        } else {
                            
                            if ($k_h > $k_w) {
                                $src_x = round(($photo_width - ($crop_width / $k_h)) / 2);
                            } else {
                                $src_y = round(($photo_height - ($crop_height / $k_w)) / 2);
                            }
                        }
                    }
                    
                    $crop_image = @imagecreatetruecolor($crop_width, $crop_height);
                    
                    if ($ext == "png") {
                        @imagesavealpha($crop_image, true);
                        @imagefill($crop_image, 0, 0, @imagecolorallocatealpha($crop_image, 0, 0, 0, 127));
                    }
                    
                    @imagecopyresampled($crop_image, $photo_source ,$dst_x, $dst_y, $src_x, $src_y, $crop_width - 2 * $dst_x, $crop_height - 2 * $dst_y, $photo_width - 2 * $src_x, $photo_height - 2 * $src_y);
                    @imageinterlace($crop_image, true);
                    @imagejpeg($crop_image, $save_src);
                    @imagedestroy($crop_image);
                }
            } elseif ($run == "resize") {
                
                if ($width == 0 && $height == 0) {
                    return false;
                }
                
                if ($width > 0 && $height == 0) {
                    $resize_width = $width;
                    $resize_ratio = $resize_width / $photo_width;
                    $resize_height = floor($photo_height * $resize_ratio);
                } elseif ($width == 0 && $height > 0) {
                    $resize_height = $height;
                    $resize_ratio = $resize_height / $photo_height;
                    $resize_width = floor($photo_width * $resize_ratio);
                } elseif ($width > 0 && $height > 0) {
                    $resize_width = $width;
                    $resize_height = $height;
                }
                
                if ($resize_width > 0 && $resize_height > 0) {
                    $resize_image = @imagecreatetruecolor($resize_width, $resize_height);
                    
                    if ($ext == "png") {
                        @imagesavealpha($resize_image, true);
                        @imagefill($resize_image, 0, 0, @imagecolorallocatealpha($resize_image, 0, 0, 0, 127));
                    }
                    
                    @imagecopyresampled($resize_image, $photo_source, 0, 0, 0, 0, $resize_width, $resize_height, $photo_width, $photo_height);
                    @imageinterlace($resize_image, true);
                    @imagejpeg($resize_image, $save_src);
                    @imagedestroy($resize_image);
                }
            } elseif ($run == "scale") {
                
                if ($width == 0) {
                    $width = 100;
                }
                
                if ($height == 0) {
                    $height = 100;
                }
                
                $scale_width = $photo_width * ($width / 100);
                $scale_height = $photo_height * ($height / 100);
                $scale_image = @imagecreatetruecolor($scale_width, $scale_height);
                
                if ($ext == "png") {
                    @imagesavealpha($scale_image, true);
                    @imagefill($scale_image, 0, 0, imagecolorallocatealpha($scale_image, 0, 0, 0, 127));
                }
                
                @imagecopyresampled($scale_image, $photo_source, 0, 0, 0, 0, $scale_width, $scale_height, $photo_width, $photo_height);
                @imageinterlace($scale_image, true);
                @imagejpeg($scale_image, $save_src);
                @imagedestroy($scale_image);
            }
        }
    }
}

public static function SK_generateKey($minlength=5, $maxlength=5, $uselower=true, $useupper=true, $usenumbers=true, $usespecial=false) {
    $charset = '';
    
    if ($uselower) {
        $charset .= "abcdefghijklmnopqrstuvwxyz";
    }
    
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    
    if ($usenumbers) {
        $charset .= "123456789";
    }
    
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }
    
    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }
    
    $key = '';
    
    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, strlen($charset) - 1))];
    }
    
    return $key;
}

public static function SK_getLiveHistoryId($file_name) {
    global $dbConnect;
    $dbConnect = Helpers::dbConnect();

    $query = "SELECT id FROM live_history WHERE file_name = '{$file_name}' ";
   
    $sql_query = mysqli_query($dbConnect, $query);
    $sql_numrows = mysqli_num_rows($sql_query);
    
    if ($sql_numrows > 0) {
        
        while ($sql_fetch = mysqli_fetch_assoc($sql_query)) {
            $get = $sql_fetch['id'];
        }
        
        return $get;
    }
}

public static function SK_getHashtagSearch($tag='', $limit=4) {
    global $dbConnect;
    $dbConnect = Helpers::dbConnect();

    $get = array();
    
    if (empty($tag)) {
        return false;
    }
    
    if (empty($limit) or !is_numeric($limit) or $limit < 1) {
        $limit = 5;
    }
    
    $tag = Helpers::SK_secureEncode($tag);
    
    if (is_numeric($tag)) {
        $query = "SELECT * FROM " . DB_HASHTAGS_API . " WHERE id=$tag LIMIT $limit";
    } else {
        $query = "SELECT * FROM " . DB_HASHTAGS_API . " WHERE tag LIKE '%$tag%' LIMIT $limit";
    }
    
    $sql_query = mysqli_query($dbConnect, $query);
    $sql_numrows = mysqli_num_rows($sql_query);
    
    if ($sql_numrows > 0) {
        
        while ($sql_fetch = mysqli_fetch_assoc($sql_query)) {
            $get[] = $sql_fetch;
        }
        
        return $get;
    }
}

public static function SK_getSearchMobile($search_query='', $from_row=0, $limit=10) {
    global $dbConnect;

    $dbConnect = Helpers::dbConnect();
    $get = array();
    $get['story'] = array();
    $get['user'] = array();
    $get['hashtag'] = array();
    
    if (!isset($search_query) or empty($search_query)) {
        return $get;
    }
    
    if (!isset($from_row) or empty($from_row)) {
        $from_row = 0;
    }
    
    if (!is_numeric($from_row) or $from_row < 0) {
        return $get;
    }
    
    if (!isset($limit) or empty($limit)) {
        $limit = 10;
    }
    
    if (!is_numeric($limit) or $limit < 1) {
        return $get;
    }
    
    $search_query = Helpers::SK_secureEncode($search_query);
    $from_row = Helpers::SK_secureEncode($from_row);
    $limit = Helpers::SK_secureEncode($limit);
    $query_one = "SELECT id FROM " . DB_ACCOUNTS_API . 
    " WHERE (email LIKE '%$search_query%' OR username LIKE '%$search_query%' " .
        " OR name LIKE '%$search_query%') AND (id IN (SELECT id FROM " . DB_USERS_API . ") " .
        " OR id IN (SELECT id FROM " . DB_PAGES_API . ") " .
        " OR id IN (SELECT id FROM " . DB_GROUPS_API . 
                    " WHERE group_privacy IN ('open','closed'))) " . 
                    "AND type IN ('user','page','group') " .
        " AND active=1 ORDER BY name ASC LIMIT $from_row,$limit";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    
    if (mysqli_num_rows($sql_query_one) > 0) {
        
        while ($sql_fetch_one = mysqli_fetch_assoc($sql_query_one)) {
            //$get['user'][] = SK_getUser($sql_fetch_one['id']);
            $user = Account::find($sql_fetch_one['id']);
                            $user->avatar;
                            $user->cover;
            //$data['author'] = $post->account;
            $user->live_cover = "https://www.vdomax.com/clips/imgd.php?src=rtmp/".$user->username.".png&width=800&height=600&crop-to-fit";
            $user->live = "http://150.107.31.13:1935/live/".$user->username."/playlist.m3u8";
            $user->is_live = Helpers::isLive($user->username);
            $user->online = false;

            $get['user'][] = $user;
        }
    }
    // 

    $hashdata = Helpers::SK_getHashtag($search_query);
    $postIds = Helpers::SK_getHashtagPostsArray($hashdata);

    foreach($postIds as $postId) {
        $get['story'][] = Helpers::getPost($postId);
    }

    $query_one = "SELECT id FROM " . DB_POSTS_API . " WHERE youtube_title LIKE '%$search_query%' ORDER BY youtube_title ASC LIMIT $from_row,$limit";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    
    if (mysqli_num_rows($sql_query_one) > 0) {
        
        while ($sql_fetch_one = mysqli_fetch_assoc($sql_query_one)) {           
            //$get['story'][] = Post::find($sql_fetch_one['id']);
            $get['story'][] = Helpers::getPost($sql_fetch_one['id']);
        }
    }

    $get['hashtag'] = Helpers::SK_getHashtagSearch($search_query);

    if(count($get['story']) == 0 || $get['story'] == null)
        $get['story'] = array();

    if(count($get['user']) == 0 || $get['user'] == null)
        $get['user'] = array();

    if(count($get['hashtag']) == 0 || $get['hashtag'] == null)
        $get['hashtag'] = array();

    //
    return $get;
}

public static function SK_getMarkup($text, $link=true, $hashtag=true, $mention=true) {
    global $dbConnect;

    $dbConnect = Helpers::dbConnect();
    
    if ($link == true) {
        // Links
        $link_search = '/\[a\](.*?)\[\/a\]/i';
        
        if (preg_match_all($link_search, $text, $matches)) {
            
            foreach ($matches[1] as $match) {
                $match_decode = urldecode($match);
                $match_url = $match_decode;
                
                if (!preg_match("/http\:\/\//", $match_decode)) {
                    $match_url = 'http://' . $match_url;
                }

                $text = str_replace('[a]' . $match . '[/a]', '' . utf8_decode(urldecode($match_decode)) . '', $text);
                
                //$text = strip_tags($match_url);
            }
        }
    }
    
    if ($hashtag == true) {
        // Hashtags
        $hashtag_regex = '/(#\[([0-9]+)\])/i';
        preg_match_all($hashtag_regex, $text, $matches);
        $match_i = 0;
        
        foreach ($matches[1] as $match) {
            $hashtag = $matches[1][$match_i];
            $hashkey = $matches[2][$match_i];
            $hashdata = Helpers::SK_getHashtag($hashkey);
            
            if (is_array($hashdata)) {
                $hashlink = '#[' . $hashdata['tag'] . ']';
                $text = str_replace($hashtag, $hashlink, $text);
            }
            
            $match_i++;
        }
    }
    
    if ($mention == true) {
        // @Mentions
        $mention_regex = '/@\[([0-9]+)\]/i';
        
        if (preg_match_all($mention_regex, $text, $matches)) {
            
            foreach ($matches[1] as $match) {
                $match = Helpers::SK_secureEncode($match);
                //$match_user = SK_getUser($match);
                $user = Account::find($match);
                
                $match_search = '@[' . $match . ']';
                $match_replace = '@{' . $user['username'] . '}';
                
                if (isset($user['id'])) {
                    $text = str_replace($match_search, $match_replace, $text);
                }
            }
        }
    }
    
    return $text;

}

public static function getPost($id) {
        $post = Post::where('id', $id)->get();

        $data = $post[0];

/*
        if($post[0]->type1 == "story" && $post[0]->type2 == "none")
            $data['type'] = "story";

        if($post[0]->type1 == "story" && $post[0]->type2 == "comment")
            $data['type'] = "comment";
            */

            $post[0]->account->avatar;
            $post[0]->account->cover;

            $tattoo_src = "";

            if ( $post[0]->text != ""){
                $data['text'] = Helpers::SK_getMarkup($post[0]->text);
                $data['emoticonized'] = Helpers::SK_emoticonize($post[0]->text,$post[0]->account->id);

                $html = $data['emoticonized'];

                preg_match( '@src="([^"]+)"@' , $html, $match );

                $tattoo_src = array_pop($match);

                $data['tattoo_url'] = $tattoo_src;
            }else{
                $data['text'] = null;
                $data['emoticonized'] = null;
                $data['tattoo_url'] = null;
            }

            $data['author'] = $post[0]->account;

        $lovers = Post::getLovers($post[0]->id);
        $comments = Post::getComment($post[0]->id);
        $share = Post::getShare($post[0]->id);

        $data['post_type'] = "text";

            // assign media type.
            if ( $post[0]->media_id != 0 ){
                $data['post_type'] = "photo";
            }

            if ( $post[0]->clip_id != 0 ){
                    $data['post_type'] = "clip";
            }

            if($post[0]->google_map_name != "") {
                $data['post_type'] = "map";
                $data['text'] = $post->google_map_name;
            }

            if ( $post[0]->soundcloud_uri != "" ){
                $data['post_type'] = "soundcloud";
            }
            
            if ( $post[0]->youtube_video_id != "" ){
                $data['post_type'] = "youtube";
            } 



            if($data['tattoo_url'] != ""){
                $data['post_type'] = "tattoo";
                $data['tattoo_url'] = $tattoo_src;
                $data['tattoo_code'] = $data['text'];
            }

            if($post[0]->text != "" && strpos($post[0]->text, 'กำลังถ่ายทอดสด') !== FALSE) {
                $data['post_type'] = "live";
            } 

            if ($post[0]->media != ""){


                $data['media'] = $post[0]->media;

                $data['media']['url'] = $post[0]->media->url . "." . $post[0]->media->extension;
                $data['media']['url_thumb'] = "imgd.php?src=".$post[0]->media->url."&width=600&height=800";


                if($post[0]->media->type == "album") {
                    $data['media']['album_photos'] = Media::getAlbum($post[0]->media->id);
                    $data['media']['url'] = Media::getAlbumFirst($post[0]->media->id);
                } else {
                    $data['media']['album_photos'] = array(0 => array("url_thumb" => "imgd.php?src=".$post[0]->media->url.".".$post[0]->media->extension."&width=600&height=800","url" => $post[0]->media->url));
                }
                

                
            }else{
                $data['media'] = null;
            }

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


            // assign media type.
            if ( $post[0]->media_id != 0 ){
                $data['media_type'] = "media";
            }elseif ( $post[0]->clip_id != 0 ){
                $data['media_type'] = "video";
            }elseif ( $post[0]->soundcloud_uri != "" ){
                $data['media_type'] = "Soundcloud";
            }
            elseif ( $post[0]->youtube_video_id != "" ){
                $data['media_type'] = "Youtube";
            } else {
                $data['media_type'] = "text";
            }

            if ($post[0]->media != ""){
                $data['media'] =  $post[0]->media;
            }else{
                $data['media'] = null;
            }

            if ($post[0]->clip != ""){
                $post[0]->clip->thumbnail;
                $data['clip'] = $post[0]->clip;
            }else{
                $data['clip'] = null;
            }

            if ($post[0]->soundcloud_uri != ""){
                $data['soundcloud'] = array('title' => $post[0]->soundcloud_title,
                                            'uri' => $post[0]->soundcloud_uri);
            }else{
                $data['soundcloud'] = null;
            }

            if ($post[0]->youtube_video_id != ""){
                $data['youtube'] = array('id' => $post[0]->youtube_video_id,
                                        'title' => $post[0]->youtube_title,
                                        'description' => $post[0]->youtube_description,
                                        'thumbnail' => 'http://img.youtube.com/vi/'.$post[0]->youtube_video_id.'/0.jpg');
            }else{
                $data['youtube'] = null;
            }
            return $data;
    } 

public static function SK_getHashtagPosts($hashdata) {
    global $dbConnect;

    $dbConnect = Helpers::dbConnect();
    $res = array();

    if (is_array($hashdata) && count($hashdata) > 0) {
        $search_string = "#[" . $hashdata['id'] . "]";
        $query_one = "SELECT id FROM ". DB_POSTS_API." WHERE text LIKE '%$search_string%' AND type1='story' AND type2='none' AND hidden=0 AND active=1 ORDER BY id DESC LIMIT 20";
        $sql_query_one = mysqli_query($dbConnect, $query_one);
        while ($sql_fetch_one = mysqli_fetch_assoc($sql_query_one)) {
            $res[] = Helpers::getPost($sql_fetch_one['id']);
        }
    }
    return $res;
}

public static function SK_getHashtagPostsArray($hashdata) {
    global $dbConnect;

    $dbConnect = Helpers::dbConnect();

    $res = array();

    if (is_array($hashdata) && count($hashdata) > 0) {
        $search_string = "#[" . $hashdata['id'] . "]";
        $query_one = "SELECT id FROM ". DB_POSTS_API." WHERE text LIKE '%$search_string%' AND type1='story' AND type2='none' AND hidden=0 AND active=1 ORDER BY id DESC LIMIT 20";
        $sql_query_one = mysqli_query($dbConnect, $query_one);
        while ($sql_fetch_one = mysqli_fetch_assoc($sql_query_one)) {
            $res[] = $sql_fetch_one['id'];
        }
    }
    return $res;
}

public static function SK_getHashtag($tag='') {
    global $dbConnect;
    $create = false;

    $dbConnect = Helpers::dbConnect();
    
    if (empty($tag)) {
        return false;
    }
    
    $tag = Helpers::SK_secureEncode($tag);
    
    if (is_numeric($tag)) {
        $query = "SELECT * FROM " . DB_HASHTAGS_API . " WHERE id=$tag";
    } else {
        $query = "SELECT * FROM " . DB_HASHTAGS_API . " WHERE tag='$tag'";
        $create = true;
    }
    
    $sql_query = mysqli_query($dbConnect, $query);
    $sql_numrows = mysqli_num_rows($sql_query);
    
    if ($sql_numrows == 1) {
        $sql_fetch = mysqli_fetch_assoc($sql_query);
        return $sql_fetch;
    } elseif ($sql_numrows == 0) {
        
        if ($create == true) {
            $hash = md5($tag);
            $query_two = "INSERT INTO " . DB_HASHTAGS_API . " (hash,tag,last_trend_time) VALUES ('$hash','$tag'," . time() . ")";
            $sql_query_two = mysqli_query($dbConnect, $query_two);
            
            if ($sql_query_two) {
                $sql_id = mysqli_insert_id($dbConnect);
                $get = array(
                    'id' => intval($sql_id),
                    'hash' => $hash,
                    'tag' => $tag,
                    'last_trend_time' => intval(time()),
                    'trend_use_num' => 0
                );
                return $get;
            }
        }
    }
}

public static function SK_emoticonize($string='',$timeline_id) {
    global $config, $emo,$dbConnect,$user;

    include_once("emo_base.php");

    $dbConnect = Helpers::dbConnect();
    $user = Account::find($timeline_id);
    
    //$dir = str_replace(array('http://', 'https://'), '//', "https://www.vdomax.com");
    $dir = "https://www.vdomax.com";
    $dir .= '/themes/vdomax1.1/emoticons';

    foreach ($emo as $code => $img) {
        $code = Helpers::SK_secureEncode($code);
        $url = $dir . '/' . urlencode($img);
        $img = '<img src="' . $url . '" class="emoticon_original">';
        $string = str_replace($code, $img, $string);
    }
    
    $config['tattoo_url'] = "https://www.vdomax.com/assets/items/tattoo";
    
    $sqlTattoo = "select its.* ,i.name as itemname,i.imgpath as mainimgpath,ptt.day,io.own_date
                    from vmmax_payment.item_tattoo_set as its
                    inner join vmmax_payment.item as i on (its.item_id = i.id)
                    inner join vmmax_payment.item_own as io on(i.id = io.item_id)
                    inner join vmmax_payment.prop_tattoo as ptt on(i.id = ptt.item_id)
                    ";
    
    $result = mysqli_query($dbConnect,$sqlTattoo);



    if ($result !== false) {
        while($row = mysqli_fetch_assoc($result)) {
            $str_path = explode("_",$row['imgpath']);
            $codeid = explode(".",$str_path[2]);
            $code = $str_path[0].":".$codeid[0].":".$str_path[0];
            $img  = $config['tattoo_url'].'/'.str_replace(" ","%20",$row['imgpath']);
            if($code ==$str_path[0].":".$codeid[0].":".$str_path[0]){
                $img = '<img src="' . $img . '" class="emoticon_custom" >';
                $string = str_replace($code, $img, $string);
            }
        }          
    }
    return $string;
    //return $img;
}

public static function SK_liveHistoryAll() {

    global $dbConnect;

    $dbConnect = Helpers::dbConnect();
    
    $query_one = "SELECT * FROM " . DB_LIVEHISTORY_API . " LIMIT 100";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    

    if ($sql_query_one !== false) {
        while($row = mysqli_fetch_assoc($sql_query_one)) {
            $a[] = $row;
        }
    }

    return $a;

}

public static function SK_isPostLoved($post_id=0, $timeline_id=0) {

    
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
    $user = Account::find($timeline_id);
    

    
    if (empty($timeline_id) or $timeline_id == 0) {
        $timeline_id = $user['id'];
    }
    
    if (!is_numeric($timeline_id) or $timeline_id < 1) {
        return false;
    }
    
    $post_id = Helpers::SK_secureEncode($post_id);
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    
    $query_one = "SELECT id FROM " . DB_POSTS_API . " WHERE post_id=$post_id AND timeline_id=$timeline_id AND type2='like' AND active=1";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    
    if (mysqli_num_rows($sql_query_one) == 1) {
        return true;
    }else {
        return false;
    }
}

public static function SK_isPostCommented($post_id=0, $timeline_id=0) {

    
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
    $user = Account::find($timeline_id);
    

    
    if (empty($timeline_id) or $timeline_id == 0) {
        $timeline_id = $user['id'];
    }
    
    if (!is_numeric($timeline_id) or $timeline_id < 1) {
        return false;
    }
    
    $post_id = Helpers::SK_secureEncode($post_id);
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    
    $query_one = "SELECT id FROM " . DB_POSTS_API . " WHERE post_id=$post_id AND timeline_id=$timeline_id AND type1='story' AND type2='comment' AND active=1";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    
    if (mysqli_num_rows($sql_query_one) == 1) {
        return true;
    }else {
        return false;
    }
}

public static function SK_isPostShared($post_id=0, $timeline_id=0) {
    
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
    $user = Account::find($timeline_id);
    
    if (empty($timeline_id) or $timeline_id == 0) {
        $timeline_id = $user['id'];
    }
    
    if (!is_numeric($timeline_id) or $timeline_id < 1) {
        return false;
    }
    
    $post_id = Helpers::SK_secureEncode($post_id);
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    
    $query_one = "SELECT id FROM " . DB_POSTS_API . " WHERE post_id=$post_id AND timeline_id=$timeline_id AND type1='story' AND type2='share' AND active=1";
    $sql_query_one = mysqli_query($dbConnect, $query_one);
    
    if (mysqli_num_rows($sql_query_one) == 1) {
        return true;
    } else {
        return false;
    }
}

public static function SK_isFollowing($following_id=0, $timeline_id=0) {    
    global $dbConnect, $user;



    $dbConnect = Helpers::dbConnect();
    $user = Account::find($timeline_id);
    
    if (empty($following_id) or !is_numeric($following_id) or $following_id < 1) {
        return false;
    }
    
    if (empty($timeline_id) or $timeline_id == 0) {
        $timeline_id = $user['id'];
        
        //if (SK_isBlocked($following_id)) {
          //  return false;
        //}
    }
    
    if (!is_numeric($timeline_id) or $timeline_id < 1) {
        return false;
    }
    
    $following_id = Helpers::SK_secureEncode($following_id);
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    
    $query_text = "SELECT id FROM ". DB_FOLLOWERS_API ." WHERE follower_id=$timeline_id AND following_id=$following_id AND active=1";
    $sql_query = mysqli_query($dbConnect, $query_text);
    
    if (mysqli_num_rows($sql_query) > 0) {
        return true;
    } else {
    	return false;
    }
}


public static function SK_getEmoticons() {
    global $emo;
    $emoticon = array();
    
    if (!isset($emo) or !is_array($emo)) {
        return false;
    }
    
    foreach ($emo as $code => $img) {
        $emoticon[addslashes($code)] = '/home/vdomax/theme/vdomax1.1' . '/emoticons/' . $img;
    }
    
    return array_unique($emoticon);
}

public static function SK_secureEncode($str) {
	return $str;
}

public static function SK_countPosts($timeline_id=0) {
    global $dbConnect, $user;
    
    $dbConnect = Helpers::dbConnect();
    
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    //$timeline = SK_getUser($timeline_id);
    $user = Account::find($timeline_id);
    $user->type;
    
    $subquery = "timeline_id={$timeline_id} AND recipient_id=0";
    
    if ($user->type == "group") {
        $subquery = "recipient_id={$timeline_id}";
    }
    
    $query = "SELECT id FROM " . DB_POSTS_API . " WHERE $subquery AND type1='story' AND type2='none' AND hidden=0 AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
    
    return mysqli_num_rows($sql_query);
}

public static function SK_countFollowing($timeline_id=0) {
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();

    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    
    $query = "SELECT id FROM " . DB_ACCOUNTS_API . " WHERE id IN (SELECT following_id FROM " . DB_FOLLOWERS_API . " WHERE follower_id=$timeline_id AND following_id<>$timeline_id AND active=1) AND type='user' AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
    
    return mysqli_num_rows($sql_query);
}

public static function SK_countFollowers($timeline_id=0) {
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
    
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    $query = "SELECT id FROM " . DB_ACCOUNTS_API . " WHERE id IN (SELECT follower_id FROM " . DB_FOLLOWERS_API . " WHERE following_id=$timeline_id AND follower_id<>$timeline_id AND active=1) AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
    
    return mysqli_num_rows($sql_query);
}

public static function SK_countFriends($timeline_id=0) {
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
    
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    $query = "SELECT id FROM " . DB_ACCOUNTS_API . " WHERE id IN (SELECT following_id FROM " . DB_FOLLOWERS_API . " WHERE follower_id=$timeline_id AND following_id<>$timeline_id AND active=1) AND id IN (SELECT follower_id FROM " . DB_FOLLOWERS_API . " WHERE following_id=$timeline_id AND follower_id<>$timeline_id AND active=1) AND type='user' AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
	
    return mysqli_num_rows($sql_query);
}

public static function SK_countPageLikes($timeline_id=0) {
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
    
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    $query = "SELECT id FROM " . DB_ACCOUNTS_API . " WHERE id IN (SELECT following_id FROM " . DB_FOLLOWERS_API . " WHERE follower_id=$timeline_id AND following_id<>$timeline_id AND active=1) AND type='page' AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
    
    return mysqli_num_rows($sql_query);
}

public static function SK_countGroupJoined($timeline_id=0) {
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
        
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    $query = "SELECT id FROM " . DB_ACCOUNTS_API . " WHERE id IN (SELECT following_id FROM " . DB_FOLLOWERS_API . " WHERE follower_id=$timeline_id AND following_id<>$timeline_id AND active=1) AND type='group' AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
    
    return mysqli_num_rows($sql_query);
}

public static function SK_countFollowRequests($timeline_id=0) {
    global $dbConnect, $user;

    $dbConnect = Helpers::dbConnect();
        
    $timeline_id = Helpers::SK_secureEncode($timeline_id);
    $query = "SELECT id FROM " . DB_ACCOUNTS_API . " WHERE id IN (SELECT follower_id FROM " . DB_FOLLOWERS_API . " WHERE following_id=$timeline_id AND follower_id<>$timeline_id AND active=0) AND active=1";
    $sql_query = mysqli_query($dbConnect, $query);
    
    return mysqli_num_rows($sql_query);
}
	
	public static function fbAuth($access_token) {

		$dbConnect = Helpers::dbConnect();
		
		// Check connection
		if (mysqli_connect_errno($dbConnect)) {
		    exit(mysqli_connect_error());
		}

		
		$client_id = "391414774312517";
		$client_secret = "f486294a7603127e78833e54f17cbc51";

		$redirect_uri = "https://www.vdomax.com/import.php?type=facebook";

		//echo "0";

		if (!empty($access_token)) {
				//echo "1";
                $getApiUrl = "https://graph.facebook.com/me?access_token={$access_token}&fields=email,gender,name,cover,picture.width(720).height(720)";
                $getApi = @file_get_contents($getApiUrl);
                $getJson = @json_decode($getApi, true);
                
                if (!empty($getJson['name']) && !empty($getJson['id'])) {
                	//echo "2";
                    $getJson['name'] = $getJson['name'];
                    $getJson['id'] = $getJson['id'];
                    $getJson['username'] = 'fb_' . $getJson['id'];
                    
                    if (!empty($getJson['email'])) {
                        $getJson['email'] = $getJson['email'];
                    } else {
                        $getJson['email'] = $getJson['username'] . '@facebook.com';
                    }
                    
                    if (!empty($getJson['gender'])) {
                        $getJson['gender'] = $getJson['gender'];
                    } else {
                        $getJson['gender'] = 'male';
                    }
                    
                    $getJson['password'] = md5($getJson['email']);
                    
                    $query_one = "SELECT * FROM accounts WHERE (username='" . $getJson['username'] . "' OR email='" . $getJson['email'] . "') AND type='user' AND active=1";
                    $sql_query_one = mysqli_query($dbConnect, $query_one);
                    
                    if (($sql_numrows_one = mysqli_num_rows($sql_query_one)) == 1) {
                    	//echo "3";
                        $sql_fetch_one = mysqli_fetch_assoc($sql_query_one);
                        
                        //$res['user_id'] = $sql_fetch_one['id'];
                        //$res['user_pass'] = $sql_fetch_one['password'];

                        $user = Account::find($sql_fetch_one['id']);
						$user->birthday;
						$user->gender;
						$user->avatar;
						$user->cover;

						$res["state"] = "login";
						$res["user_info"] = $user;
						return $res;
                        
                        //return $user;

                        //setcookie('sk_u_i', $_SESSION['user_id'], time() + (60 * 60 * 24 * 7));
                        //setcookie('sk_u_p', $_SESSION['user_pass'], time() + (60 * 60 * 24 * 7));
                    } else {
                        
                        if (($register = Helper::SK_registerUser($getJson)) != false) {
							

                            $register['password'] = $getJson['password'];
                            $sk['mail'] = $register;
                            
                            $res['user_id'] = $register['id'];
                            $res['user_pass'] = md5($getJson['password']);


                            
                            
                            $to = $register['email'];
                            $subject = $config['site_name'] . ' - Account Password';
                            
                            $headers = "From: " . $config['email'] . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            
                            $message = "TEST FROM VM API";
                            
							//mail($to, $subject, $message, $headers);
                            
                            $user = Account::find($register['id']);
							$user->birthday;
							$user->gender;
							$user->avatar;
							$user->cover;

							$res["state"] = "register";
							$res["user_info"] = $user;
							return $res;

                            //UPDATE COVER AND AVATAR IMAGE
                            /*
                            if (!empty($getJson['cover']) && is_array($getJson['cover'])) {
                                $cover = SK_importMedia($getJson['cover']['source']);
                                
                                if (is_array($cover)) {
                                    $query_one = "UPDATE " . DB_ACCOUNTS . " SET cover_id=" . $cover['id'] . " WHERE id=" . $register['id'];
                                    $sql_query_one = mysqli_query($dbConnect, $query_one);
                                }
                            }
                            
                            if (is_array($getJson['picture']) && !empty($getJson['picture']['data']['url'])) {
                                $avatar = SK_importMedia($getJson['picture']['data']['url']);
                                
                                if (is_array($avatar)) {
                                    $query_two = "UPDATE " . DB_ACCOUNTS . " SET avatar_id=" . $avatar['id'] . " WHERE id=" . $register['id'];
                                    $sql_query_two = mysqli_query($dbConnect, $query_two);
                                }
                            }
                            */
                        }
                    }
                }
            }
            
	}

	public static function SK_registerUser($data=0) {
		$dbConnect = Helpers::dbConnect();

		$dbConnect = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_name,$sql_port);
		mysqli_set_charset($dbConnect,"utf8");
		// Check connection
		if (mysqli_connect_errno($dbConnect)) {
		    exit(mysqli_connect_error());
		}
	    
	    if (!is_array($data)) {
	        return false;
	    }
	    
	    if (!empty($data['name']) && !empty($data['username']) && !empty($data['email']) && !empty($data['password']) && !empty($data['gender'])) {
	        $name = $data['name'];
	        $username = $data['username'];
	        $email = $data['email'];
	        $password = trim($data['password']);
	        $md5_password = md5($password);
	        $gender = $data['gender'];
	        
	        //echo SK_validateUsername($username) . " " . is_numeric($username) . " " . !SK_validateEmail($email) . " " . !preg_match('/(male|female)/', $gender);


	        
	        if (!SK_validateUsername($username)) {
	            return false;
	        }
	        
	        if (is_numeric($username)) {
	            return false;
	        }
	        
	        if (!SK_validateEmail($email)) {
	            return false;
	        }
	        
	        if (!preg_match('/(male|female)/', $gender)) {
	            return false;
	        }

	        
	        $query_one = "INSERT INTO accounts (active,cover_id,email,email_verification_key,name,password,time,type,username) VALUES (1,0,'$email','" . md5(Helpers::SK_generateKey()) . "','$name','$md5_password'," . time() . ",'user','$username')";
	        $sql_query_one = mysqli_query($dbConnect, $query_one);
	        
	        if ($sql_query_one) {
	            $user_id = mysqli_insert_id($dbConnect);
	            $query_two = "INSERT INTO users (id,gender) VALUES ($user_id,'$gender')";
	            $sql_query_two = mysqli_query($dbConnect, $query_two);
	            
	            if ($sql_query_two) {
	                //$get = SK_getUser($user_id, true);
	                //return $get;
	            }
	        }
	    }
	}

	public static function send_sms($mobile,$message)
	{

		$message = urlencode($message);

		$url = "http://www.thaibulksms.com/sms_api.php";
		$data_string = "username=0831188889&password=301302&msisdn={$mobile}&message={$message}&force=standard&sender=VDOMAX.COM";

		$url = $url . "?" . $data_string;

		$return["url"] = $url;


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
		curl_setopt($ch, CURLOPT_HEADER, FALSE); 
		$result = curl_exec($ch);

/*
		$xml = simplexml_load_string($result);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
*/
		$return["server"] = $result;

		curl_close($ch);
		return $return;
	}

	public static function generateOTP()
	{
			$otp = "xxxxxx";
			while (Helpers::checkRepeatNumber($otp)) {
					$otp = rand(100000, 999999);
			}
			return $otp;
	}

	public static function checkRepeatNumber($str)
	{
			$char = str_split($str);
			$prev = $char[0];
			for ($i = 1; $i < count($char); $i++) {
					if ($prev == $char[$i]) {
							return true;
					}
					$prev = $char[$i];
			}
			return false;
	}

	public static function checkViewer($name) {
		$xmlstr = file_get_contents("http://150.107.31.6:8080/stat");
		$ret = array();
        $ret['status'] = 404;
        $ret['message'] = "No live streaming";
		$xmlcont = new SimpleXMLElement($xmlstr);	
		foreach($xmlcont->server->application->live->stream as $para) {	
			if($para->name == $name){
                $obj = $para->nclients;
				$ret['status'] = 200;
                $ret['message'] = "Now live";
				$ret['visitors'] = intval($obj) ;
                $ret['others'][] = (string) $para->name;
                //print_r($para->nclients);
				return $ret;
			}else{
				$ret['status'] = 404;
                $ret['message'] = "No live streaming for this user";
				$ret['visitors'] = 0;			
				$ret['others'][] = (string) $para->name;
			}
		}	
		return $ret;
	}

    public static function liveNowChannel() {
        $xmlstr = file_get_contents("http://150.107.31.6:8080/stat");
        $ret = array();
        $ret['status'] = "1";
        $channels = array();
        //$ret['status'] = 404;
        //$ret['message'] = "No live streaming";
        $xmlcont = new SimpleXMLElement($xmlstr);   
        foreach($xmlcont->server->application->live->stream as $para) { 
                $obj = $para->nclients;
                $username = (string) $para->name;
                $channel = Account::where('username', $username)
                ->get()->first();
                $channel->avatar;
                $channel->cover;
                $channel->gender;
                $channel->birthday;
                $channel->live_url = "rtmp://150.107.31.6:1935/live/" . $username;
                $channel->avatar_url = "https://www.vdomax.com/" . $channel->avatar;
                $channel->cover_url = "https://www.vdomax.com/" . $channel->cover;
                $channel->live_cover = "https://www.vdomax.com/clips/rtmp/".$username.".png";
                $channel->live_cover_offline = "https://www.vdomax.com/themes/vdomax1.1/images/offline-channel.png";
                $channel->online = Helpers::isLive($username);
                $channels[] = $channel;
                
                //print_r($para->nclients);
                //return $ret;
            
        }  
        $ret['count'] = count($channels);
        $ret['channels'] = $channels;

        return $ret;
    }

    public static function isLive($name) {
        $xmlstr = file_get_contents("http://150.107.31.6:8080/stat");
        $ret = array();
        $xmlcont = new SimpleXMLElement($xmlstr);   
        foreach($xmlcont->server->application->live->stream as $para) { 
            if($para->name == $name){
                return true;
            } 
        }
        return false;
    }
}