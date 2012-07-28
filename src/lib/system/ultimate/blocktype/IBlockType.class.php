<?php
namespace wcf\system\ultimate\blocktype;

/**
 * Interface for all BlockType classes.
 *
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage system.ultimate.blockType
 * @category Ultimate CMS
 */
interface IBlockType {
    /**
     * Initializes the blockType.
     *
     * @param string    $requestType
     * @param integer   $blockID
     */
    public function run($requestType, $blockID);
    
    /**
     * Reads parameters.
     */
    public function readParameters();
    
    /**
     * Reads the necessary data.
     */
    public function readData();
    
    /**
     * Assigns template variables.
     */
    public function assignVariables();
    
    /**
     * Returns the HTML for this blockType.
     *
     * @return string
     */
    public function getHTML();
    
}
