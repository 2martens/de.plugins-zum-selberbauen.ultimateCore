<?php
namespace wcf\system\package\plugin;
use wcf\system\event\EventHandler;
use wcf\system\exception\SystemException;

/**
 * Provides the block type data for the event listeners.
 * 
 * @author		Jim Martens
 * @copyright	2012 Jim Martens
 * @license		http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.package.plugin
 * @category	Ultimate CMS
 */
class BlockTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	/**
	 * xml tag name
	 * @var	string
	 */
	public $tagName = 'blocktype';
	
	/**
	 * object editor class name
	 * @var string
	 */
	public $className = '';
	
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
	 * Sets the object variables.
	 * 
	 * @param	string	$name
	 * @param	mixed	$value
	 */
	public function __set($name, $value) {
		$allowedValues = array(
			'className', 
			'findExistingItemSQLData', 
			'handleDeleteItems', 
			'hasUninstallReturn'
		);
		if (!in_array($name, $allowedValues)) return;
		$this->{$name} = $value;
	}
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::prepareImport()
	 */
	protected function prepareImport(array $data) {
		$databaseData = array(
			'blockTypeName' => $data['elements']['blocktypename'],
			'blockTypeClassName' => $data['elements']['blocktypeclassname'],
			'fixedHeight' => (isset($data['elements']['fixedHeight']) ? $data['elements']['fixedHeight'] : 1)
		);
		return $databaseData;
	}
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::validateImport()
	 */
	protected function validateImport(array $data) {
		parent::validateImport($data);
		
		if (empty($data['blockTypeName'])) {
			throw new SystemException('Invalid blockTypeName', 0, 'The blockTypeName cannot be empty.');
		}
		$namespaces = explode('\\', $data['blockTypeClassName']);
		if (empty($namespaces)) {
			throw new SystemException('Invalid blockTypeClassName', 0, 'The blockTypeClassName has to contain namespaces.');
		}
		elseif (count($namespaces) > 1) {
			$applicationPrefix = array_shift($namespaces);
			if ($applicationPrefix != 'ultimate') {
				throw new SystemException('Invalid blockTypeClassName', 0, 'The blockTypeClassName has to contain the application prefix \'ultimate\'.');
			}
		}
		else {
			throw new SystemException('Invalid blockTypeClassName', 0, 'The blockTypeClassName has to contain more than the application prefix.');
		}
		if ($data['fixedHeight'] != 1 && $data['fixedHeight'] != 0) {
			throw new SystemException('Invalid fixedHeight', 0, 'The fixedHeight has to be either 0 or 1.');
		}
	}
	
	/**
	 * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::import()
	 */
	protected function import(array $row, array $data) {
		if (empty($row)) {
			// create new item
			$this->prepareCreate($data);
				
			return call_user_func(array($this->className, 'create'), $data);
		}
		else {
			// update existing item
			$baseClass = call_user_func(array($this->className, 'getBaseClass'));
				
			$itemEditor = new $this->className(new $baseClass(null, $row));
			$itemEditor->update($data);
				
			return $itemEditor;
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
