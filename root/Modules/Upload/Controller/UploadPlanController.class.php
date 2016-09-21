<?php

namespace Modules\Upload\Controller;

if (!defined("EVE_APP"))
	exit();

class UploadPlanController extends \Library\ActionController {
	
	public function executeAction(\Library\HTTPRequest $pRequest) {
		try {
			/*
			*	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
			*/
				
			$url = $_POST["plan_url"];
			/*
				$url = explode("?", $_POST["plan_url"]);
				$base = array_shift($url);
				
				foreach ($url AS $u) {
					$temp = "";
					$seg = explode("&", $u);
					foreach ($seg AS $s) {
						$elem = explode("=", $s);
						$temp .= array_shift($elem) . "=";
						$temp .= urlencode(implode("=", $elem)) . "&";
					}
					$base .= "?" . $temp;
				}//*/
				
			
				$img = imagecreatefrompng($url);
				
				$imagePath = __DIR__ . "/../../../Upload/Image/Plan/";
				
				
				//Check write Access to Directory
			
				if(!is_writable($imagePath)) {
					throw new \Library\Exception\FileException("Error on writing", \Library\Exception\FileException::UNWRITABLE_FOLDER);
				}
					  
				$name = uniqid() . ".png";
				
				imagepng($img, $imagePath . $name, 0);
					
				$file = new \Library\Entities\file(array("user_id" => $this->app()->user()->id(), "file_name" => $name, "file_src" => $imagePath, "file_pub_name" => "plan.png"));
				$this->managers()->getManagersOf("file")->send($file);
						
				 $response = array(
					"valid" => "1",
					"url" => $this->page()->getVar("root") . "/File/" . $file->id() . "/"
				);
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
						"status" => '0',
						"message" => $error
			);
		} catch (\Exception $e) {
			$response = array(
						"status" => '0',
						"message" => FILE_ERROR . " : " . $e->getMessage() 
			);
		}
		
		print(json_encode($response));
		exit();
		
	}
}


?>