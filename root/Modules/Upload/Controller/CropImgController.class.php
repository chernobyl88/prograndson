<?php

namespace Modules\Upload\Controller;

if (!defined("EVE_APP"))
	exit();

class CropImgController extends \Library\ActionController {
	
	public function executeAction(\Library\HTTPRequest $pRequest) {
		try {
			/*
			 *	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
			*/
			$expension = 1;
			if ($pRequest->existPost("crop_type")) {
				switch ($pRequest->dataPost("crop_type")) {
					case "galerie":
						$expension = 1;	
						break;
					default:
						$expension = 2;
				}
			} else {
				$expension = 2;
			}
			
			$imgUrl = $_POST['imgUrl'];
			
			$id = explode("-", substr($imgUrl, 0, -4));
			$id = end($id);
			
			if (!is_numeric($id))
				throw new \Library\Exception\FileException("Error on saving data", \Library\Exception\FileException::ERROR_DB_INSERTION);
			
			$fileManager = $this->managers()->getManagersOf("file");
			
			$file = $fileManager->get($id);
			
			if ($file->id() < 1)
				throw new \Library\Exception\FileException("Error on saving data", \Library\Exception\FileException::ERROR_DB_INSERTION);
			
			$imgUrl = $file->file_src() . $file->file_name();
			
			// original sizes
			$imgInitW = $_POST['imgInitW'];
			$imgInitH = $_POST['imgInitH'];
			
			// resized sizes
			$imgW = $_POST['imgW']*$expension;
			$imgH = $_POST['imgH']*$expension;
			
			// offsets
			$imgY1 = $_POST['imgY1']*$expension;
			$imgX1 = $_POST['imgX1']*$expension;
			
			// crop box
			$cropW = $_POST['cropW']*$expension;
			$cropH = $_POST['cropH']*$expension;
			
			// rotation angle
			$angle = $_POST['rotation'];
			
			$jpeg_quality = 100;
			
			$output_filesrc = __DIR__ . "/../../../Upload/Image/Croped/";
			$output_filename = $file->file_name();
			
			// uncomment line below to save the cropped image in the same location as the original image.
			//$output_filename = dirname($imgUrl). "/croppedImg_".rand();
			
			$what = getimagesize($imgUrl);
			
			switch(strtolower($what['mime'])) {
				case 'image/png':
					$img_r = imagecreatefrompng($imgUrl);
					$source_image = imagecreatefrompng($imgUrl);
					$type = '.png';
					break;
				case 'image/jpeg':
					$img_r = imagecreatefromjpeg($imgUrl);
					$source_image = imagecreatefromjpeg($imgUrl);
					error_log("jpg");
					$type = '.jpeg';
					break;
				case 'image/gif':
					$img_r = imagecreatefromgif($imgUrl);
					$source_image = imagecreatefromgif($imgUrl);
					$type = '.gif';
					break;
				default:
					throw new \Library\Exception\FileException("Error on data type", \Library\Exception\FileException::INVALID_DATA_TYPE);
			}
			
			
			//Check write Access to Directory
			
			if(!is_writable(dirname($output_filesrc . $output_filename))){
					throw new \Library\Exception\FileException("Error on writing", \Library\Exception\FileException::UNWRITABLE_FOLDER);
			}else{
			
				// resize the original image to size of editor
				$resizedImage = imagecreatetruecolor($imgW, $imgH);
				imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
				// rotate the rezized image
				$rotated_image = imagerotate($resizedImage, -$angle, 0);
				// find new width & height of rotated image
				$rotated_width = imagesx($rotated_image);
				$rotated_height = imagesy($rotated_image);
				// diff between rotated & original sizes
				$dx = $rotated_width - $imgW;
				$dy = $rotated_height - $imgH;
				// crop rotated image to fit into original rezized rectangle
				$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
				imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
				imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
				// crop image into selected area
				$final_image = imagecreatetruecolor($cropW, $cropH);
				imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
				imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
				// finally output png image
				//imagepng($final_image, $output_filename.$type, $png_quality);
				imagejpeg($final_image, $output_filesrc . $output_filename, $jpeg_quality);

				$file->setFile_src(realpath($output_filesrc)."/");
					
				$fileManager->send($file);
					
				if ($file->id() < 1)
					throw new \Library\Exception\FileException("Error on saving data", \Library\Exception\FileException::ERROR_DB_INSERTION);
					
				$filePath = "./Img/std-" . $file->id() . ".jpg";
				
				$response = Array(
						"status" => 'success',
						"url" => $filePath,
						"file_id" => $file->id()
				);
			}
		} catch (\Library\Exception\FileException $e) {
			switch ($e->getCode()) {
				case \Library\Exception\FileException::UNWRITABLE_FOLDER:
					$error = UNWRITABLE_FOLDER;
					break;
				case \Library\Exception\FileException::ERROR_DB_INSERTION:
					$error = ERROR_DB_INSERTION . $e->getMessage();
					break;
				case \Library\Exception\FileException::INVALID_DATA_TYPE:
					$error = INVALID_DATA_TYPE;
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