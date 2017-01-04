<?php 

namespace ftpgallery;

class plunderer {
	protected $ftp;

	public function __construct() {
		$this->ftp = $this->connect();
	}

	/**
	 * Lists all the contents of the provided directory.
	 */
	public function listdir($directory = false) {
		if (!$directory) {
			$directory = getenv('FTP_DIRECTORY');
		}

		$dirList = $this->ftp->scanDir( $directory );

		$formattedList = new \stdClass();
		$formattedList->Location = $directory;
		$formattedList->Directories = [];
		$formattedList->Files = [];

		foreach ($dirList as $dirEntry) {
			switch($dirEntry["type"]) {
				case "file":
					array_push($formattedList->Files, $dirEntry["name"] );
					break;
				case "directory":
					array_push($formattedList->Directories, $dirEntry["name"] );
					break;
				default:
					return false;
			}
		}

		return $formattedList;
	}

	public function imagepick($ftpFilepath) {
		switch ( pathinfo($ftpFilepath, PATHINFO_EXTENSION) ) {
			case "jpg":
			case "jpeg":
			case "png":
			case "gif":
				$success = $this->ftp->get(__DIR__.'/cache/temp.'.pathinfo($ftpFilepath, PATHINFO_EXTENSION),$ftpFilepath, FTP_BINARY);
				
				if ($success) {
					$file = base64_encode(file_get_contents(__DIR__.'/cache/temp.'.pathinfo($ftpFilepath, PATHINFO_EXTENSION)));
					unlink(__DIR__.'/cache/temp.'.pathinfo($ftpFilepath, PATHINFO_EXTENSION));
					return 'data:image/'.pathinfo($ftpFilepath, PATHINFO_EXTENSION).';base64,'.$file;
				} else {
					return false;
				}
			default:
				return false;
		}
		
	}

	/**
	 *	Connects to the FTP source.
	 */
	private function connect() {
		$ftp = new \FtpClient\FtpClient();
		$ftp->connect( getenv('FTP_HOST'), (boolean)getenv('FTP_SSL'), getenv('FTP_PORT') );
		$ftp->login( getenv('FTP_USER'), getenv('FTP_PASS') );

		return $ftp;
	}
}