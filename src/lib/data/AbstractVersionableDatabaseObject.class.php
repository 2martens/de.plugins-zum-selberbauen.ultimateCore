<?php
namespace wcf\data;
use wcf\system\WCF;

/**
 * Abstract class for versionable database objects.
 * 
 * Do NOT mistake this class with the WCF class VersionableDatabaseObject in the same namespace.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
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
	 * @see \wcf\data\IVersionableDatabaseObject::getVersion()
	 */
	public function getVersion() {
		$objectIDName = static::getDatabaseTableIndexName();
		$versionIDName = static::getVersionIDName();
		
		$sql = 'SELECT   '.$versionIDName.'
		        FROM     '.static::getDatabaseVersionTableName().'
		        WHERE    '.$objectIDName.' = ?
		        ORDER BY '.$versionIDName.' DESC';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($this->$objectIDName));
		
		$row = $statement->fetchArray();
		$mostRecentVersionID = $row[$versionIDName];
		return new static::$versionClassName($this->$objectIDName, $mostRecentVersionID);
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