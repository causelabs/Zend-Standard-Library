<?php

class HiDef_View_Helper_Truncate
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