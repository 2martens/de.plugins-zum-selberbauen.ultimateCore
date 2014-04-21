<?php
/**
 * Contains abstract class AbstractVersion.
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
 * Abstract class for versions.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractVersion extends DatabaseObject implements IVersion {
	/**
	 * The class name of the corresponding versionable object class (FQCN).
	 * @var string
	 */
	protected static $versionableObjectClass = '';
	
	/**
	 * Creates a new instance of the Version class.
	 *
	 * @param	integer						$versionID
	 * @param	array						$row
	 * @param	\wcf\data\AbstractVersion	$object
	 */
	public function __construct($versionID, array $row = null, AbstractVersion $object = null) {
		if ($id !== null) {
			$sql = 'SELECT *
			        FROM   '.static::getDatabaseTableName().'
			        WHERE  '.static::getDatabaseTableIndexName().' = ?';
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($objectID, $versionID));
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
	 * @see \wcf\data\IVersion::getVersionNumber()
	 */
	public function getVersionNumber() {
		return $this->versionNumber;
	}
	
	/**
	 * Returns the database table name.
	 *
	 * @return string
	 */
	public static function getDatabaseTableName() {
		return call_user_func(array(static::$versionableObjectClass, 'getDatabaseVersionTableName'));
	}
	
	/**
	 * @see	\wcf\data\IStorableObject::getDatabaseTableAlias()
	 */
	public static function getDatabaseTableAlias() {
		return call_user_func(array(static::$versionableObjectClass, 'getDatabaseTableAlias')).'_version';
	}
	
	/**
	 * Returns the database table index name of the versionable object class.
	 *  
	 * @return string
	 */
	public static function getObjectDatabaseTableIndexName() {
		return call_user_func(array(static::$versionableObjectClass, 'getDatabaseTableIndexName'));
	}
}
