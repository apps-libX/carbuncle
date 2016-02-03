<?php namespace Einherjars\Carbuncle\Cookies;
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

class FuelPHPCookie implements CookieInterface {

	/**
	 * The key used in the Cookie.
	 *
	 * @var string
	 */
	protected $key = 'einherjars_carbuncle';

	/**
	 * Create a new FuelPHP cookie driver for Carbuncle.
	 *
	 * @param  string  $key
	 */
	public function __construct($key = null)
	{
		if (isset($key))
		{
			$this->key = $key;
		}
	}

	/**
	 * Returns the cookie key.
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Put a value in the Carbuncle cookie.
	 *
	 * @param  mixed  $value
	 * @param  int    $minutes
	 * @return void
	 */
	public function put($value, $minutes)
	{
		\Cookie::set($this->getKey(), json_encode($value), $minutes);
	}

	/**
	 * Put a value in the Carbuncle cookie forever.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function forever($value)
	{
		// Forever can set a cookie for 5 years.
		// This should suffice "forever".
		$this->put($value, 2628000);
	}

	/**
	 * Get the Carbuncle cookie value.
	 *
	 * @return mixed
	 */
	public function get()
	{
		return json_decode(\Cookie::get($this->getKey()));
	}

	/**
	 * Remove the Carbuncle cookie.
	 *
	 * @return void
	 */
	public function forget()
	{
		\Cookie::delete($this->getKey());
	}

}
