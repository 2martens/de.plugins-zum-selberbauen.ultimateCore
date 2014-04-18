<?php
namespace wcf\data;

/**
 * Interface for all versionable object classes.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface IVersionableDatabaseObject {
	/**
	 * Returns the current version.
	 * 
	 * @api
	 * @return	\wcf\data\IVersion
	 */
	public function getVersion();
}
