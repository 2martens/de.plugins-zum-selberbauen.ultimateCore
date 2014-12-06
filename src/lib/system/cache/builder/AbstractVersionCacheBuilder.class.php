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
		return $this->getCachedData();
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

	/**
	 * Returns the data read from database.
	 * 
	 * @return array
	 */
	public function getCachedData() {
		$data = array(
			'currentVersionIDToObjectID' => array(),
			'versionIDsToObjectID' => array(),
			'versionsToObjectID' => array()
		);

		$objectListClass = static::$versionClass.'List';
		$objectList = new $objectListClass();
		$objectList->sqlOrderBy = 'versionNumber DESC';
		$objectList->readObjects();
		$versions = $objectList->getObjects();

		if (empty($versions)) return $data;

		$objectIDs = array();
		foreach ($versions as $versionID => $version) {
			$objectID = $version->__get(static::getObjectDatabaseTableIndexName());
			if (!isset($data['versionIDsToObjectID'][$objectID])) {
				$data['versionIDsToObjectID'][$objectID] = array();
			}
			$data['versionIDsToObjectID'][$objectID][] = $versionID;

			if (!in_array($objectID, $objectIDs)) {
				$objectIDs[] = $objectID;
			}

			if ($version->isReleased() && !isset($data['currentVersionIDToObjectID'][$objectID])) {
				$data['currentVersionIDToObjectID'][$objectID] = $version->__get('versionID');
			}

			if (!isset($data['versionsToObjectID'][$objectID])) {
				$data['versionsToObjectID'][$objectID] = array();
			}
			$data['versionsToObjectID'][$objectID][$version->__get('versionID')] = $version;
		}

		foreach ($objectIDs as $objectID) {
			$versionIDs = array_reverse($data['versionIDsToObjectID'][$objectID]);
			$oldestVersionID = $versionIDs[0];
			if (!isset($data['currentVersionIDToObjectID'][$objectID])) {
				$data['currentVersionIDToObjectID'][$objectID] = $oldestVersionID;
			}
		}
		
		return $data;
	}
}
