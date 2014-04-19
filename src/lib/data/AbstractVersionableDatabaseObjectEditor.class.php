<?php
namespace wcf\data;
use wcf\system\WCF;

/**
 * Abstract class for object editors for versionable database object classes.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractVersionableDatabaseObjectEditor extends DatabaseObjectEditor implements IVersionableDatabaseObjectEditor {
	/**
	 * Creates a new version with the given data.
	 * 
	 * @param	array	$parameters	associative array
	 * @return	\wcf\data\IVersion
	 */
	public function createVersion(array $parameters) {
		$keys = $values = '';
		$statementParameters = array();
		$parameters[static::getDatabaseTableIndexName()] = $this->getObjectID();
		foreach ($parameters as $key => $value) {
			if (!empty($keys)) {
				$keys .= ',';
				$values .= ',';
			}
			
			$keys .= $key;
			$values .= '?';
			$statementParameters[] = $value;
		}
		
		// retrieve next version id for current object
		$sql = 'SELECT MAX(versionNumber)
		        FROM   '.static::getDatabaseVersionTableName().'
		        WHERE  '.static::getDatabaseTableIndexName().' = ?';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($this->getObjectID()));
		$row = $statement->fetchArray();
		$newVersionNumber = $row['versionNumber'] + 1;
		
		// include the new version ID in the keys and values
		if (!empty($keys)) {
			$keys .= ',';
			$values .= ',';
		}
		$keys .= 'versionNumber';
		$values .= '?';
		$statementParameters[] = $newVersionNumber;
		
		
		// actually insert data into database
		$sql = 'INSERT INTO '.static::getDatabaseVersionTableName().'
		                    ('.$keys.')
		        VALUES      ('.$values.')';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($statementParameters);
		
		$versionClassName = static::getVersionClassName();
		
		return new $versionClassName($newVersionNumber);
	}
	
	/**
	 * @see \wcf\data\IVersionableDatabaseObjectEditor::updateVersion()
	 */
	public function updateVersion($versionID, array $parameters = array()) {
		$versionIDName = static::getVersionIDName();
		
		if (empty($parameters)) return;
		
		$updateSQL = '';
		$statementParameters = array();
		foreach ($parameters as $key => $value) {
			if (!empty($updateSQL)) $updateSQL .= ', ';
			$updateSQL .= $key . ' = ?';
			$statementParameters[] = $value;
		}
		$statementParameters[] = $versionID;
		
		$sql = 'UPDATE '.static::getDatabaseVersionTableName().'
		        SET    '.$updateSQL.'
		        WHERE  '.$versionIDName.' = ?';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($statementParameters);
	}
	
	/**
	 * @see \wcf\data\IVersionableDatabaseObjectEditor::deleteVersion()
	 */
	public function deleteVersion($versionID) {
		static::deleteAllVersions($this->getObjectID(), array($versionID));
	}
	
	/**
	 * Deletes all given versions of a given object.
	 * 
	 * @param	integer	$objectID
	 * @param	array	$versionIDs	numerical array
	 * @return	count of affected rows
	 */
	public static function deleteAllVersions($objectID, array $versionNumbers) {
		$sql = 'DELETE FROM '.static::getDatabaseVersionTableName().'
		        WHERE       '.static::getDatabaseTableIndexName().' = ?
		        AND         versionNumber = ?';
		$statement = WCF::getDB()->prepareStatement($sql);
		
		$affectedCount = 0;
		WCF::getDB()->beginTransaction();
		foreach ($versionNumbers as $versionNumber) {
			$statement->executeUnbuffered(array($objectID, $versionNumber));
			$affectedCount += $statement->getAffectedRows();
		}
		WCF::getDB()->commitTransaction();
		
		return $affectedCount;
	}
	
	/**
	 * Returns suffix of the version database table.
	 *
	 * @return	string
	 */
	public static function getDatabaseVersionTableName() {
		return call_user_func(array(static::$baseClass, 'getDatabaseVersionTableName'));
	}
	
	/**
	 * Returns the name of the version ID.
	 * 
	 * @return	string
	 */
	public static function getVersionIDName() {
		return call_user_func(array(static::$baseClass, 'getVersionIDName'));
	}
	
	/**
	 * Returns the version class name.
	 * 
	 * @return	string
	 */
	public static function getVersionClassName() {
		return call_user_func(array(static::$baseClass, 'getVersionClassName'));
	}
}
