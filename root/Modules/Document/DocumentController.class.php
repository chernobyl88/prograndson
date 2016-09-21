<?php

namespace Modules\Document;

if (!defined("EVE_APP"))
	exit();

class DocumentController extends \Library\BackController {
	public function executeListe(\Library\HTTPRequest $request) {
		$status = $this->managers()->getManagersOf("file")->getList(array("cst_name = 'STATUS'"));
		
		$this->page()->addVar("listeDoc", $this->managers()->getManagersOf("file")->getListFromUser($this->app()->user()->id(), $this->app()->user()->getAdminLvl() >= \Library\Application::appConfig()->getConst("MAX_ADMIN_LVL")));
		$this->page()->addVar("status", (count($status)) ? $status[0] : null);

		$adminGroup = $this->managers()->getManagersOf("groupe")->getFromConst("ADMIN_GROUPE");
		$normalGroup = $this->managers()->getManagersOf("groupe")->getFromConst("NORMAL_USER");
		
		if ($adminGroup == null)
			$adminGroup = new \Library\Entities\groupe();
		
		if ($normalGroup == null)
			$normalGroup = new \Library\Entities\groupe();
		
		$this->page()->addVar("listeGroupe", $this->managers()->getManagersOf("groupe")->getList(array("parent_id IN (" . $adminGroup->id() . ", " . $normalGroup->id() . ")")));
	}
	
	public function executeUpload(\Library\HTTPRequest $request) {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		@set_time_limit(5 * 60);
		
		//usleep(5000);
		
		$targetDir = __DIR__ . "/../../Upload/Document/";
		
		if (!file_exists($targetDir))
			mkdir($targetDir, 0777, true);
		
		if (!file_exists($targetDir))
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "failed to create directory"}, "id" : "id", "ok": "ko"}');
		
		$targetDir = realpath($targetDir);
		
		
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}
		
		$filePath = $targetDir  . "/" . $fileName;
		
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb"))
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open output stream."}, "id" : "id", "ok": "ko"}');
		
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"]))
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id", "ok": "ko"}');
		
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb"))
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id", "ok": "ko"}');
			
		} else
			if (!$in = @fopen("php://input", "rb"))
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id", "ok": "ko"}');

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
			$name = implode(".", $name) . "_" . uniqid() . "." . $ext;
			
			rename("{$filePath}.part", $targetDir . "/" . $name);
			
			$file = new \Library\Entities\file(array("file_name" => $name, "file_src" => $targetDir . "/", "file_pub_name" => $fileName));
			
			$this->managers()->getManagersOf("file")->send($file);
			
			die('{"jsonrpc" : "2.0", "result" : "finished", "file_id" : ' . $file->id() . ', "ok": "ok"}');
		}
		
		die('{"jsonrpc" : "2.0", "status" : "uploading", "chunks" : ' . $chunks . ', "current": ' . $chunk . ', "ok": "ok"}');
	}
	
	public function executeSetAccess(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		//Improve protection by checking that both the file and the groupe exist
		if (!($request->existPost("file_id") && is_numeric($file_id = $request->dataPost("file_id"))))
			$message[] = ERROR_ON_RETRIEVING_DATA;
		else {
			if ($request->existPost("groupes") && is_array($groupes = $request->dataPost("groupes"))) {
				$accessManager = $this->managers()->getManagersOf("access");
				
				foreach ($groupes AS $g)
					if (is_numeric($g))
						$accessManager->send(new \Modules\Document\Entities\access(array("groupe_id" => $g, "file_id" => $file_id)));
			}
			
			$valid = 1;
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		
		$this->page()->setIsJson();
	}
}


?>