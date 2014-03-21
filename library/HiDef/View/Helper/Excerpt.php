<?php

class HiDef_View_Helper_Excerpt extends Zend_View_Helper_Abstract
{
	const EXCERPT_LENGTH = 30;

	public function init() {}

	public function excerpt($text, $length = null)
	{
		if ($length === null) {
			$length = self::EXCERPT_LENGTH;
		}

		if ( mb_strlen( $text ) > $length ) {
			$subex = mb_substr( strip_tags( $text ), 0, $length - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				$text = mb_substr( $subex, 0, $excut );
			} else {
				$text = $subex;
			}
			$text .= '...';
		}

		return $text;
	}
}
