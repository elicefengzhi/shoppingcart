<?php 

namespace Image\Image;

class Validate
{
	private $mimeType = array('image/jpg','image/jpeg','image/png','image/gif');
	
	public function IsVal($file)
	{
		if(!is_file($file)) {
			throw new \Exception("image validate file path undefined");
			return false;
		}

		$mimeType = new \Zend\Validator\File\MimeType($this->mimeType);
		$mimeType->setOptions(array('magicFile' => false));
		if($mimeType->isValid($file) === false) {
			throw new \Exception("image validate MimeType error , MimeType : image/jpg image/jpeg image/png image/gif");
			return false;
		}

		return true;
	}
}