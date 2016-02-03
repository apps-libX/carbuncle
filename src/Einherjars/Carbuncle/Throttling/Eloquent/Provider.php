<?php namespace Einherjars\Carbuncle\Throttling\Eloquent;
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

use Einherjars\Carbuncle\Throttling\ThrottleInterface;
use Einherjars\Carbuncle\Throttling\ProviderInterface;
use Einherjars\Carbuncle\Users\ProviderInterface as UserProviderInterface;
use Einherjars\Carbuncle\Users\UserInterface;

class Provider implements ProviderInterface {

	/**
	 * The Eloquent throttle model.
	 *
	 * @var string
	 */
	protected $model = 'Einherjars\Carbuncle\Throttling\Eloquent\Throttle';

	/**
	 * The user provider used for finding users
	 * to attach throttles to.
	 *
	 * @var \Einherjars\Carbuncle\Users\UserInterface
	 */
	protected $userProvider;

	/**
	 * Throttling status.
	 *
	 * @var bool
	 */
	protected $enabled = true;

	/**
	 * Creates a new throttle provider.
	 *
	 * @param \Einherjars\Carbuncle\Users\ProviderInterface $userProvider
	 * @param  string $model
	 * @return void
	 */
	public function __construct(UserProviderInterface $userProvider, $model = null)
	{
		$this->userProvider = $userProvider;

		if (isset($model))
		{
			$this->model = $model;
		}
	}

	/**
	 * Finds a throttler by the given Model.
	 *
	 * @param  \Einherjars\Carbuncle\Users\UserInterface $user
	 * @param  string  $ipAddress
	 * @return \Einherjars\Carbuncle\Throttling\ThrottleInterface
	 */
	public function findByUser(UserInterface $user, $ipAddress = null)
	{
		$model = $this->createModel();
		$query = $model->where('user_id', '=', ($userId = $user->getId()));

		if ($ipAddress)
		{
			$query->where(function($query) use ($ipAddress) {
				$query->where('ip_address', '=', $ipAddress);
				$query->orWhere('ip_address', '=', NULL);
			});
		}

		if ( ! $throttle = $query->first())
		{
			$throttle = $this->createModel();
			$throttle->user_id = $userId;
			if ($ipAddress) $throttle->ip_address = $ipAddress;
			$throttle->save();
		}

		return $throttle;
	}
	/**
	 * Finds a throttler by the given user ID.
	 *
	 * @param  mixed   $id
	 * @param  string  $ipAddress
	 * @return \Einherjars\Carbuncle\Throttling\ThrottleInterface
	 */
	public function findByUserId($id, $ipAddress = null)
	{
		return $this->findByUser($this->userProvider->findById($id),$ipAddress);
	}

	/**
	 * Finds a throttling interface by the given user login.
	 *
	 * @param  string  $login
	 * @param  string  $ipAddress
	 * @return \Einherjars\Carbuncle\Throttling\ThrottleInterface
	 */
	public function findByUserLogin($login, $ipAddress = null)
	{
		return $this->findByUser($this->userProvider->findByLogin($login),$ipAddress);
	}

	/**
	 * Enable throttling.
	 *
	 * @return void
	 */
	public function enable()
	{
		$this->enabled = true;
	}

	/**
	 * Disable throttling.
	 *
	 * @return void
	 */
	public function disable()
	{
		$this->enabled = false;
	}

	/**
	 * Check if throttling is enabled.
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel()
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class;
	}

	/**
	 * Sets a new model class name to be used at
	 * runtime.
	 *
	 * @param  string  $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}

}
