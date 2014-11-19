<?php
/*
	A PHP framework for web sites by Mike Lopez

    A file upload management class
    ==============================

	Usage:
		1) Create an instance of the class with the name of the file input
		   field and the folder you want to upload to. Optionally, set the 
           maximum upload size in bytes (default is two million bytes).
		   
		2) Set the file extensions and mime types you want to allow.
		
		3) (Optionally) Set the file name you want to use. If you skip this 
		   step, a random name will be allocated based on the file contents.
		   
		4) Call the upload() method. This will return the file name used. 
		
	Here are some common extensions and mime types ...
	Extension	Mime type
	jpg			image/jpeg
    png 		image/png
    gif 		image/gif
	txt         text/plain
	
	Do an internet search for more mime types! 
	
	note, this uses the php fileinfo extension
*/

class FileUploader {

	private $formName;
	private $uploadFolder;
	private $uploadLimit;
	private $allowedTypes;
	private $fileName;
	
	public function __construct($formName, $uploadFolder, $uploadLimit=2000000) {
		$this->formName=$formName;
		$this->uploadFolder=$uploadFolder;
		$this->uploadLimit=$uploadLimit;
		$allowedTypes=array();
		$this->fileName=null;
	}
	public function allowType ($extension, $mimeType)  {
		$this->allowedTypes[$extension]=$mimeType;
	}
	public function setFileName ($fileName) {
		$this->fileName=$fileName;
	}
	public function upload() {
		/*
			We don't trust anything from the client so we start with 
			some sanity checks. We expect a single file with the right
			form name and a properly formed error structure.
		*/

		if (!isset($_FILES[$this->formName]) ) {
			throw new RuntimeException(
				'Invalid form name');
		}
		$file=$_FILES[$this->formName];		
		if (!isset($file['name']) || 
			!isset($file['type']) ||
			!isset($file['tmp_name']) ||
			!isset($file['error']) ||
			!isset($file['size']) 
			) {
			throw new RuntimeException(
				'Invalid files array');
		}
		$name=$file['name'];
		$error=$file['error'];
		$tempFile=$file['tmp_name'];  
		$size=$file['size'];  
		if ( is_array($name)) {
			throw new RuntimeException(
				'multiple files selected.');
		}

		// now check the error code
	
		switch ($error) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException('No file uploaded.');
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException('File size exceeded upload limit.');
			default:
				throw new RuntimeException('Unspecified upload error.');
		}

		// Check the file is within our limit. 
		if ($size > $this->uploadLimit) {
			throw new RuntimeException('Upload exceeded file size limit.');
		}

		/* We'll check the mime type ourself since we don't trust 
		   data from the user. We'll make sure the mime type is what
		   we're expecting and will choose an extension that makes 
		   sense to us, not what the user tells us.
		*/
	
		$finfo = new finfo(FILEINFO_MIME_TYPE);	// note: requires fileinfo extension
		$mimeType=$finfo->file($tempFile);
	
		$extension = array_search($mimeType, $this->allowedTypes,true);
		if ($extension===false) {
			throw new RuntimeException("Invalid file format (mime type $mimeType)");
		}
		
		/* We don't trust the name given by the user so unless the 
		   fileName has been set via setFileName, we'll generate a
     	   random name from the file contents.
		*/
		$temp=$this->fileName;     // name set by our site, e.g. product ID
		if ($temp===null) {
			$temp=sha1_file($tempFile);
		}
		$targetName="$temp.$extension";
		
		// Now we'll try to store the file
		if (!move_uploaded_file($tempFile,$this->uploadFolder.'/'.$targetName)) {
			throw new RuntimeException('Could not store the uploaded file');
		}	
		
		// All  OK - return the name we've used 
		return $targetName;
	}
}
?>