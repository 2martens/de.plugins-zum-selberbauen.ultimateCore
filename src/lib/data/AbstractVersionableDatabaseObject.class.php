<?php
/**
 * Contains abstract class AbstractVersionableDatabaseObject.
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
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Abstract class for versionable database objects.
 * 
 * Do NOT mistake this class with the WCF class VersionableDatabaseObject in the same namespace.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractVersionableDatabaseObject extends DatabaseObject implements IVersionableDatabaseObject {
	/**
	 * The class name of the corresponding version class (FQCN).
	 * @var string
	 */
	protected static $versionClassName = '';
	
	/**
	 * The current version.
	 * @var	\wcf\data\IVersion
	 */
	private $currentVersion = null;
	
	/**
	 * @see \wcf\data\IVersionableDatabaseObject::getCurrentVersion()
	 */
	public function getCurrentVersion() {
		if ($this->currentVersion === null) {
			$versions = $this->getVersions();
			foreach ($versions as $version) {
				/* @var $version \wcf\data\IVersion */
				if (!$version->isReleased()) continue;
				
				$this->currentVersion = $version;
				break;
			}
		}
		
		return $this->currentVersion;
	}
	
	/**
	 * @see \wcf\data\IVersionableDatabaseObject::getVersions()
	 */
	public function getVersions() {
		$objectIDName = static::getDatabaseTableIndexName();
		
		$sql = 'SELECT *
		        FROM     '.static::getDatabaseVersionTableName().'
		        WHERE    '.$objectIDName.' = ?
		        ORDER BY versionNumber DESC';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($this->$objectIDName));
		
		$versions = array();
		while ($version = $statement->fetchObject($versionClassName)) {
			$versions[$version->getObjectID()] = $version;
		}
		
		return $versions;
	}
	
	/**
	 * Returns the value of a object data variable with the given name.
	 * 
	 * @param	string	$name
	 * @return	mixed|null
	 */
	public function __get($name) {
		$result = parent::__get($name);
		if ($result === null) {
			return $this->getCurrentVersion()->__get($name);
		}
	}
	
	/**
	 * Delegates inaccessible methods calls to the version object.
	 * 
	 * @param	string	$name
	 * @param	array	$arguments
	 * @return	mixed
	 */
	public function __call($name, $arguments) {
		if (!method_exists($this->getCurrentVersion(), $name)) {
			throw new SystemException("unknown method '".$name."'");
		}
		
		return call_user_func_array(array($this->getCurrentVersion(), $name), $arguments);
	}
	
	/**
	 * Returns suffix of the version database table.
	 * 
	 * @return	string
	 */
	public static function getDatabaseVersionTableName() {
		return static::getDatabaseTableName().'_version';
	}
	
	/**
	 * Returns name of the version id.
	 * 
	 * @return	string
	 */
	public static function getVersionIDName() {
		return 'versionID';
	}
	
	/**
	 * Returns the version class name.
	 * 
	 * @return string
	 */
	public static function getVersionClassName() {
		return static::$versionClassName;
	}
}