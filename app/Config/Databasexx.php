<?php namespace Config;

/**
 * Database Configuration
 *
 * @package Config
 */

class Database extends \CodeIgniter\Database\Config
{
	/**
	 * The directory that holds the Migrations
	 * and Seeds directories.
	 *
	 * @var string
	 */
	public $filesPath = APPPATH . 'Database/';

	/**
	 * Lets you choose which connection group to
	 * use if no other is specified.
	 *
	 * @var string
	 */
	public $defaultGroup = 'admin';

	/**
	 * The default database connection.
	 *
	 * @var array
	 */

	/* MYSQL */
	// public $admin = [
	// 	'DSN'      => '',
	// 	'hostname' => '192.168.10.17',
	// 	'username' => 'root',
	// 	'password' => 'password',
	 //	'database' => 'db_template',
	 //	'DBDriver' => 'MySQLi',
	 //	'DBPrefix' => '',
	 //	'pConnect' => false,
	 //	'DBDebug'  => (ENVIRONMENT !== 'production'),
	 //	'cacheOn'  => false,
	 //	'cacheDir' => '',
	 //	'charset'  => 'utf8',
	 //	'DBCollat' => 'utf8_general_ci',
	 //	'swapPre'  => '',
	 //	'encrypt'  => false,
	 //	'compress' => false,
	 //	'strictOn' => false,
	 //	'failover' => [],
	 //	'port'     => 3306,
	 //];

	/* POSTGRES */
	public $admin = [
		'DSN'      => '',
	//	'hostname' => '192.168.10.10',
		'hostname' => '192.168.10.10',
		'username' => 'root',
		'password' => 'LMsystem321!!!',
	//	'password' => 'Juanda3',
		'database' => 'db_hris_daily',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 5432,
	];

	// public $trans = [
	// 	'DSN'      => '',
	// 	'hostname' => 'localhost',
	// 	'username' => 'root',
	// 	'password' => 'password',
	// 	'database' => 'db_admin_shp',
	// 	'DBDriver' => 'MySQLi',
	// 	'DBPrefix' => '',
	// 	'pConnect' => false,
	// 	'DBDebug'  => (ENVIRONMENT !== 'production'),
	// 	'cacheOn'  => false,
	// 	'cacheDir' => '',
	// 	'charset'  => 'utf8',
	// 	'DBCollat' => 'utf8_general_ci',
	// 	'swapPre'  => '',
	// 	'encrypt'  => false,
	// 	'compress' => false,
	// 	'strictOn' => false,
	// 	'failover' => [],
	// 	'port'     => 3306,
	// ];

	/**
	 * This database connection is used when
	 * running PHPUnit database tests.
	 *
	 * @var array
	 */
	public $tests = [
		'DSN'      => '',
		'hostname' => '127.0.0.1',
		'username' => '',
		'password' => '',
		'database' => ':memory:',
		'DBDriver' => 'SQLite3',
		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// Ensure that we always set the database group to 'tests' if
		// we are currently running an automated test suite, so that
		// we don't overwrite live data on accident.
		if (ENVIRONMENT === 'testing')
		{
			$this->defaultGroup = 'tests';

			// Under Travis-CI, we can set an ENV var named 'DB_GROUP'
			// so that we can test against multiple databases.
			if ($group = getenv('DB'))
			{
				if (is_file(TESTPATH . 'travis/Database.php'))
				{
					require TESTPATH . 'travis/Database.php';

					if (! empty($dbconfig) && array_key_exists($group, $dbconfig))
					{
						$this->tests = $dbconfig[$group];
					}
				}
			}
		}
	}

	//--------------------------------------------------------------------

}
