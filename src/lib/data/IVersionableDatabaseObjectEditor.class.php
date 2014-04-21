<?php
/**
 * Contains interface IVersionableDatabaseObjectEditor.
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
 * Interface for all database object editors for versionable objects.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface IVersionableDatabaseObjectEditor {
	/**
	 * Creates a new version with the given data.
	 * 
	 * @api
	 * @param	array	$parameters	associative array
	 * @return	\wcf\data\IVersion
	 */
	public function createVersion($parameters);
	
	/**
	 * Updates the given version with the given data.
	 * 
	 * @api
	 * @param	integer	$versionID
	 * @param	array	$parameters	associative array
	 */
	public function updateVersion($versionID, $parameters);
	
	/**
	 * Deletes the given version.
	 * 
	 * @api
	 * @param	integer	$versionID
	 */
	public function deleteVersion($versionID);
	
	/**
	 * Deletes all given versions of a given object.
	 * 
	 * @param	integer	$objectID
	 * @param	array	$versionIDs	numerical array
	 * @return	count of affected rows
	 */
	public static function deleteAllVersions($objectID, array $versionIDs);
}
