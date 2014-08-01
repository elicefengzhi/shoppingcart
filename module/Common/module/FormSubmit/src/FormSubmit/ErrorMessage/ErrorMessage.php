<?php

$translator = new \Zend\I18n\Translator\Translator;

return array(
	'maxSizeError'  => $translator->translate("Single file can not be larger than %s"),
	'minSizeError'  => $translator->translate("Single file can not be less than %s"),
	'mimeTypeError' => $translator->translate("Please upload the correct type of file"),
	'existsError'   => $translator->translate("This data already exists"),
);