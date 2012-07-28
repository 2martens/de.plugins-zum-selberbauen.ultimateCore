<?php
namespace wcf\system\ultimate\blocktype;
use wcf\data\ultimate\block\Block;
use wcf\system\cache\CacheHandler;
use wcf\system\event\EventHandler;
use wcf\util\StringUtil;

/**
 * Abstract class for all blockTypes.
 *
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage system.ultimate.blockType
 * @category Ultimate CMS
 */
abstract class AbstractBlockType implements IBlockType {
    /**
     * Contains the template name.
     * @var string
     */
    public $templateName = '';
    
    /**
     * Contains the read rows.
     * @var array
     */
    public $queryResult = array();
    
    /**
     * Contains the request type.
     * The request type is one of the following values: page, content, category.
     * @var string
     */
    public $requestType = '';
    
    /**
     * Contains the block id.
     * @var integer
     */
    public $blockID = 0;
    
    /**
     * Contains a Block object.
     * @var \wcf\data\ultimate\block\Block
     */
    public $block = null;
    
    /**
     * Contains the cache name.
     * @var string
     */
    protected $cacheName = '';
    
    /**
     * Contains the CacheBuilder class name.
     * @var string
     */
    protected $cacheBuilderClassName = '';
    
    /**
     * Contains the cache index.
     * @var string
     */
    protected $cacheIndex = '';
    
    /**
     * Creates a new BlockType object.
     */
    public final function __construct() {}
    
    /**
     * @see \wcf\system\ultimate\blockType\IBlockType::run()
     */
    public function run($requestType, $blockID) {
        // fire event
        EventHandler::getInstance()->fireAction($this, 'run');
        $this->requestType = StringUtil::trim($requestType);
        $this->blockID = intval($blockID);
        $this->block = new Block($this->blockID);
        
        $this->readParameters();
        $this->readData();
        $this->assignVariables();
    }
    
    /**
     * @see \wcf\system\ultimate\blockType\IBlockType::readParameters()
     */
    public function readParameters() {
        // fire event
        EventHandler::getInstance()->fireAction($this, 'readParameters');
    }
    
    /**
     * @see \wcf\system\ultimate\blockType\IBlockType::readData()
     */
    public function readData() {
       // fire event
       EventHandler::getInstance()->fireAction($this, 'readData');
       $this->loadCache();
       
    }
    
    /**
     * @see \wcf\system\ultimate\blockType\IBlockType::assignVariables()
     */
    public function assignVariables() {
        // fire event
        EventHandler::getInstance()->fireAction($this, 'assignVariables');
    }
    
    /**
     * @see \wcf\system\ultimate\blockType\IBlockType::getHTML()
     */
    public function getHTML() {
        // fire event
        EventHandler::getInstance()->fireAction($this, 'getHTML');
        return ''; // you have to override this method
    }
    
    /**
     * Loads the cache.
     */
    protected function loadCache() {
        if (!empty($this->block->query)) {
            $cacheName = 'ultimate-block';
            $cacheBuilderClassName = '\wcf\system\cache\builder\UltimateBlockCacheBuilder';
            $file = WCF_DIR.'cache/cache'.$cacheName.'.php';
            CacheHandler::getInstance()->addResource($cacheName, $file, $cacheBuilderClassName);
            $result = CacheHandler::getInstance()->get($cacheName, 'cachedQueryToBlockID');
            $this->queryResult = $result[$this->blockID];
        } else {
            $file = WCF_DIR.'cache/cache.'.$this->cacheName.'.php';
            CacheHandler::getInstance()->addResource($this->cacheName, $file, $this->cacheBuilderClassName);
            $this->queryResult = CacheHandler::getInstance()->get($this->cacheName, $this->cacheIndex);
        }
    }
}
