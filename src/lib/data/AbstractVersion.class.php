<?php
namespace wcf\data;

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
	 * The class name of the corresponding versionable object class (FQCN).
	 * @var string
	 */
	protected static $versionableObjectClass = '';
	
	/**
	 * @see \wcf\data\IVersion::getVersionNumber()
	 */
	public function getVersionNumber() {
		$versionIDName = call_user_func(static::$versionableObjectClass, 'getVersionIDName');
		return $this->$versionIDName;
	}

}
