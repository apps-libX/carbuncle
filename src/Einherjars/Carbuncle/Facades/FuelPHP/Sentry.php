<?php namespace Einherjars\Carbuncle\Facades\FuelPHP;
/**
 * Part of the Carbuncle package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Carbuncle
 * @version    2.0.0
 * @author     Einherjars LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Einherjars LLC
 * @link       http://einherjars.com
 */

use Einherjars\Carbuncle\Cookies\FuelPHPCookie;
use Einherjars\Carbuncle\Facades\ConnectionResolver;
use Einherjars\Carbuncle\Facades\Facade;
use Einherjars\Carbuncle\Groups\Eloquent\Provider as GroupProvider;
use Einherjars\Carbuncle\Hashing\NativeHasher;
use Einherjars\Carbuncle\Sessions\FuelPHPSession;
use Einherjars\Carbuncle\Carbuncle as BaseCarbuncle;
use Einherjars\Carbuncle\Throttling\Eloquent\Provider as ThrottleProvider;
use Einherjars\Carbuncle\Users\Eloquent\Provider as UserProvider;
use Database_Connection;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use PDO;
use Session;

class Carbuncle extends Facade {

	/**
	 * Creates a new instance of Carbuncle.
	 *
	 * @return \Einherjars\Carbuncle\Carbuncle
	 * @throws \RuntimeException
	 */
	public static function createCarbuncle()
	{
		// If Eloquent doesn't exist, then we must assume they are using their own providers.
		if (class_exists('Illuminate\Database\Eloquent\Model'))
		{
			// Retrieve what we need for our resolver
			$database    = Database_Connection::instance();
			$pdo         = $database->connection();
			$driverName  = $database->driver_name();
			$tablePrefix = $database->table_prefix();

			// Make sure we're getting a PDO connection
			if ( ! $pdo instanceof PDO)
			{
				throw new \RuntimeException("Carbuncle will only work with PDO database connections.");
			}

			Eloquent::setConnectionResolver(new ConnectionResolver($pdo, $driverName, $tablePrefix));
		}

		return new BaseCarbuncle(
			$userProvider = new UserProvider(new NativeHasher),
			new GroupProvider,
			new ThrottleProvider($userProvider),
			new FuelPHPSession(Session::instance()),
			new FuelPHPCookie,
			Input::real_ip()
		);
	}

}
