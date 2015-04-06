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
	use \ReflectionClass as ReflectionClass;
	abstract class Enumeration {
		private static $cache = [];

		public static function getConstants() {
			$class = get_called_class();
			if(array_key_exists($class, self::$cache) === false) {
				self::$cache[$class] = (new ReflectionClass($class))->getConstants();
			}

			return self::$cache[$class];
		}

		public static function isValidName($name) {
			return array_key_exists($name, self::getConstants());
		}

		public static function isValidValue($value) {
			return in_array($value, array_values(self::getConstants()), true);
		}
	} // abstract class Enumeration
} // namespace devlog\system

?>