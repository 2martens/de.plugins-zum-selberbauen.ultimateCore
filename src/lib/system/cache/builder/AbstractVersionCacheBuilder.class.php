<?php
/**
 * Contains abstract class AbstractVersionCacheBuilder.
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
use wcf\system\WCF;

/**
 * Abstract class for version cache builders.
 * 
 * Provides three variables:
 * * integer[] currentVersionIDToObjectID (objectID => currentVersionID)
 * * integer[][] versionIDsToObjectID (objectID => (versionID))
 * * \wcf\data\IVersion[][] versionsToObjectID (objectID => (versionID => version))
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
abstract class AbstractVersionCacheBuilder extends AbstractCacheBuilder {
	/**
	 * Name of the version class (FQCN).
	 * @var string
	 */
	protected static $versionClass = '';
	
	/**
	 * @see \wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected final function rebuild(array $parameters) {
		$data = array(
			'currentVersionIDToObjectID' => array(),
			'versionIDsToObjectID' => array(),
			'versionsToObjectID' => array()
		);
		
		$sql = 'SELECT versionID, '.static::getObjectDatabaseTableIndexName().'
		        FROM '.static::getDatabaseTableName();
		$statement = WCF::getDB()->prepareStatement($sql);
		
		$objectIDs = array();
		while ($row = $statement->fetchArray()) {
			if (!isset($data['versionIDsToObjectID'][$row[static::getObjectDatabaseTableIndexName()]])) {
				$data['versionIDsToObjectID'][$row[static::getObjectDatabaseTableIndexName()]] = array();
			}
			$data['versionIDsToObjectID'][$row[static::getObjectDatabaseTableIndexName()]][] = $row['versionID'];
			// save object ids
			if (!in_array($row[static::getObjectDatabaseTableIndexName()], $objectIDs)) {
				$objectIDs[] = $row[static::getObjectDatabaseTableIndexName()];
			}
		}
		
		$sql = 'SELECT *
		        FROM   '.static::getDatabaseTableName().'
		        WHERE  '.static::getObjectDatabaseTableIndexName().' = ?
		        ORDER BY versionNumber DESC';
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($objectIDs as $objectID) {
			$statement->execute(array($objectID));
			
			$versions = array();
			while ($version = $statement->fetchObject(static::$versionClass)) {
				$versions[] = $version;
				if ($version->isReleased() 
					&& !isset($data['currentVersionIDToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())])) {
					$data['currentVersionIDToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())] = $version->__get('versionID');
				}
			}
			
			// if no version is released yet, the oldest version is the current version
			if (!isset($data['currentVersionIDToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())])) {
				$data['currentVersionIDToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())] = $tmprslt = array_pop($versions);
				$versions[] = $tmprslt;
			}
			
			foreach ($versions as $version) {
				if (!isset($data['versionsToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())])) {
					$data['versionsToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())] = array();
				}
				$data['versionsToObjectID'][$version->__get(static::getObjectDatabaseTableIndexName())][$version->__get('versionID')] = $version;
			}
		}
		
		return $data;
	}
	
	/**
	 * Returns the object id name.
	 *
	 * @return string
	 */
	protected static function getObjectDatabaseTableIndexName() {
		return call_user_func(array(static::$versionClass, 'getObjectDatabaseTableIndexName'));
	}
	
	/**
	 * Returns the database table name.
	 *
	 * @return string
	 */
	protected static function getDatabaseTableName() {
		return call_user_func(array(static::$versionClass, 'getDatabaseTableName'));
	}
}
