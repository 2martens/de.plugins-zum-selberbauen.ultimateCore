<?php
namespace wcf\system\ultimate\blocktype;
use wcf\data\ultimate\template\Template;
use wcf\system\cache\CacheHandler;
use wcf\system\SingletonFactory;

/**
 * Handles the blockTypes.
 *
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage system.ultimate.blockType
 * @category Ultimate CMS
 */
class BlockTypeHandler extends SingletonFactory {
    /**
     * Contains the request type.
     * @var string
     */
    protected $requestType = '';
    
    /**
     * The template id of the current request.
     * @var integer
     */
    protected $templateID = 0;
    
    /**
     * Contains the read objects.
     * @var array
     */
    protected $objects = array();
    
    /**
     * Returns the request type.
     *
     * @return string
     */
    public function getRequestType() {
        return $this->requestType;
    }
    
    /**
     * Handles the request.
     *
     * @param string  $requestType
     * @param integer $templateID
     *
     * @return array<String>
     */
    public function handleRequest($requestType, $templateID) {
        $this->requestType = StringUtil::trim($requestType);
        $this->templateID = intval($templateID);
        
        $this->loadCache();
        $template = $this->objects[$this->templateID];
        $resultArray = array();
        foreach ($template->blocks as $blockID => $block) {
            $blockType = $block->blockType;
            $className = $blockType->blockTypeClassName;
            $blockTypeController = new $className();
            $blockTypeController->run($this->requestType, $blockID);
            $resultArray[$blockID] = $blockTypeController->getHTML();
        }
        return $resultArray;
    }
    
    protected function loadCache() {
        $cacheName = 'ultimate-template';
        $cacheBuilderClassName = '\wcf\system\cache\builder\UltimateTemplateCacheBuilder';
        $file = WCF_DIR.'cache/cache.'.$cacheName.'.php';
        CacheHandler::getInstance()->addResource($cacheName, $file, $cacheBuilderClassName);
        $this->objects = CacheHandler::getInstance()->get($cacheName, 'templates');
    }
}