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

namespace devlog\database {
	use \LogicException as LogicException;
	use \devlog\exceptions\FileNotFoundException as FileNotFoundException;

	class DatabaseSetup {
		private $xml;
		private $arguments;

		public static function fromFile($filename) {
			$xml = simplexml_load_file($filename);
			if($xml === false)
				throw new FileNotFoundException($filename);

			return new DatabaseSetup($xml);
		}

		private function __construct($xml) {
			$this->xml = $xml;
			$this->arguments = [];
		}

		private function checkArguments() {
			$defined = $this->xml->xpath('/database-setup/arguments/require');
			foreach($defined as $current) {
				$name = (string)$current->attributes()->{'name'};
				if(array_key_exists($name, $this->arguments) === false)
					throw new LogicException("Argument '$name' is missing.");
			}
			return true;
		}

		private function prepareQuery($query) {
			foreach($this->arguments as $name => $value) {
				$query = str_replace("[[@$name@]]", $value, $query);
			}
			return $query;
		}

		public function getArguments() {
			return $this->arguments;
		}

		public function setArguments(array $arguments) {
			$this->arguments = $arguments;
		}

		public function execute($type, callable $callable) {
			$this->checkArguments();
			$queries = $this->xml->xpath("/database-setup/database[@type='$type']/statements/query");
			foreach($queries as $current) {
				$current = $this->prepareQuery($current);
				if(!$callable($current))
					return false;
			}
			return true;
		}
	} // class DatabaseSetup
} // namespace devlog\database

?>