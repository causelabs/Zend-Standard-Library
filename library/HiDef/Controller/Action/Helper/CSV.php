<?php
/**
 * Simple action helper for outputting a CSV file
 *
 * Takes a file path, header array, and data array to write/output a comma-
 * separated-values file to the local filesystem.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller_Action
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Simple action helper for outputting a CSV file
 *
 * Takes a file path, header array, and data array to write/output a comma-
 * separated-values file to the local filesystem.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller_Action
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Controller_Action_Helper_CSV extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Writes a CSV file to the local filesystem
	 * 
	 * @param  string $filepath Absolute filepath to which to write the CSV file
	 * @param  array $data     Array containing data to write
	 * @param  array $header   One-row array containing header to write
	 * @return void
	 */
	public function generate($filepath, $data, $header)
	{
		if ($fp = fopen($filepath, 'w'))
		{
			$show_header = true;
			if (empty($header))
			{
				$show_header = false;
				reset($data);
				$line = current($data);
				if ( !empty($line) )
				{
					reset($line);
					$first = current($line);
					if ( substr($first, 0, 2) == 'ID' && !preg_match('/["\\s,]/', $first) ) {
						array_shift($data);
						array_shift($line);
						if ( empty($line) ) {
							fwrite($fp, "\"{$first}\"\r\n");
						} else {
							fwrite($fp, "\"{$first}\",");
							fputcsv($fp, $line);
							fseek($fp, -1, SEEK_CUR);
							fwrite($fp, "\r\n");
						}
					}
				}
			} else {
				reset($header);
				$first = current($header);
				if ( substr($first, 0, 2) == 'ID' && !preg_match('/["\\s,]/', $first) ) {
					array_shift($header);
					if ( empty($header) ) {
						$show_header = false;
						fwrite($fp, "\"{$first}\"\r\n");
					} else {
						fwrite($fp, "\"{$first}\",");
					}
				}
			}
			if ( $show_header ) {
				fputcsv($fp, $header);
				fseek($fp, -1, SEEK_CUR);
				fwrite($fp, "\r\n");
			}
			foreach ( $data as $line ) {
				fputcsv($fp, $line);
				fseek($fp, -1, SEEK_CUR);
				fwrite($fp, "\r\n");
			}
			fclose($fp);
		} else {
			return false;
		}
		return true;
	}
}

?>