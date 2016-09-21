<?php

namespace Modules\Upload\Controller;

if (!defined("EVE_APP"))
	exit();

class UploadImgController extends \Library\ActionController {
	
	public function executeAction(\Library\HTTPRequest $request) {
		try {
			/*
			*	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
			*/
			
			if ($request->existPost("folder"))
				$fold = $request->dataPost("folder");
			else 
				$fold = "temp";
			
			    $imagePath = __DIR__ . "/../../../Upload/Image/" . $fold . "/";
			
				$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
				$temp = explode(".", $_FILES["img"]["name"]);
				$extension = end($temp);
				
				//Check write Access to Directory
			
				if(!is_writable($imagePath)) {
					throw new \Library\Exception\FileException("Error on writing", \Library\Exception\FileException::UNWRITABLE_FOLDER);
				}
				
				if ( in_array($extension, $allowedExts)){
					if ($_FILES["img"]["error"] > 0){
						throw new \Library\Exception\FileException("Error on writing", \Library\Exception\FileException::UNWRITABLE_FOLDER);		
					} else {
						
				      $filename = $_FILES["img"]["tmp_name"];
					  list($width, $height) = getimagesize( $filename );
					
					  
					  $ext = explode(".", $_FILES["img"]["name"]);
					  $ext = end($ext);
					  
					  $name = uniqid() . "." . $ext;
					  move_uploaded_file($filename,  $imagePath . $name);
					
						$file = new \Library\Entities\file(array("user_id" => $this->app()->user()->id(), "file_name" => $name, "file_src" => $imagePath, "file_pub_name" => $_FILES["img"]["name"]));
						$this->managers()->getManagersOf("file")->send($file);
						
					  $response = array(
						"status" => 'success',
						"url" => "./Img/std-" . $file->id() . ".jpg", //$this->page()->getVar("root") . "/File/" . $file->id() . "/",
						"width" => $width,
						"height" => $height
					  );
					  
					}
				} else {
					throw new \Library\Exception\FileException("Error on writing", \Library\Exception\FileException::INVALID_DATA_TYPE);
					$response = array(
						"status" => 'error',
						"message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
					);
				  }
		} catch (\Library\Exception\FileException $e) {
			switch ($e->getCode()) {
				case \Library\Exception\FileException::UNWRITABLE_FOLDER:
					$error = UNWRITABLE_FOLDER;
					break;
				case \Library\Exception\FileException::ERROR_DB_INSERTION:
					$error = ERROR_DB_INSERTION;
					break;
				case \Library\Exception\FileException::INVALID_DATA_TYPE:
					$error = INVALID_DATA_TYPE;
					break;
				case \Library\Exception\FileException::UPLOAD_ERROR:
					$error = UPLOAD_ERROR;
					break;
				default:
					$error = FILE_ERROR;
			}
			
			$response = array(
						"status" => 'error',
						"message" => $error
			);
		} catch (\Exception $e) {
			$response = array(
						"status" => 'error',
						"message" => FILE_ERROR . " : " . $e->getMessage() 
			);
		}
		
		print(json_encode($response));
		exit();
		
	}
}


?>