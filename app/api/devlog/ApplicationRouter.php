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

namespace devlog {
	use \flight\Engine as FlightEngine;
	use \devlog\json\JsonConfig as JsonConfig;
	use \devlog\database\DatabaseAdapter as DatabaseAdapter;

	class ApplicationRouter {
		private $config;
		private $flight;
		private $database;

		public function __construct(JsonConfig $config,
			FlightEngine $flight,
			DatabaseAdapter $database)
		{
			$this->config = $config;
			$this->flight = $flight;
			$this->database = $database;
		}

		public function config($name) {
			return $this->config->get($name);
		}

		public function database() {
			return $this->database;
		}

		public function route($url, $type, callable $callable) {
			header('Content-Type: ' . $type);
			$this->flight->route($url, $callable);
		}

		public function json($code, $content) {
			$json = json_encode($content,
				$this->config('devlog.mode') === 'dev' ? JSON_PRETTY_PRINT : 0);

			if($json === false)
				$this->error(500);

			$this->flight->response(false)
				->status($code)
				->header('Content-Type', 'application/json')
				->write($json)
				->send();
		}

		public function error($code, $message = '') {
			if(empty($message)) {
				$filename = DEVLOG_ERRORS . DEVLOG_SLASH . $code;
				if(file_exists($filename))
					$message = file_get_contents($filename);
			}

			$this->flight->halt($code, $message);
		}
	} // class ApplicationRouter
} // namespace devlog

?>