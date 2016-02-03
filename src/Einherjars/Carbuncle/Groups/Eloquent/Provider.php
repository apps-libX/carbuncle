<?php namespace Einherjars\Carbuncle\Groups\Eloquent;
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

use Einherjars\Carbuncle\Groups\GroupInterface;
use Einherjars\Carbuncle\Groups\GroupNotFoundException;
use Einherjars\Carbuncle\Groups\ProviderInterface;

class Provider implements ProviderInterface {

	/**
	 * The Eloquent group model.
	 *
	 * @var string
	 */
	protected $model = 'Einherjars\Carbuncle\Groups\Eloquent\Group';

	/**
	 * Create a new Eloquent Group provider.
	 *
	 * @param  string  $model
	 * @return void
	 */
	public function __construct($model = null)
	{
		if (isset($model))
		{
			$this->model = $model;
		}
	}

	/**
	 * Find the group by ID.
	 *
	 * @param  int  $id
	 * @return \Einherjars\Carbuncle\Groups\GroupInterface  $group
	 * @throws \Einherjars\Carbuncle\Groups\GroupNotFoundException
	 */
	public function findById($id)
	{
		$model = $this->createModel();

		if ( ! $group = $model->newQuery()->find($id))
		{
			throw new GroupNotFoundException("A group could not be found with ID [$id].");
		}

		return $group;
	}

	/**
	 * Find the group by name.
	 *
	 * @param  string  $name
	 * @return \Einherjars\Carbuncle\Groups\GroupInterface  $group
	 * @throws \Einherjars\Carbuncle\Groups\GroupNotFoundException
	 */
	public function findByName($name)
	{
		$model = $this->createModel();

		if ( ! $group = $model->newQuery()->where('name', '=', $name)->first())
		{
			throw new GroupNotFoundException("A group could not be found with the name [$name].");
		}

		return $group;
	}

	/**
	 * Returns all groups.
	 *
	 * @return array  $groups
	 */
	public function findAll()
	{
		$model = $this->createModel();

		return $model->newQuery()->get()->all();
	}

	/**
	 * Creates a group.
	 *
	 * @param  array  $attributes
	 * @return \Einherjars\Carbuncle\Groups\GroupInterface
	 */
	public function create(array $attributes)
	{
		$group = $this->createModel();
		$group->fill($attributes);
		$group->save();
		return $group;
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
