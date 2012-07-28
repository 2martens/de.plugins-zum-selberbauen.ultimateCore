<?php
namespace wcf\data\ultimate\block;
use wcf\data\ultimate\blocktype\BlockType;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Represents a block entry.
 *
 * @author Jim Martens
 * @copyright 2011-2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage data.ultimate.block
 * @category Ultimate CMS
 */
class Block extends DatabaseObject {
    /**
     * @see \wcf\data\DatabaseObject::$databaseTableName
     */
    protected static $databaseTableName = 'ultimate_block';
    
    /**
     * @see \wcf\data\DatabaseObject::$databaseTableIndexIsIdentity
     */
    protected static $databaseTableIndexIsIdentity = true;
    
    /**
     * @see \wcf\data\DatabaseObject::$databaseTableIndexName
     */
    protected static $databaseTableIndexName = 'blockID';
        
    /**
     * @see \wcf\data\DatabaseObject::handleData()
     */
    protected function handleData($data) {
        $data['parameters'] = unserialize($data['parameters']);
        $data['blockType'] = new BlockType($data['blockTypeID']);
        parent::handleData($data);
    }
}
