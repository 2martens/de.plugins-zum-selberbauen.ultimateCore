<?php
namespace wcf\data;

/**
 * Interface for all version classes.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface IVersion extends IStorableObject {
	/**
	 * Returns the number of this version.
	 * 
	 * The number of the version is unique in combination with the object ID.
	 * Don't mistake with the versionID.
	 * 
	 * @api
	 * @return	integer
	 */
	public function getVersionNumber();
	
	/**
	 * Checks if this version is already released.
	 * 
	 * @api
	 * @return	boolean
	 */
	public function isReleased();
	
	/**
	 * Checks if the version is visible for the current user.
	 * 
	 * @api
	 * @return	boolean
	 */
	public function isVisible();
	
	/**
	 * Returns the saved data.
	 * 
	 * @api
	 * @return	mixed[] associative array
	 */
	public function getData();
}
