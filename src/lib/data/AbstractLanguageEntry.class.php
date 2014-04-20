<?php
/**
 * Contains the AbstractLanguageEntry class.
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
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Abstract class for all language entries.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractLanguageEntry extends DatabaseObject implements ILanguageEntry {
	/**
	 * name of the primary index column
	 * @var	string
	 */
	protected static $databaseTableIndexName = 'languageEntryID';
	
	/**
	 * Name of the object id.
	 * @var string
	 */
	protected static $objectIDName = '';
	
	/**
	 * Initializes a new instance of the AbstractLanguageEntry class.
	 * 
	 * One of $languageEntryID, ($objectID, $languageID), $row or $object must be given and unequal to null.
	 *
	 * @param	integer|null					$languageEntryID
	 * @param	integer|null					$objectID	must be given together with $languageID
	 * @param	integer|null					$languageID	must be given together with $objectID
	 * @param	array|null						$row		associative array
	 * @param	\wcf\data\ILanguageEntry|null	$object
	 */
	public function __construct($languageEntryID, $objectID = null, $languageID = null, array $row = null, ILanguageEntry $object = null) {
		if ($languageEntryID !== null) {
			$sql = 'SELECT *
			        FROM   '.static::getDatabaseTableName().'
			        WHERE  '.static::getDatabaseTableIndexName().' = ?';
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($languageEntryID));
			$row = $statement->fetchArray();
				
			// enforce data type 'array'
			if ($row === false) $row = array();
		}
		else if ($objectID !== null && $languageID !== null) {
			$sql = 'SELECT *
			        FROM   '.static::getDatabaseTableName().'
			        WHERE  '.static::$objectIDName.' = ?
			        AND    languageID                = ?';
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($objectID, ($languageID ? $languageID : null)));
			$row = $statement->fetchArray();
			
			// enforce data type 'array'
			if ($row === false) $row = array();
		}
		else if ($object !== null) {
			$row = $object->data;
		}
	
		$this->handleData($row);
	}
	
	/**
	 * @see \wcf\data\ILanguageEntry::getLanguage()
	 */
	public function getLanguage() {
		return LanguageFactory::getInstance()->getLanguage($this->languageID);
	}
	
	/**
	 * Returns the object id name.
	 * 
	 * @return	string
	 */
	public static function getObjectIDName() {
		return static::$objectIDName;
	}
}
