<?php

class Zend_View_Helper_Truncate extends Zend_View_Helper_Abstract
{
   public function truncate($string, $length = 100, $postfix = '...')
   {

       if (strlen($string) > $length)
       {
           $string = wordwrap($string, $length);
           $string = substr($string, 0, strpos($string, "\n"));
           $string .= $postfix;
       }
       return $string;
   }
}