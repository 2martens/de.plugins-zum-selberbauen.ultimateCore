<?php
namespace wcf\system\package\plugin;
use wcf\system\event\EventHandler;
use wcf\system\exception\SystemException;

/**
 * Provides the block type data for the event listeners.
 * 
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage system.package.plugin
 * @category Ultimate CMS
 */
class BlockTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
    
    /**
     * xml tag name
     * @var string
     */
    public $tagName = 'blocktype';
    
    /**
     * Contains the sql data, which will be returned in the method findExistingItem.
     * @var (string|array)[]
     */
    public $findExistingItemSQLData = array();
    
    /**
     * Contains the data given to the findExistingItem method.
     * @var string[]
     */
    public $findExistingItemData = array();
    
    /**
     * Contains the items given to the handleDelete method.
     * @var array[]
     */
    public $handleDeleteItems = array();
    
    /**
     * True if uninstall is possible.
     * @var boolean
     */
    public $hasUninstallReturn = false;
    
    /**
     * @see \wcf\system\package\plugin\IPackageInstallationPlugin::hasUninstall()
     */
    public function hasUninstall() {
        // call hasUninstall event
        EventHandler::getInstance()->fireAction($this, 'hasUninstall');
        return $this->hasUninstallReturn;
    }
    
    /**
     * @see \wcf\system\package\plugin\IPackageInstallationPlugin::uninstall()
     */
    public function uninstall() {
        // call uninstall event
        EventHandler::getInstance()->fireAction($this, 'uninstall');
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::prepareImport()
     */
    protected function prepareImport(array $data) {
        $databaseData = array(
            'blockTypeName' => $data['elements']['blocktypename'],
            'blockTypeClassName' => $data['elements']['blocktypclassname']
        );
        return $databaseData;
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::validateImport()
     */
    protected function validateImport(array $data) {
        parent::validateImport($data);
        
        if (empty($data['blocktypename'])) {
            throw new SystemException('Invalid blockTypeName', 0, 'The blockTypeName cannot be empty.');
        }
        $namespaces = explode('\\', $data['blockTypeClassName']);
        if (!count($namespaces)) {
            throw new SystemException('Invalid blockTypeClassName', 0, 'The blockTypeClassName has to contain namespaces.');
        }
        elseif (count($namespaces) > 1) {
            $applicationPrefix = array_shift($namespaces);
            if ($applicationPrefix != 'ultimate') {
                throw new SystemException('Invalid blockTypeClassName', 0, 'The blockTypeClassName has to contain the application prefix \'ultimate\'.');
            }
        }
        else {
            throw new SystemException('Invalid blockTypeClassName', 0, 'The blockTypeClassName has to contain more than the application prefix');
        }
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::findExistingItem()
     */
    protected function findExistingItem(array $data) {
        $this->findExistingItemData = $data;
        
        // call findExistingItem event
        EventHandler::fireAction($this, 'findExistingItem');
        
        if (count($this->findExistingItemSQLData)) {
            return $this->findExistingItemSQLData;
        }
        return null;
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::handleDelete()
     */
    protected function handleDelete(array $items) {
        $this->handleDeleteItems = $items;
        // call handleDelete event
        EventHandler::fireAction($this, 'handleDelete');
    }
    
}
