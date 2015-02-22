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


// $config = \devlog\json\JsonConfig::fromFile('../../config/devlog.json');
// if($config->get('devlog.database.type') !== 'mysql') {
// 	die('dev/log currently only supports MySQL.');
// }

// $database = new medoo([
// 	'charset' => $config->get('devlog.database.charset'),
// 	'database_type' => $config->get('devlog.database.type'),
// 	'port' => $config->get('devlog.database.port'),
// 	'server' => $config->get('devlog.database.server'),
// 	'database_name' => $config->get('devlog.database.name'),
// 	'username' => $config->get('devlog.database.user'),
// 	'password' => $config->get('devlog.database.password')
// ]);

// $setup = \devlog\database\DatabaseSetup::fromFile('./setup/database.xml');
// $setup->setArguments([
// 	'table-prefix' => 'devlog'
// ]);

// $setup->execute('mysql', function($query) use ($database) {
// 	return $database->query($query);
// });


namespace devlog\database {
	use \BadMethodCallException as BadMethodCallException;
	use \medoo as medoo;
	use \devlog\json\JsonConfig as JsonConfig;

	class DatabaseAdapter {
		private $medoo;
		private $prefix;

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
		}

		private function getQualifiedTableName($table) {
			return $this->prefix . '_' . $table;
		}

		private function singlify($array) {
			if(count($array) === 0)
				return null;

			return $array[0];
		}

		private function select($table) {
			$count = func_num_args();
			if($count < 3 || $count > 4)
				throw new BadMethodCallException('Method expects either three or four parameters.');

			$args = func_get_args();
			$args[0] = $this->getQualifiedTableName($table);
			return call_user_func_array([$this->medoo, 'select'], $args);
		}

		private function insert($table, array $data) {
			return $this->medoo->insert($this->getQualifiedTableName($table), $data);
		}

		public function userIdFromLogin($login) {
			$id = $this->select('users', 'id', [
				'email' => $login,
				'LIMIT' => 1
			]);

			return $this->singlify($id);
		}
	} // class DatabaseAdapter
} // namespace devlog\database

?>