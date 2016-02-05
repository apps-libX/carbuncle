<?php namespace Einherjars\Carbuncle\Facades\Kohana;
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

use Einherjars\Carbuncle\Cookies\KohanaCookie;
use Einherjars\Carbuncle\Facades\Facade;
use Einherjars\Carbuncle\Groups\Kohana\Provider as GroupProvider;
use Einherjars\Carbuncle\Sessions\KohanaSession;
use Einherjars\Carbuncle\Carbuncle as BaseCarbuncle;
use Einherjars\Carbuncle\Throttling\Kohana\Provider as ThrottleProvider;
use Einherjars\Carbuncle\Users\Kohana\Provider as UserProvider;

class Carbuncle extends Facade {

	/**
	 * Creates a new instance of Carbuncle.
	 *
	 * @return \Einherjars\Carbuncle\Carbuncle
	 */
	public static function createCarbuncle()
	{
		$config = \Kohana::$config->load('carbuncle')->as_array();

		//If the user hasn't defined a config file offer defaults
		if ( count($config) == 0 )
		{
			$config = array(
				'session_driver' => 'native',
				'session_key' => 'einherjars_carbuncle',
				'cookie_key' => 'einherjars_carbuncle',
				'hasher' => 'Bcrypt'
			);
		}

		//Choose the hasher
		switch ( $config['hasher'] )
		{
			default:
			case 'Bcrypt':
				$hasher = new \Einherjars\Carbuncle\Hashing\BcryptHasher;
				break;
			case 'Native':
				$hasher = new \Einherjars\Carbuncle\Hashing\NativeHasher;
				break;
			case 'Sha256':
				$hasher = new \Einherjars\Carbuncle\Hashing\Sha256Hasher;
				break;
		}

		return new BaseCarbuncle(
			$userProvider = new UserProvider($hasher),
			new GroupProvider,
			new ThrottleProvider($userProvider),
			new KohanaSession(\Session::instance($config['session_driver']), $config['session_key']),
			new KohanaCookie($config['cookie_key']),
			\Request::$client_ip
		);
	}

}
