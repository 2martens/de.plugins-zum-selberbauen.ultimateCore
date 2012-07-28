<?php
namespace wcf\data\ultimate\template;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit templates.
 *
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage data.ultimate.template
 * @category Ultimate CMS
 */
class TemplateEditor extends DatabaseObjectEditor {
    /**
     * @see \wcf\data\DatabaseObjectDecorator::$baseClass
     */
    protected static $baseClass = '\wcf\data\ultimate\template\Template';
    
    /**
     * @see \wcf\data\IEditableCachedObject::resetCache()
     */
    public function resetCache() {
        CacheHandler::getInstance()->clear(WCF_DIR.'cache/', 'cache.template-'.PACKAGE_ID.'.php');
    }
}
