<?php

class UploadController extends \BaseController {
	public function __construct()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		ini_set('allow_url_fopen', TRUE);
		$dataInput = Input::file('file');
		//print_r($dataInput);
		$data = array();
		$mimeType = "";

		if (isset($_FILES['file']['name'])) {
      $data['files'] = $_FILES['file'];
      $i = 0;
      foreach($dataInput as $file) {
                // public/uploads
      	$mimeType = $file->getMimeType();
        $filename = $file->move('uploads/');
        $data['files']['tmp_name'][$i] = $filename;
        $i++;
    }
    }

    $data['mobile_api'] = true;
        
    // if (isset($_FILES['clips']['name'])) {
    //   $data['clips'] = $dataInput['clips'];
    // }
    $a = array();
    if($mimeType == "image/gif" || $mimeType == "image/jpeg" || $mimeType == "image/png")
			$a = Helpers::SK_registerChatUploadPhoto($data);	
		else
			$a = Helpers::SK_registerChatUploadClip($data);	


		$a['mimetype'] = $mimeType;
		return Response::json($a);

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
