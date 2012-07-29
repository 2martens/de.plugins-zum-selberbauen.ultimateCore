<?php
namespace wcf\data\ultimate\template;
use wcf\data\ultimate\block\Block;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Represents a template entry.
 *
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage data.ultimate.template
 * @category Ultimate CMS
 */
class Template extends DatabaseObject {
    /**
     * @see \wcf\data\DatabaseObject::$databaseTableName
     */
    protected static $databaseTableName = 'ultimate_template';
    
    /**
     * @see \wcf\data\DatabaseObject::$databaseTableIndexIsIdentity
     */
    protected static $databaseTableIndexIsIdentity = true;
    
    /**
     * @see \wcf\data\DatabaseObject::$databaseTableIndexName
     */
    protected static $databaseTableIndexName = 'templateID';
    
    /**
     * Returns all blocks associated with this template.
     *
     * @return multitype:\wcf\data\ultimate\block\Block
     */
    public function getBlocks() {
        $sql = 'SELECT blockID
                FROM   wcf'.WCF_N.'_ultimate_block_to_template
                WHERE  templateID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array($this->templateID));
        $blocks = array();
        while ($row = $statement->fetchArray()) {
            $blocks[$row['blockID']] = new Block($row['blockID']);
        }
        return $blocks;
    }
    
    /**
     * Returns the title of this component.
     *
     * @return string
     */
    public function __toString() {
        return WCF::getLanguage()->get($this->templateName);
    }
    
    /**
     * @see \wcf\data\DatabaseObject::handleData()
     */
    protected function handleData($data) {
        $data['templateID'] = intval($data['templateID']);
        $data['templateBlocks'] = unserialize($data['templateBlocks']);
        parent::handleData($data);
        $this->data['blocks'] = $this->getBlocks();
    }
}
