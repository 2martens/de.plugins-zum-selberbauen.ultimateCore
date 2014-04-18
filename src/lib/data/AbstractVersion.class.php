<?php
namespace wcf\data;
use wcf\system\WCF;

/**
 * Abstract class for versions.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractVersion extends DatabaseObject implements IVersion {
	/**
	 * indicates if database table index is an identity column
	 * @var	boolean
	 */
	protected static $databaseTableIndexIsIdentity = false;
	
	/**
	 * The class name of the corresponding versionable object class (FQCN).
	 * @var string
	 */
	protected static $versionableObjectClass = '';
	
	/**
	 * Creates a new instance of the Version class.
	 *
	 * @param	integer						$objectID
	 * @param	integer						$versionID
	 * @param	array						$row
	 * @param	\wcf\data\AbstractVersion	$object
	 */
	public function __construct($objectID, $versionID, array $row = null, AbstractVersion $object = null) {
		if ($id !== null) {
			$sql = 'SELECT *
			        FROM   '.static::getDatabaseTableName().'
			        WHERE  '.static::getObjectDatabaseTableIndexName().' = ?
			        AND    '.static::getDatabaseTableIndexName().' = ?';
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
		$versionIDName = call_user_func(static::$versionableObjectClass, 'getVersionIDName');
		return $this->$versionIDName;
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
