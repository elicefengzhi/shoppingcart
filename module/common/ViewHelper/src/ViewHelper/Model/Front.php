<?php

namespace ViewHelper\Model;
 
use ViewHelper\Model\BaseViewHelper;
 
class Front extends BaseViewHelper
{
    public function __invoke()
    {
        return $this;
    }
}