<?php
namespace wcf\data;

/**
 * Interface for versionable database object action classes.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface IVersionableDatabaseObjectAction {
	/**
	 * Creates a new version.
	 */
	public function createVersion();
	
	/**
	 * Validates the create version call.
	 */
	public function validateCreateVersion();
	
	/**
	 * Deletes a version.
	 */
	public function deleteVersion();
	
	/**
	 * Validates the delete version call.
	 */
	public function validateDeleteVersion();
}
