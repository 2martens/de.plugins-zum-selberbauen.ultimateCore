<?php
/**
 * Contains interface IVersion.
 * 
 * LICENSE:
 * This file is part of the Ultimate Core.
 *
 * The Ultimate Core is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * The Ultimate Core is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with the Ultimate Core. If not, see {@link http://www.gnu.org/licenses/}}.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
namespace wcf\data;

/**
 * Interface for all version classes.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface IVersion extends IStorableObject {
	/**
	 * Returns the number of this version.
	 * 
	 * The number of the version is unique in combination with the object ID.
	 * Don't mistake with the versionID.
	 * 
	 * @api
	 * @return	integer
	 */
	public function getVersionNumber();
	
	/**
	 * Checks if this version is already released.
	 * 
	 * @api
	 * @return	boolean
	 */
	public function isReleased();
	
	/**
	 * Checks if the version is visible for the current user.
	 * 
	 * @api
	 * @return	boolean
	 */
	public function isVisible();
	
	/**
	 * Returns the saved data.
	 * 
	 * @api
	 * @return	mixed[] associative array
	 */
	public function getData();
}
