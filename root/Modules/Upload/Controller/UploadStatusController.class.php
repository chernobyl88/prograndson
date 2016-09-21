<?php

namespace Modules\Upload\Controller;

if (!defined("EVE_APP"))
	exit();

class UploadStatusController extends \Library\ActionController {
	
	public function executeAction(\Library\HTTPRequest $pRequest) {
		$valid = 0;
		$message = array();
		
		$targetDir = __DIR__ . "/../../../Upload/Document/Status/";
	
		if (!file_exists($targetDir))
			mkdir($targetDir, 0777, true);
		
		if (!file_exists($targetDir)) {
			$message[] = IMPOSSIBLE_TO_WRITE_DIR;
		} else {
			$targetDir = realpath($targetDir) . "/";
			
			$name = basename($_FILES["status"]["name"]);
			$status = $this->managers()->getManagersOf("file")->getList(array("cst_name = 'STATUS'"));
				if (count($status)) {
				foreach ($status AS $s) {
					if (file_exists($s->file_src() . $s->file_name()))
						unlink($s->file_src() . $s->file_name());
				}
				$this->managers()->getManagersOf("file")->deleteList(array("cst_name = 'STATUS'"));
			}
			
			if (move_uploaded_file($_FILES['status']['tmp_name'], $targetDir . $name)) {
				$file = new \Library\Entities\file(array("user_id" => $this->app()->user()->id(), "file_name" => $name, "file_src" => $targetDir . "/", "file_pub_name" => $name, "cst_name" => "STATUS"));
				$this->managers()->getManagersOf("file")->send($file);
				$valid = 1;
				$this->page()->addVar("file_id", $file->id());
			} else {
				$message[] = ERROR_ON_WRITING_FILE;
			}
		}
		

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
}


?>