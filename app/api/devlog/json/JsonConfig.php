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

namespace devlog\json {
	use \Exception as Exception;

	class JsonConfig {
		private $config;

		public static function fromFile($filename) {
			$content = @file_get_contents($filename);
			if($content === false)
				throw new Exception('File could not be found.');

			$config = json_decode($content, true);
			if(json_last_error() !== JSON_ERROR_NONE)
				throw new Exception('JSON file is invalid.');

			return new JsonConfig($config);
		}

		private function __construct(array $config) {
			$this->config = $config;
		}

		private function getDefaultOrThrow($name, $defaultValue, $throwOnError) {
			if($throwOnError)
				throw new Exception("Key '$name' could not be found.");
			else
				return $defaultValue;
		}

		public function get($name) {
			$parts = explode('.', $name);
			$length = count($parts);
			$current = $this->config;
			$defaultValue = null;
			$throwOnError = true;

			if(func_num_args() >= 2) {
				$defaultValue = func_get_arg(1);
				$throwOnError = false;
			}

			if($length === 0)
				return $this->getDefaultOrThrow($name, $defaultValue, $throwOnError);

			if($length === 1) {
				if(isset($current[$parts[0]]))
					return $current[$parts[0]];
				else
					return $this->getDefaultOrThrow($name, $defaultValue, $throwOnError);
			}

			for($i = 0; $i < $length; $i++) {
				if(!isset($current[$parts[$i]]))
					return $this->getDefaultOrThrow($name, $defaultValue, $throwOnError);

				$current = $current[$parts[$i]];
			}

			return $current;
		}
	} // class JsonConfig
} // namespace devlog\json

?>