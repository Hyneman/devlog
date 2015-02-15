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
	use \DirectoryIterator as DirectoryIterator;
	use \flight\Engine as FlightEngine;

	class ApplicationController {
		private $config;
		private $flight;
		private $router;

		public function __construct(array $config) {
			$this->config = $config;
			$this->flight = new FlightEngine();
			$this->router = new ApplicationRouter($this->config, $this->flight);
			$this->overrideNotFound();
			$this->loadRoutes();
		}

		private function overrideNotFound() {
			$this->flight->map('notFound', function() {
				require DEVLOG_ERRORS . DEVLOG_SLASH . '404';
			});
		}

		private function isRouteFile($filename) {
			$suffix = 'Route.php';
			return (substr($filename, -strlen($suffix)) === $suffix);
		}

		private function loadRoutes() {
			$iterator = new DirectoryIterator(DEVLOG_ROUTES);

			foreach($iterator as $file) {
				$filename = $iterator->getFilename();

				if($file->isDot() || !$this->isRouteFile($filename))
					continue;

				$route = function($devlog, $__filename) {
					require_once $__filename;
				};

				$route($this->router, DEVLOG_ROUTES . DEVLOG_SLASH . $filename);
			}
		}

		public function config($name = '') {
			if(!isset($this->config[$name]))
				return '';

			return $this->config[$name];
		}

		public function service() {
			$this->flight->start();
		}
	} // class ApplicationController
} // namespace devlog

?>