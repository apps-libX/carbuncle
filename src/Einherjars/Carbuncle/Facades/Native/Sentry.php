<?php namespace Einherjars\Carbuncle\Facades\Native;
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

use Einherjars\Carbuncle\Cookies\CookieInterface;
use Einherjars\Carbuncle\Cookies\NativeCookie;
use Einherjars\Carbuncle\Facades\ConnectionResolver;
use Einherjars\Carbuncle\Facades\Facade;
use Einherjars\Carbuncle\Groups\Eloquent\Provider as GroupProvider;
use Einherjars\Carbuncle\Groups\ProviderInterface as GroupProviderInterface;
use Einherjars\Carbuncle\Hashing\NativeHasher;
use Einherjars\Carbuncle\Sessions\NativeSession;
use Einherjars\Carbuncle\Sessions\SessionInterface;
use Einherjars\Carbuncle\Carbuncle as BaseCarbuncle;
use Einherjars\Carbuncle\Throttling\Eloquent\Provider as ThrottleProvider;
use Einherjars\Carbuncle\Throttling\ProviderInterface as ThrottleProviderInterface;
use Einherjars\Carbuncle\Users\Eloquent\Provider as UserProvider;
use Einherjars\Carbuncle\Users\ProviderInterface as UserProviderInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use PDO;

class Carbuncle extends Facade {

	/**
	 * Creates a Carbuncle instance.
	 *
	 * @param  \Einherjars\Carbuncle\Users\ProviderInterface $userProvider
	 * @param  \Einherjars\Carbuncle\Groups\ProviderInterface $groupProvider
	 * @param  \Einherjars\Carbuncle\Throttling\ProviderInterface $throttleProvider
	 * @param  \Einherjars\Carbuncle\Sessions\SessionInterface $session
	 * @param  \Einherjars\Carbuncle\Cookies\CookieInterface $cookie
	 * @param  string $ipAddress
	 * @return \Einherjars\Carbuncle\Carbuncle
	 */
	public static function createCarbuncle(
		UserProviderInterface $userProvider = null,
		GroupProviderInterface $groupProvider = null,
		ThrottleProviderInterface $throttleProvider = null,
		SessionInterface $session = null,
		CookieInterface $cookie = null,
		$ipAddress = null
	)
	{
		$userProvider = $userProvider ?: new UserProvider(new NativeHasher);

		return new BaseCarbuncle(
			$userProvider,
			$groupProvider    ?: new GroupProvider,
			$throttleProvider ?: new ThrottleProvider($userProvider),
			$session          ?: new NativeSession,
			$cookie           ?: new NativeCookie,
			$ipAddress        ?: static::guessIpAddress()
		);
	}

	/**
	 * Sets up the Eloquent Connection Resolver with the given PDO connection.
	 *
	 * @param  PDO    $pdo
	 * @param  string $driverName
	 * @param  string $tablePrefix
	 * @return void
	 */
	public static function setupDatabaseResolver(PDO $pdo, $driverName = null, $tablePrefix = '')
	{
		// If Eloquent doesn't exist, then we must assume they are using their own providers.
		if (class_exists('Illuminate\Database\Eloquent\Model'))
		{
			if (is_null($driverName))
			{
				$driverName = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
			}

			Eloquent::setConnectionResolver(new ConnectionResolver($pdo, $driverName, $tablePrefix));
		}
	}

	/**
	 * Looks through various server properties in an attempt
	 * to guess the client's IP address.
	 *
	 * @return string  $ipAddress
	 */
	public static function guessIpAddress()
	{
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
		{
			if (array_key_exists($key, $_SERVER) === true)
			{
				foreach (explode(',', $_SERVER[$key]) as $ipAddress)
				{
					$ipAddress = trim($ipAddress);

					if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
					{
						return $ipAddress;
					}
				}
			}
		}
	}

}
