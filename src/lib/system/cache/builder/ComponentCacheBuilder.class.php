<?php
namespace wcf\system\cache\builder;
use wcf\data\component\ComponentList;
use wcf\system\cache\builder\ICacheBuilder;

/**
 * Caches the components.
 *
 * @author Jim Martens
 * @copyright 2011-2012 Jim Martens
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
 * @package de.plugins-zum-selberbauen.componentPIP
 * @subpackage system.cache.builder
 * @category Ultimate CMS
 */
class ComponentCacheBuilder implements ICacheBuilder {
    
    /**
     * @see \wcf\system\cache\builder\ICacheBuilder::getData()
     */
    public function getData($cacheResource) {
        $data = array(
            'components' => array(),
            'componentIDs' => array()
        );
        
        $componentList = new ComponentList();
                
        $componentList->readObjectIDs();
        $componentList->readObjects();
        $componentIDs = $componentList->getObjectIDs();
        $components = $componentList->getObjects();
        if (!count($componentIDs) || !count($components)) return $data;
        
        $data['components'] = array_combine($componentIDs, $components);
        $data['componentIDs'] = $componentIDs;
        
        return $data;
    }
}
