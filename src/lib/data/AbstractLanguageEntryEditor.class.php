<?php
/**
 * Contains abstract class AbstractLanguageEntryEditor.
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
use wcf\system\WCF;

/**
 * Abstract class for language entry editors.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractLanguageEntryEditor extends DatabaseObjectEditor implements ILanguageEntryEditor, IEditableCachedObject {
	/**
	 * Name of cache class (FQCN).
	 * @var string
	 */
	protected static $cacheClass = '';
	
	/**
	 * Creates entries for one object.
	 * 
	 * @param	integer		$objectID
	 * @param	array		$data	associative array (languageID => (key => value))
	 * @return	integer[]	ids of created entries (languageID => entryID)
	 */
	public static function createEntries($objectID, array $data) {
		$keys = $values = array();
		$statementParameters = array();
		$languageIDs = array();
		foreach ($data as $languageID => $__data) {
			$keys[$languageID] = $values[$languageID] = '';
			foreach ($__data as $key => $value) {
				if ($key == 'languageID' || $key == static::getObjectIDName()) {
					continue;
				}
				if (!empty($keys[$languageID])) {
					$keys[$languageID] .= ',';
					$values[$languageID] .= ',';
				}

				$keys[$languageID] .= $key;
				$values[$languageID] .= '?';
				$statementParameters[$languageID][] = $value;
			}
			if (!empty($keys[$languageID])) {
				$languageIDs[] = $languageID;
				$statementParameters[$languageID] = array();
				$keys[$languageID] .= ',languageID,'.static::getObjectIDName();
				$values[$languageID] .= ',?,?';
				$statementParameters[$languageID][] = ($languageID ? $languageID : null);
				$statementParameters[$languageID][] = $objectID;
			}
		}
		
		// save entries
		$entryIDs = array();
		WCF::getDB()->beginTransaction();
		foreach ($languageIDs as $languageID) {
			$sql = 'INSERT INTO '.static::getDatabaseTableName().'
			                    ('.$keys[$languageID].')
			        VALUES      ('.$values[$languageID].')';
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($statementParameters[$languageID]);
			$entryIDs[$languageID] = WCF::getDB()->getInsertID(static::getDatabaseTableName(), static::getDatabaseTableIndexName());
		}
		WCF::getDB()->commitTransaction();
		
		return $entryIDs;
	}

	/**
	 * Updates entries for one object.
	 *
	 * Convenience method to delete existing entries and create new entries.
	 *
	 * @param	integer	$objectID
	 * @param	array	$data	associative array (languageID => (key => value))
	 */
	public static function updateEntries($objectID, array $data) {
		static::deleteEntries($objectID);
		static::createEntries($objectID, $data);
	}
	
	/**
	 * Deletes all entries of an object.
	 *
	 * @param	integer	$objectID
	 * @return	integer	amount of deleted entries
	 */
	public static function deleteEntries($objectID) {
		$sql = 'DELETE FROM '.static::getDatabaseTableName().'
		        WHERE       '.static::getObjectIDName().' = ?';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($objectID));
		return $statement->getAffectedRows();
	}
	
	/**
	 * Returns the object id name.
	 * 
	 * @return	string
	 */
	public static function getObjectIDName() {
		return call_user_func(array(static::$baseClass, 'getObjectIDName'));
	}
	
	/**
	 * @see \wcf\data\IEditableCachedObject::resetCache()
	 */
	public static function resetCache() {
		static::getCacheObject()->reloadCache();
	}
	
	/**
	 * Returns an object of the cache builder.
	 *
	 * @return	\wcf\data\AbstractLanguageEntryCache
	 */
	protected static function getCacheObject() {
		return call_user_func(array(static::$cacheClass, 'getInstance'));
	}
}
