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
interface IVersion {
	/**
	 * Returns the number of this version.
	 * 
	 * The number of the version is it's ID. It is unique in combination with the object ID.
	 * 
	 * @api
	 * @return	integer
	 */
	public function getVersionNumber();
	
	/**
	 * Returns if this version is already released.
	 * 
	 * @api
	 * @return	boolean
	 */
	public function isReleased();
	
	/**
	 * Returns the saved data.
	 * 
	 * @api
	 * @return	mixed[] associative array
	 */
	public function getData();
}
