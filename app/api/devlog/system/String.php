<?php
//
//// DEV/LOG: ISSUE TRACKING SYSTEM FOR DEVELOPERS.
//// COPYRIGHT (C) 2015 BY RICO BETI <rico.beti@silentbyte.com>.
////
//// THIS FILE IS PART OF DEV/LOG.
////
//// DEV/LOG IS FREE SOFTWARE: YOU CAN REDISTRIBUTE IT AND/OR MODIFY IT
//// UNDER THE TERMS OF THE GNU GENERAL PUBLIC LICENSE AS PUBLISHED BY
//// THE FREE SOFTWARE FOUNDATION, EITHER VERSION 3 OF THE LICENSE, OR
//// (AT YOUR OPTION) ANY LATER VERSION.
////
//// DEV/LOG IS DISTRIBUTED IN THE HOPE THAT IT WILL BE USEFUL,
//// BUT WITHOUT ANY WARRANTY; WITHOUT EVEN THE IMPLIED WARRANTY OF
//// MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. SEE THE
//// GNU GENERAL PUBLIC LICENSE FOR MORE DETAILS.
////
//// YOU SHOULD HAVE RECEIVED A COPY OF THE GNU GENERAL PUBLIC LICENSE
//// ALONG WITH DEV/LOG. IF NOT, SEE <http://www.gnu.org/licenses/>.
//

namespace devlog\system {
	use \Countable as Countable;

	final class String implements Countable {
		const INTERNAL_ENCODING = 'UTF-8';

		private $value;
		private $encoding;
		private $length;

		public static function areEquals(String $first, String $second) {
			return ($first === $second)	|| (($first->length === $second->length)
					&& ($first->value === $second->value));
		}

		public static function isNullOrEmpty(String $string = null) {
			return ($string === null)
				|| ($string->length === 0);
		}

		public static function isNullOrWhiteSpace(String $string = null) {
			return ($string === null)
				|| ($string->length === 0)
				|| mb_ereg_match('^\s*$', $string->value);
		}

		public function __construct($string, $encoding = null) {
			$this->encoding = self::INTERNAL_ENCODING;
			$this->value = mb_convert_encoding($string, $this->encoding,
				($encoding !== null ? $encoding : mb_internal_encoding()));
			$this->length = mb_strlen($this->value, $this->encoding);
		}

		public function string() {
			return $this->value;
		}

		public function length() {
			return $this->length;
		}

		public function encoding() {
			return $this->encoding;
		}

		public function equals(String $string) {
			return self::areEquals($this, $string);
		}

		public function encode($encoding) {
			return mb_convert_encoding($this->value, $encoding, $this->encoding);
		}

		public function lower() {
			return new String(mb_strtolower($this->value, $this->encoding), $this->encoding);
		}

		public function upper() {
			return new String(mb_strtoupper($this->value, $this->encoding), $this->encoding);
		}

		public function trim() {
			return new String(trim($this->value), $this->encoding);
		}

		public function count() {
			return $this->length;
		}

		public function __toString() {
			return $this->encode(mb_internal_encoding());
		}
	} // final class String
} // namespace devlog\system

?>