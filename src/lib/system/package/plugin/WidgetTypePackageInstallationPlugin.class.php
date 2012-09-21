<?php
/**
 * Contains the widgetType package installation plugin class.
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
 * along with the Ultimate Core.  If not, see {@link http://www.gnu.org/licenses/}.
 * 
 * @author		Jim Martens
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.package.plugin
 * @category	Ultimate CMS
 */
namespace wcf\system\package\plugin;
use wcf\system\event\EventHandler;
use wcf\system\exception\SystemException;

/**
 * Provides the widget type data for the event listeners.
 * 
 * @author		Jim Martens
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.package.plugin
 * @category	Ultimate CMS
 */
class WidgetTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	/**
	 * xml tag name
	 * @var	string
	 */
	public $tagName = 'widgettype';
	
	/**
	 * Contains the sql data, which will be returned in the method findExistingItem.
	 * @var	(string|array)[]
	 */
	public $findExistingItemSQLData = array();
	
	/**
	 * Contains the data given to the findExistingItem method.
	 * @var	string[]
	*/
	public $findExistingItemData = array();
	
	/**
	 * Contains the items given to the handleDelete method.
	 * @var	array[]
	*/
	public $handleDeleteItems = array();
	
	/**
	 * True if uninstall is possible.
	 * @var	boolean
	*/
	public $hasUninstallReturn = false;
	
	/**
	 * @see	\wcf\system\package\plugin\IPackageInstallationPlugin::hasUninstall()
	 */
	public function hasUninstall() {
		// call hasUninstall event
		EventHandler::getInstance()->fireAction($this, 'hasUninstall');
		return $this->hasUninstallReturn;
	}
	
	/**
	 * @see	\wcf\system\package\plugin\IPackageInstallationPlugin::uninstall()
	 */
	public function uninstall() {
		// call uninstall event
		EventHandler::getInstance()->fireAction($this, 'uninstall');
	}
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::prepareImport()
	 */
	protected function prepareImport(array $data) {
		$databaseData = array(
			'widgetTypeName' => $data['elements']['widgettypename'],
			'widgetTypeClassName' => $data['elements']['widgettypeclassname']
		);
		return $databaseData;
	}
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::validateImport()
	 */
	protected function validateImport(array $data) {
		parent::validateImport($data);
	
		if (empty($data['widgettypename'])) {
			throw new SystemException('Invalid widgetTypeName', 0, 'The widgetTypeName cannot be empty.');
		}
		$namespaces = explode('\\', $data['widgetTypeClassName']);
		if (empty($namespaces)) {
			throw new SystemException('Invalid widgetTypeClassName', 0, 'The widgetTypeClassName has to contain namespaces.');
		}
		elseif (count($namespaces) > 1) {
			$applicationPrefix = array_shift($namespaces);
			if ($applicationPrefix != 'ultimate') {
				throw new SystemException('Invalid widgetTypeClassName', 0, 'The widgetTypeClassName has to contain the application prefix \'ultimate\'.');
			}
		}
		else {
			throw new SystemException('Invalid widgetTypeClassName', 0, 'The widgetTypeClassName has to contain more than the application prefix.');
		}
		if ($data['fixedHeight'] != 1 && $data['fixedHeight'] != 0) {
			throw new SystemException('Invalid fixedHeight', 0, 'The fixedHeight has to be either 0 or 1.');
		}
	}
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::findExistingItem()
	 */
	protected function findExistingItem(array $data) {
		$this->findExistingItemData = $data;
	
		// call findExistingItem event
		EventHandler::getInstance()->fireAction($this, 'findExistingItem');
	
		if (!empty($this->findExistingItemSQLData)) {
			return $this->findExistingItemSQLData;
		}
		return null;
	}
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::handleDelete()
	 */
	protected function handleDelete(array $items) {
		$this->handleDeleteItems = $items;
		// call handleDelete event
		EventHandler::getInstance()->fireAction($this, 'handleDelete');
	}
}
