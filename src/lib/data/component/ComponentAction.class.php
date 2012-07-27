<?php
namespace wcf\data\component;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes component-related actions.
 *
 * @author Jim Martens
 * @copyright 2011-2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimate
 * @subpackage data.component
 * @category Ultimate CMS
 */
class ComponentAction extends AbstractDatabaseObjectAction {
    /**
     * @see \wcf\data\AbstractDatabaseObjectAction::$className
     */
    public $className = '\wcf\data\component\ComponentEditor';
}
