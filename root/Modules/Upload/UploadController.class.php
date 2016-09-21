<?php

namespace Modules\Upload;

if (!defined("EVE_APP"))
	exit();

class UploadController extends \Library\BackController {
	
	public function executeGetFile(\Library\HTTPRequest $request) {
		if (!($request->existGet("file_id") && is_numeric($request->dataGet("file_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$fileManager = $this->managers()->getManagersOf("file");
		
		$file = $fileManager->get($request->dataGet("file_id"));
		
		if ($file->id() < 0)
			$this->app()->httpResponse()->redirect404();
			
		$this->page()->setPageType("file", array("rep" => $file->file_src(), "fileToLoad" => $file->file_name()));
	}
	
	public function executeGetMinFile(\Library\HTTPRequest $request) {
		if (!($request->existGet("file_id") && is_numeric($request->dataGet("file_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$fileManager = $this->managers()->getManagersOf("file");
		
		$file = $fileManager->get($request->dataGet("file_id"));
		
		if ($file->id() < 0)
			$this->app()->httpResponse()->redirect404();
		
		$this->page()->setPageType("file", array("rep" => $file->file_src(), "fileToLoad" => "min_" . $file->file_name()));
	}
	
	public function executeCaptcha(\Library\HTTPRequest $request) {
		$this->page()->addVar("code", substr(md5($request->dataGet("code")), 10, 4));
		$this->page()->setIsImage(75, 40, "jpg");
	}
	
	public function executeUpload(\Library\HTTPRequest $request) {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		//Nécessite sécuriter pour éviter l'upload de n'importe quoi via les fichier zip
		//contrôle d'extension
		
		@set_time_limit(5 * 60);
		
		//usleep(5000);
		
		$valid = 0;
		$message = array();
		
		if(!($testPath = realpath(__DIR__ . "/../.." . \Library\Application::appConfig()->getConst("DOWNLOAD_FOLDER"))))
			$message[] = "No writable folder exist";
		else {
			if ($request->existPost("dir")) {
				$path = __DIR__."/../..".((substr($request->dataPost("dir"), 0, 1) == "/")?"":"/").$request->dataPost("dir");
				if ((file_exists($path) || @mkdir($path, 0777, true)) && ($path = realpath($path)))
					if (strpos($path, $testPath) === 0)
						$targetDir = $path;
					else 
						$message[] = "The choosen folder is not in the allowed zone";
				else
					$message[] = "The choosen folder is not writable";
			}
			
			if (count($message) == 0)
				if (!isset($targetDir)){
					if (!($targetDir = realpath(__DIR__ . "/../../Upload/temp/")));
						$message[] = "Impossibl to find default folder";
				}
			
			if (count($message) == 0) {
				if (!file_exists($targetDir) || !@is_dir($targetDir) || !is_writable($targetDir))
					$message[] = "Specified directory is not writable or is not a diretory";
				else {
					if (isset($_REQUEST["name"])) {
						$fileName = $_REQUEST["name"];
					} elseif (!empty($_FILES)) {
						$fileName = $_FILES["file"]["name"];
					} else {
						$fileName = uniqid("file_");
					}

					$filePath = $targetDir  . DIRECTORY_SEPARATOR . $fileName;
					
					$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
					$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

					if (!$out = @fopen($filePath . ".part", $chunks ? "ab" : "wb"))
						$message[] = "Failed to open output stream";
					else {
						if (!empty($_FILES)) {
							if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
								$message[] = "Failed to move uploaded file.";
							} else
								if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb"))
									$message[] = "Failed to open input stream.";
						} else
							if (!$in = @fopen("php://input", "rb"))
								$message[] = "Failed to open input stream.";
						
						if (count($message) == 0) {
							
							while ($buff = fread($in, 4096)) {
								fwrite($out, $buff);
							}
							
							@fclose($out);
							@fclose($in);

							
							if (!$chunks || $chunk == $chunks - 1) {
								// Strip the temp .part suffix off
								
								$name = explode(".", $fileName);
								$ext = $name[count($name)-1];
								unset($name[count($name)-1]);
								
								if ($ext == "zip" && $request->existPost("unzip") && $request->dataPost("unzip") == 1) {
									$zip = new \ZipArchive();
									$listeFile = array();
									
									if ($zip->open($filePath.".part")){
										for ($i = 0; $i < $zip->numFiles; $i++)
											if ((!$request->existPost("validExt") || ($validExt = $request->dataPost("validExt") == "")) || (count($expl = explode(".", $zip->getNameIndex($i))) && in_array($ext = $expl[count($expl)-1], explode(",", $validExt)) && $ext != "zip"))
												$listeFile[] = $zip->getNameIndex($i);
										
										if (count($listeFile)) {
											
											if ($zip->extractTo($filePath, $listeFile)) {
												
												$listeFile = array_map(function ($a) use ($filePath, $fileName) {
													return new \Library\Entities\file(array("file_name" => $fName = basename($filePath . "/" . $a), "file_src" => dirname($filePath . "/" . $a), "file_pub_name" => $fName, "user_id" => $this->app()->user()->id()));
												}, $listeFile);
												
												foreach ($listeFile AS $file)
													$this->managers()->getManagersOf("file")->send($file);
												
												unlink($filePath.".part");
												
												$valid = 1;
												$this->page()->addVar("listeId", array_map(function ($a) {return $a->id();}, $listeFile));
											}
										} else
											$message[] = "Zip file is empty or contain zero valid file";
									} else
										$message[] = "Error on reading .zip file";
								} else {
									$name = implode(".", $name) . "_" . uniqid() . "." . $ext;
										
									rename($filePath.".part", $targetDir . "/" . $name);
										
									$file = new \Library\Entities\file(array("file_name" => $name, "file_src" => $targetDir . "/", "file_pub_name" => $fileName, "user_id" =>$this->app()->user()->id()));
										
									$this->managers()->getManagersOf("file")->send($file);
												
									$valid = 1;
									$this->page()->addVar("listeId", array($file->id()));
								}
							} else {
								$valid = 1;
							}
						}
					}
				}	
			}
		}

		$this->page()->addVar("jsonrpc", "2.0");
		$this->page()->addVar("message", $message);
		$this->page()->addVar("valid", $valid);
		
		$this->page()->setIsJson();
	}
	
	public function executeGetMini(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		if (!is_numeric($fileId = $request->dataTransfert("fileId")) || !($file = $this->managers()->getManagersOf("file")->get($fileId)) instanceof \Library\Entities\file)
			$message = "Error on getting file";
		else {
			$info = pathinfo($fileSrc = ($file->file_src() . ((substr($file->file_src(), 0, 1) != "/") ? "/": "") . $file->file_name()));
			
			switch ($ext = strtolower($info["extension"])) {
				case "jpeg":
				case "jpg":
					$img = imagecreatefromjpeg($fileSrc);
					break;
				case "png":
					$img = imagecreatefrompng($fileSrc);
					break;
				case "gif":
					$img = imagecreatefromgif($fileSrc);
					break;
				default:
					$message[] = "Not valid image extension";
			}
			
			if (count($message) == 0) {
				if ($size = @getimagesize($fileSrc)) {
					$width = ($w = $this->app()->config()->get("min_max_size")) ? $w : 200;
					$height = $size[1] * $width / $size[0];
					
					if ($height > $width) {
						$height = $width;
						$width = $size[0] * $height / $size[1];
					}
					
					
					
					
					$min = imagecreatetruecolor($width, $height);
					
					imagecopyresampled($min, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
					imagejpeg($min, $file->file_src() . ((substr($file->file_src(), 0, 1) != "/") ? "/" : "") . "min_" . $file->file_name());
					
					$valid = 1;
				} else {
					$message[] = "Error on reading image informations";
				}
			}
		}

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
	}
}

?>