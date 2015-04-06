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

namespace devlog\system\logging {
	use \RuntimeException as RuntimeException;
	use \devlog\system\logging\LogLevel as LogLevel;

	class LogFile {
		private $handle;
		private $levels;
		private $lastLine;

		public function __construct($filename, $levels = LogLevel::ALL) {
			$this->handle = fopen($filename, 'a');
			if(!$this->handle) {
				throw new RuntimeException('The log file could not be opened.');
			}

			$this->levels = $levels;
			$this->lastLine = '';
		}

		public function __destruct() {
			if($this->handle) {
				fclose($this->handle);
			}
		}

		private function getTimeStamp() {
			return date('Y-m-d H:i:s');
		}

		private function sanitizeMessage($message) {
			return mb_ereg_replace('\s+', ' ', $message);
		}

		private function formatMessage($message, $level) {
			return '[' . $this->getTimeStamp() . ']'
				. ' ' . LogLevel::nameOf($level)
				. ' | ' . $this->sanitizeMessage($message)
				. PHP_EOL;
		}

		private function logMessage($message, $level) {
			$formatted = $this->formatMessage($message, $level);

			if(fwrite($this->handle, $formatted) === false) {
				return false;
			}
			else {
				fflush($this->handle);
				$this->lastLine = $formatted;
				return true;
			}
		}

		public function setLevels($levels) {
			if(LogLevel::isValidValue($levels) === false)
				return false;

			$this->levels = $levels;
		}

		public function getLevels() {
			return $this->levels;
		}

		public function getLastLine() {
			return $this->lastLine;
		}

		public function write($message, $level = LogLevel::ALL) {
			if($this->levels & $level)
				return $this->logMessage($message, $level);

			return;
		}

		public function alert($message) {
			return $this->write($message, LogLevel::ALERT);
		}

		public function error($message) {
			return $this->write($message, LogLevel::ERROR);
		}

		public function warning($message) {
			return $this->write($message, LogLevel::WARNING);
		}

		public function notice($message) {
			return $this->write($message, LogLevel::NOTICE);
		}

		public function debug($message) {
			return $this->write($message, LogLevel::DEBUG);
		}

		public function trace($message) {
			return $this->write($message, LogLevel::TRACE);
		}
	} // class LogFile
} // namespace devlog\system\logging

?>