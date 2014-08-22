<?php
/**
 * Contains abstract class AbstractVersionCache.
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
use wcf\system\SingletonFactory;

/**
 * Abstract class for version cache classes.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractVersionCache extends SingletonFactory {
	/**
	 * Name of the cache builder class (FQCN).
	 * @var string
	 */
	protected static $cacheBuilderClass = '';
	
	/**
	 * Contains the cached data.
	 * @var array
	 */
	protected $cachedData = array();
	
	/**
	 * Returns the id of the current version for the given object.
	 *  
	 * @param	integer	$objectID
	 * @return	integer
	 */
	public function getCurrentVersionID($objectID) {
		return $this->cachedData['currentVersionIDToObjectID'][$objectID];
	}
	
	/**
	 * Returns the current version for the given object.
	 * 
	 * @param	integer	$objectID
	 * @return	\wcf\data\IVersion
	 */
	public function getCurrentVersion($objectID) {
		$versionID = $this->getCurrentVersionID($objectID);
		$versions = $this->getVersions($objectID);
		return $versions[$versionID];
	}
	
	/**
	 * Returns the versions of the given object (newest first).
	 * 
	 * @param	integer	$objectID
	 * @return	\wcf\data\IVersion[]
	 */
	public function getVersions($objectID) {
		return $this->cachedData['versionsToObjectID'][$objectID];
	}
	
	/**
	 * Returns a list of version IDs belonging to the given object.
	 * 
	 * @param	integer	$objectID
	 * @return	integer[]
	 */
	public function getVersionIDs($objectID) {
		return $this->cachedData['versionIDsToObjectID'][$objectID];
	}
	
	/**
	 * Reloads the cache.
	 */
	public function reloadCache() {
		static::getCacheBuilderObject()->reset();
		$this->init();
	}
	
	/**
	 * Initializes the version cache.
	 */
	protected function init() {
		$this->cachedData = static::getCacheBuilderObject()->getData();
	}
	
	/**
	 * Returns an object of the cache builder class.
	 * 
	 * @return \wcf\system\cache\builder\AbstractVersionCacheBuilder
	 */
	protected static function getCacheBuilderObject() {
		return call_user_func(array(static::$cacheBuilderClass, 'getInstance'));
	}
}
