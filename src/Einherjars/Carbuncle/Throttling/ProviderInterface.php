<?php namespace Einherjars\Carbuncle\Throttling;
use Einherjars\Carbuncle\Users\UserInterface;

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

interface ProviderInterface {


	/**
	 * Finds a throttler by the given user ID.
	 *
	 * @param  \Einherjars\Carbuncle\Users\UserInterface   $user
	 * @param  string  $ipAddress
	 * @return \Einherjars\Carbuncle\Throttling\ThrottleInterface
	 */
	public function findByUser(UserInterface $user, $ipAddress = null);

	/**
	 * Finds a throttler by the given user ID.
	 *
	 * @param  mixed   $id
	 * @param  string  $ipAddress
	 * @return \Einherjars\Carbuncle\Throttling\ThrottleInterface
	 */
	public function findByUserId($id, $ipAddress = null);

	/**
	 * Finds a throttling interface by the given user login.
	 *
	 * @param  string  $login
	 * @param  string  $ipAddress
	 * @return \Einherjars\Carbuncle\Throttling\ThrottleInterface
	 */
	public function findByUserLogin($login, $ipAddress = null);

	/**
	 * Enable throttling.
	 *
	 * @return void
	 */
	public function enable();

	/**
	 * Disable throttling.
	 *
	 * @return void
	 */
	public function disable();

	/**
	 * Check if throttling is enabled.
	 *
	 * @return bool
	 */
	public function isEnabled();

}
