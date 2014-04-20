<?php
/**
 * Contains abstract class AbstractLanguageEntryCacheBuilder.
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
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
namespace wcf\system\cache\builder;
use wcf\system\cache\builder\AbstractCacheBuilder;
use wcf\system\WCF;

/**
 * Abstract class for language entry cache builders.
 * 
 * Provides two variables:
 * * integer[][] 				  languageEntryIDsToObjectID	(objectID => (languageID => languageEntryID))
 * * \wcf\data\ILanguageEntry[][] languageEntries				(objectID => (languageID => languageEntry))
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
abstract class AbstractLanguageEntryCacheBuilder extends AbstractCacheBuilder {
	/**
	 * Name of the languageEntry class (FQCN).
	 * @var string
	 */
	protected static $languageEntryClass = '';
	
	/**
	 * @see \wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected final function rebuild(array $parameters) {
		$data = array(
			'languageEntryIDsToObjectID' => array(),
			'languageEntries' => array()
		);
		
		// build languageEntryIDsToObjectID
		$sql = 'SELECT languageEntryID, languageID, '.static::getObjectIDName().'
		        FROM   '.static::getDatabaseTableName();
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		
		while ($row = $statement->fetchArray()) {
			if (!isset($data['languageEntryIDsToObjectID'][$row[static::getObjectIDName()]])) {
				$data['languageEntryIDsToObjectID'][$row[static::getObjectIDName()]] = array();
			}
			$data['languageEntryIDsToObjectID'][$row[static::getObjectIDName()]][$row['languageID']] = $row['languageEntryID'];
		}
		
		$data['languageEntries'] = $this->buildLanguageEntries();
		
		return $data;
	}
	
	/**
	 * Builds language entries.
	 * 
	 * If you don't want to cache language entries, just return empty array.
	 * 
	 * @return	array	associative array (objectID => (languageID => languageEntry))
	 */
	abstract protected function buildLanguageEntries();
	
	/**
	 * Returns the object id name.
	 * 
	 * @return	string
	 */
	protected static function getObjectIDName() {
		return call_user_func(array(static::$languageEntryClass, 'getObjectIDName'));
	}
	
	/**
	 * Returns the database table name.
	 * 
	 * @return string
	 */
	protected static function getDatabaseTableName() {
		return call_user_func(array(static::$languageEntryClass, 'getDatabseTableName'));
	}
}
