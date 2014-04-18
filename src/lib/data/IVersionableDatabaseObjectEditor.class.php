<?php
namespace wcf\data;

/**
 * Interface for all database object editors for versionable objects.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface IVersionableDatabaseObjectEditor {
	/**
	 * Creates a new version with the given data.
	 * 
	 * @api
	 * @param	array	$parameters	associative array
	 * @return	\wcf\data\IVersion
	 */
	public function createVersion($parameters);
	
	/**
	 * Updates the given version with the given data.
	 * 
	 * @api
	 * @param	integer	$versionID
	 * @param	array	$parameters	associative array
	 */
	public function updateVersion($versionID, $parameters);
	
	/**
	 * Deletes the given version.
	 * 
	 * @api
	 * @param	integer	$versionID
	 */
	public function deleteVersion($versionID);
	
	/**
	 * Deletes all given versions of a given object.
	 * 
	 * @param	integer	$objectID
	 * @param	array	$versionIDs	numerical array
	 * @return	count of affected rows
	 */
	public static function deleteAllVersions($objectID, array $versionIDs);
}
