<?php namespace Einherjars\Carbuncle\Sessions;
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

use Illuminate\Session\Store as SessionStore;

class IlluminateSession implements SessionInterface {

	/**
	 * The key used in the Session.
	 *
	 * @var string
	 */
	protected $key = 'einherjars_carbuncle';

	/**
	 * Session store object.
	 *
	 * @var \Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * Creates a new Illuminate based Session driver for Carbuncle.
	 *
	 * @param  \Illuminate\Session\Store  $session
	 * @param  string  $key
	 * @return void
	 */
	public function __construct(SessionStore $session, $key = null)
	{
		$this->session = $session;

		if (isset($key))
		{
			$this->key = $key;
		}
	}

	/**
	 * Returns the session key.
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Put a value in the Carbuncle session.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function put($value)
	{
		$this->session->put($this->getKey(), $value);
	}

	/**
	 * Get the Carbuncle session value.
	 *
	 * @return mixed
	 */
	public function get()
	{
		return $this->session->get($this->getKey());
	}

	/**
	 * Remove the Carbuncle session.
	 *
	 * @return void
	 */
	public function forget()
	{
		$this->session->forget($this->getKey());
	}

}
