<?php namespace Einherjars\Carbuncle\Groups;
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
	 * Find the group by ID.
	 *
	 * @param  int  $id
	 * @return \Einherjars\Carbuncle\Groups\GroupInterface  $group
	 * @throws \Einherjars\Carbuncle\Groups\GroupNotFoundException
	 */
	public function findById($id);

	/**
	 * Find the group by name.
	 *
	 * @param  string  $name
	 * @return \Einherjars\Carbuncle\Groups\GroupInterface  $group
	 * @throws \Einherjars\Carbuncle\Groups\GroupNotFoundException
	 */
	public function findByName($name);

	/**
	 * Returns all groups.
	 *
	 * @return array  $groups
	 */
	public function findAll();

	/**
	 * Creates a group.
	 *
	 * @param  array  $attributes
	 * @return \Einherjars\Carbuncle\Groups\GroupInterface
	 */
	public function create(array $attributes);

}
