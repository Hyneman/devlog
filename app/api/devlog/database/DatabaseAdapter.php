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
	use \BadMethodCallException as BadMethodCallException;
	use \PDO as PDO;
	use \medoo as medoo;
	use \devlog\json\JsonConfig as JsonConfig;
	use \devlog\exceptions\DatabaseQueryException as DatabaseQueryException;

	class DatabaseAdapter {
		private $medoo;
		private $prefix;
		private $sessionIdle;

		public function __construct(JsonConfig $config) {
			$this->medoo = $database = new medoo([
				'charset' => $config->get('devlog.database.charset'),
				'database_type' => $config->get('devlog.database.type'),
				'port' => $config->get('devlog.database.port'),
				'server' => $config->get('devlog.database.server'),
				'database_name' => $config->get('devlog.database.name'),
				'username' => $config->get('devlog.database.user'),
				'password' => $config->get('devlog.database.password')
			]);

			$this->prefix = $config->get('devlog.database.table-prefix');
			$this->sessionIdle = $config->get('devlog.system.access.session-idle');
		}

		private function qualifyTable($table) {
			return $this->prefix . '_' . $table;
		}

		private function singlify($array) {
			if(count($array) === 0)
				return null;

			return $array[0];
		}

		private function date($offset = 0) {
			return date('Y-m-d H:i:s', time() + $offset);
		}

		private function token($entropy = '') {
			return hash('sha256', $entropy . uniqid($entropy, true));
		}

		private function pdo() {
			return $this->medoo->pdo;
		}

		private function quote($string) {
			return $this->medoo->quote($string);
		}

		private function query($query) {
			return $this->medoo->query($query);
		}

		private function select($table) {
			$count = func_num_args();
			if($count < 3 || $count > 4)
				throw new BadMethodCallException('Method expects either three or four parameters.');

			$args = func_get_args();
			$args[0] = $this->qualifyTable($table);
			return call_user_func_array([$this->medoo, 'select'], $args);
		}

		private function insert($table, array $data) {
			return $this->medoo->insert($this->qualifyTable($table), $data);
		}

		public function userIdFromLogin($login) {
			$id = $this->select('users', 'id', [
				'email' => $login,
				'LIMIT' => 1
			]);

			return $this->singlify($id);
		}

		public function requestSession($login) {
			$sessionsTable = $this->qualifyTable('sessions');
			$usersTable = $this->qualifyTable('users');

			$statement = $this->pdo()->prepare("INSERT INTO `$sessionsTable` (user_id, session, challenge, created_on, expires_on)"
				. " SELECT id, :session, :challenge, :created_on, :expires_on FROM `$usersTable` WHERE email=:email");

			$session = $this->token($login);
			$challenge = $this->token($session);
			$createdOn = $this->date();
			$expiresOn = $this->date($this->sessionIdle);
			$email = $login;

			$statement->bindParam(':session', $session, PDO::PARAM_STR);
			$statement->bindParam(':challenge', $challenge, PDO::PARAM_STR);
			$statement->bindParam(':created_on', $createdOn, PDO::PARAM_STR);
			$statement->bindParam(':expires_on', $expiresOn, PDO::PARAM_STR);
			$statement->bindParam(':email', $email, PDO::PARAM_STR);

			if($statement->execute() === false || $statement->rowCount() === 0)
				return null;

			return (object)[
				'session' => $session,
				'challenge' => $challenge
			];
		}
	} // class DatabaseAdapter
} // namespace devlog\database

?>