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

use Illuminate\Container\Container;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class IlluminateCookie implements CookieInterface {

	/**
	 * The key used in the Cookie.
	 *
	 * @var string
	 */
	protected $key = 'einherjars_carbuncle';

	/**
	 * The cookie object.
	 *
	 * @var \Illuminate\Cookie\CookieJar
	 */
	protected $jar;

	/**
	 * The cookie to be stored.
	 *
	 * @var \Symfony\Component\HttpFoundation\Cookie
	 */
	protected $cookie;

	/**
	 * The strategy to be used when retrieving the cookie.
	 *
	 * Must be either 'request' or 'jar'. This has to do with the fact that
	 * Laravel changed how cookies are accessed between 4.0 and 4.1 versions. If
	 * used with Laravel 4.0, this should be 'jar', but for Laravel 4.1 it
	 * should be 'request'. For further information see issue #325 in the
	 * einherjars/carbuncle repo.
	 *
	 * @link https://github.com/einherjars/carbuncle/issues/325
	 * @var string
	 */
	protected $strategy;

	/**
	 * Creates a new cookie instance.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Cookie\CookieJar  $jar
	 * @param  string  $key
	 * @return void
	 */
	public function __construct(Request $request, CookieJar $jar, $key = null, $strategy = 'request')
	{
		$this->request = $request;
		$this->jar = $jar;
		$this->strategy = $strategy;

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
		$cookie = $this->jar->make($this->getKey(), $value, $minutes);
		$this->jar->queue($cookie);
	}

	/**
	 * Put a value in the Carbuncle cookie forever.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function forever($value)
	{
		$cookie = $this->jar->forever($this->getKey(), $value);
		$this->jar->queue($cookie);
	}

	/**
	 * Get the Carbuncle cookie value.
	 *
	 * @return mixed
	 */
	public function get()
	{
		$key = $this->getKey();
		$queued = $this->jar->getQueuedCookies();

		if (isset($queued[$key]))
		{
			return $queued[$key];
		}

		if ($this->strategy === 'request')
		{
			return $this->request->cookie($key);
		}
		else
		{
			return $this->jar->get($key);
		}
	}

	/**
	 * Remove the Carbuncle cookie.
	 *
	 * @return void
	 */
	public function forget()
	{
		$cookie = $this->jar->forget($this->getKey());
		$this->jar->queue($cookie);
	}

}
