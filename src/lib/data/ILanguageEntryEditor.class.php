<?php
/**
 * Contains interface ILanguageEntryEditor.
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
 * Interface for language entry editors.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface ILanguageEntryEditor {
	/**
	 * Creates entries for one object.
	 * 
	 * @param	integer		$objectID
	 * @param	array		$data	associative array (languageID => (key => value))
	 * @return	integer[]	ids of created entries
	 */
	public static function createEntries($objectID, array $data);
	
	/**
	 * Updates entries for one object.
	 * 
	 * @param	integer	$objectID
	 * @param	array	$data	associative array (languageID => (key => value))
	 */
	public static function updateEntries($objectID, array $data);
	
	/**
	 * Deletes all entries of an object.
	 * 
	 * @param	integer	$objectID
	 * @return	integer	amount of deleted entries
	 */
	public static function deleteEntries($objectID);
}
