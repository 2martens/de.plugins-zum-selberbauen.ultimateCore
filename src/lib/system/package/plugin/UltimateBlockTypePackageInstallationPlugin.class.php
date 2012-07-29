<?php
namespace wcf\system\package\plugin;
use wcf\system\exception\SystemException;
use wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin;
use wcf\system\WCF;
use wcf\util\ClassUtil;

/**
 * This PIP installes, updates or deletes blockTypes.
 *
 * @author Jim Martens
 * @copyright 2011-2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage system.package.plugin
 * @category Ultimate CMS
 */
class UltimateBlockTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$className
     */
    public $className = '\wcf\data\ultimate\blocktype\BlockTypeEditor';
    
    /**
     * @see	\wcf\system\package\plugin\AbstractPackageInstallationPlugin::$tableName
     */
    public $tableName = 'ultimate_blocktype';
    
    /**
     * @see	\wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$tagName
     */
    public $tagName = 'blockType';
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::handleDelete()
     */
    protected function handleDelete(array $items) {
        $sql = 'DELETE FROM wcf'.WCF_N.'_'.$this->tableName.'
        		WHERE  packageID     = ?
                AND    blockTypeName = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        
        WCF::getDB()->beginTransaction();
        foreach ($items as $blockType) {
            $statement->executeUnbuffered(array(
                $this->installation->getPackageID(),
                $blockType['attributes']['name']
            ));
        }
        WCF::getDB()->commitTransaction();
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::prepareImport()
     */
    protected function prepareImport(array $data) {
        $mapped = array(
            'packageID' => $this->installation->getPackageID(),
            'blockTypeName' => $data['attributes']['name'],
            'bockTypeClassName' => $data['elements']['className']
        );
        return $mapped;
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::findExistingItem()
     * @return null
     */
    protected function findExistingItem(array $data) {
        // You can't update a blockType with an xml file.
        // To update the blockType, simply update its class file.
        return null;
    }
    
    /**
     * @see \wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::validateImport()
     * @throws \wcf\system\exception\SystemException
     */
    protected function validateImport(array $data) {
        if (!isset($data['blockTypeName']) || !isset($data['blockTypeClassName'])) {
            throw new SystemException('The array given doesn\'t fit the form needed by the object editor class.');
        }
        
        if (empty($data['blockTypeName'])) {
            throw new SystemException('The given name can\'t be empty.');
        }
        
        if (empty($data['blockTypeClassName'])) {
            throw new SystemException('The given class name can\'t be empty.');
        }
        
        if (!strpos($data['blockTypeClassName'], '\\')) {
            throw new SystemException('The class name has to contain at least one namespace.');
        }
        
        if (!ClassUtil::isInstanceOf($data['blockTypeClassName'], '\wcf\system\ultimate\blocktype\IBlockType')) {
            throw new SystemException('The class belonging to the class name has to implement IBlockType.');
        }
    }
}
