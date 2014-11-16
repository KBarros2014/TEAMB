<?php
/*
	A PHP framework for web sites by Mike Lopez

    A product image upload management class
    ========================================

	Usage:
		1) Create an instance of the class with the name of the file input
		   field.  
		2) Call the upload() method. 
	
	Notes:
		1) JPG and PNG files are supported
		2) files are re-sampled to a standard size (640 x 480) and stored in
			/images/products/pnnn     [nnn is product ID]
		3) Thumbnails (80 x 60) are created by re-sampling and stored in
			/images/products/tnnn     [nnn is product ID]
*/

include 'lib/fileUploader.php';

class ProductImageUploader extends FileUploader {
	// standard sizes
	private $standardWidth = 640;
	private $standardHeight = 480;
	private $thumbnailScale = 8;
	// could change size to 800 x 600
	// could change thumbnails to 10 (64 x 48 for 640 x 480)

	private $imagePath;
	private $thumbnailPath;
	
	public function __construct($formName, $id) {
		$uploadFolder='images/products';
		parent::__construct($formName, $uploadFolder, 2000000) ;
		$this->allowType('jpg','image/jpeg');
		$this->allowType('png','image/png');	
		$this->setFilename('p'.$id);
		$this->imagePath = $uploadFolder.'/p'.$id;
		$this->thumbnailPath = $uploadFolder.'/t'.$id;
	}
	public function upload() {
		$filepath=parent::upload(); 
		$extension = substr($filepath, strlen($filepath)-4 , 4);
		$this->imagePath.=$extension;
		$this->thumbnailPath.=$extension;
	//	echo "Making standard image in $this->imagePath<br/>";
		$this->makeStandardImage($this->imagePath);
	//	echo "Making thumbnail in $this->thumbnailPath<br/>";
		$this->makeThumbnail($this->imagePath, $this->thumbnailPath);
	}
	private function getImage($sourceFile) {
		$extension = substr($sourceFile, strlen($sourceFile)-4 , 4);
		switch($extension) {
			case '.jpg':
				return imagecreatefromjpeg($sourceFile);
			case '.png':
		//	echo "Loading PNG from $sourceFile<br/>";
				$img=imagecreatefrompng($sourceFile);
				if ($img) {
					return $img;
				}
		//	echo "Creating empty image<br/>";
				
				/* if it failed, create a blank image */
				$img = imagecreatetruecolor(640, 480);
				$bgc = imagecolorallocate($img, 255, 192, 192);
				$tc  = imagecolorallocate($img, 0, 0, 0);
				imagefilledrectangle($img, 0, 0, 150, 30, $bgc);
				/* Output an error message */	
				imagestring($img, 1, 5, 5, 'Error loading ' . $sourceFile, $tc);
				return $img;
		}
		throw new LogicException('format not supported');
	}
	private function makeStandardImage($sourceFile) {
		
		// 1) check detail of uploaded image
		list($width, $height, $type) = getimagesize($sourceFile);
		if ( $width == $this->standardWidth &&
			$height == $this->standardHeight ) {
			return;
		}
		
		// 2) create an empty image of the right size
	 	
		$aspectActual = $width / $height;
		$aspectStandard = $this->standardWidth / $this->standardHeight;
		
		if ( $aspectActual > $aspectStandard) {
		//	 we are "wide-ish" -set width and have a smaller height
			$w = $this->standardWidth;
			$h = floor ($w / $aspectStandard);
		} else {
			$h = $this->standardHeight;
			$w = floor($aspectStandard * $h );		
		}
		$newImage = imagecreatetruecolor( $w, $h );
		
		// 3) get the source image
		$sourceImage = $this->getImage($sourceFile);

		// 4) re-sample to create best possible quality thumbnail
		imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $w, $h, $width, $height);

		// 5) Save the image in place by overwriting
		switch ($type) {
			// case IMG_JPG:  // same value as JPEG
			case IMG_JPEG:
				imagejpeg ($newImage, $sourceFile, 80); // 80% quality
				break;		
			case IMG_PNG:
				imagepng ($newImage, $sourceFile, 9);
				break;
			default:
				throw new LogicException('image type not supported');
		}
		imagedestroy($sourceImage);	// free memory
		imagedestroy($newImage);	// free memory
	}	
	private function makeThumbnail($sourceFile, $targetFile) {
		// our thumbnails will be 1/thumbnailScale of the standard image size
		// e.g. they will be 64 x 48 for a standard size of 640 x 480 with scaling 10
	
		// 1) create an empty image of the right size
		list($width, $height, $type) = getimagesize($sourceFile);
		$w = $width / $this->thumbnailScale;
		$h = $height / $this->thumbnailScale;
		$thumbnail = imagecreatetruecolor( $w, $h );
		
		// 2) get the source image
		$sourceImage = $this->getImage ($sourceFile);
		
		// 3) re-sample to create best possible quality thumbnail
		imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $w, $h, $width, $height);
		
		// 4) save the thumbnail
		switch ($type) {
			// case IMG_JPG:  // same value as JPEG
			case IMG_JPEG:
				imagejpeg ($thumbnail, $targetFile, 80); // 80% quality
				break;		
			case IMG_PNG:
				imagepng ($thumbnail, $targetFile, 9);
				break;
			default:
				throw new LogicException('image type not supported');
		}
		imagedestroy($sourceImage);	// free memory
		imagedestroy($thumbnail);	// free memory
	}
}
?>