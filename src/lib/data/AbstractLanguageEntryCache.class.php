<?php
/**
 * Contains abstract class AbstractLanguageEntryCache.
 * 
 * LICENSE:
 * This file is part of the Ultimate Core.
 *
 * The Ultimate Core is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * The Ultimate Core is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with the Ultimate Core. If not, see {@link http://www.gnu.org/licenses/}}.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
namespace wcf\data;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Abstract class for language entry cache classes.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
abstract class AbstractLanguageEntryCache extends SingletonFactory {
	/**
	 * languageID for language-independent entries.
	 * @var integer
	 */
	const NEUTRAL_LANGUAGE = 0;
	
	/**
	 * Name of the languageEntry class (FQCN).
	 * @var string
	 */
	protected static $languageEntryClass = '';
	
	/**
	 * Name of the cache builder class (FQCN).
	 * @var string
	 */
	protected static $cacheBuilderClass = '';
	
	/**
	 * The cached data.
	 * @var array
	 */
	private $cachedData = array();
	
	/**
	 * Returns the value of the language entry with given object, key and current language.
	 * 
	 * @param	integer	$objectID
	 * @param	string	$key
	 * @return	mixed|null	null if there is no such value
	 */
	public function getValue($objectID, $key) {
		$value = null;
		$languageID = 0;
		// the actual language entries are cached with all or part of their values
		if (isset($this->cachedData['languageEntries'][$objectID])) {
			$entries = $this->cachedData['languageEntries'][$objectID];
			$entry = null;
			// there is an entry for the active language
			$activeLanguageID = WCF::getLanguage()->getObjectID();
			if (isset($entries[$activeLanguageID])) {
				$entry = $entries[$activeLanguageID];
				$languageID = $activeLanguageID;
			}
			// there is an entry for all languages/a fallback for not covered languages
			else if ($entries[static::NEUTRAL_LANGUAGE]) {
				$entry = $entries[static::NEUTRAL_LANGUAGE];
				$languageID = static::NEUTRAL_LANGUAGE;
			}
			
			/* @var $entry \wcf\data\ILanguageEntry|null */
			// there is an entry
			if ($entry !== null) {
				// try to retrieve a cached value
				$value = $entry->__get($key);
			}
		}
		// there are no cached entries or the value itself wasn't cached
		if ($value === null) {
			$entryIDs = $this->cachedData['languageEntryIDsToObjectID'][$objectID];
			$entryID = 0;
			// there is an entryID for the active language
			if (isset($entryIDs[WCF::getLanguage()->getObjectID()])) {
				$entryID = $entryIDs[WCF::getLanguage()->getObjectID()];
			}
			// there is an entryID for all languages/a fallback for not covered languages
			else if ($entryIDs[static::NEUTRAL_LANGUAGE]) {
				$entryID = $entryIDs[static::NEUTRAL_LANGUAGE];
			}
			
			// there is an entryID
			if ($entryID) {
				/* @var $entry \wcf\data\ILanguageEntry|null */
				// load the fitting entry from database
				$entry = new static::$languageEntryClass($entryID);
				// save the retrieved entry in the cached data
				if (!isset($this->cachedData['languageEntries'][$objectID])) {
					$this->cachedData['languageEntries'][$objectID] = array();
				}
				$this->cachedData['languageEntries'][$objectID][$languageID] = $entry;
				// try to retrieve value
				$value = $entry->__get($key);
			}
		}
		
		return $value;
	}
	
	/**
	 * Returns an array with all values for given object and key.
	 * 
	 * If a key has only a neutral value, all returned values will be the same. 
	 * 
	 * @param	integer	$objectID
	 * @param	string	$key
	 * @return   string[]
	 */
	public function getValues($objectID, $key) {
		$values = array();
		$languageIDs = array_keys(WCF::getLanguage()->getLanguages());
		if (isset($this->cachedData['languageEntries'][$objectID])) {
			$entries = $this->cachedData['languageEntries'][$objectID];
			
			foreach ($languageIDs as $languageID) {
				if (isset($entries[$languageID])) {
					// try to retrieve a cached value
					$values[$languageID] = $entries[$languageID]->__get($key);
				}
				else if (isset($entries[static::NEUTRAL_LANGUAGE])) {
					$values[$languageID] = $entries[static::NEUTRAL_LANGUAGE];
				}
				else {
					$values[$languageID] = null;
				}
			}
		}
		
		$entryIDs = $this->cachedData['languageEntryIDsToObjectID'][$objectID];
		foreach ($values as $languageID => &$value) {
			if ($value !== null) continue;
			$entryID = 0;
			// cached value didn't exist
			
			// there is an entryID for the active language
			if (isset($entryIDs[$languageID])) {
				$entryID = $entryIDs[$languageID];
			}
			// there is an entryID for all languages/a fallback for not covered languages
			else if ($entryIDs[static::NEUTRAL_LANGUAGE]) {
				$entryID = $entryIDs[static::NEUTRAL_LANGUAGE];
			}
			
			// there is an entryID
			if ($entryID) {
				/* @var $entry \wcf\data\ILanguageEntry|null */
				// load the fitting entry from database
				$entry = new static::$languageEntryClass($entryID);
				// save the retrieved entry in the cached data
				if (!isset($this->cachedData['languageEntries'][$objectID])) {
					$this->cachedData['languageEntries'][$objectID] = array();
				}
				$this->cachedData['languageEntries'][$objectID][$languageID] = $entry;
				// try to retrieve value
				$value = $entry->__get($key);
			}
		}
		
		return $values;
	}
	
	/**
	 * Returns true, if there is only a neutral value for the given object and key.
	 * 
	 * @param	integer	$objectID
	 * @param	string	$key
	 */
	public function isNeutralValue($objectID, $key) {
		$languageIDs = array_keys(WCF::getLanguage()->getLanguages());
		$entryIDs = $this->cachedData['languageEntryIDsToObjectID'][$objectID];
		
		$isNeutralValue = (isset($entryIDs[static::NEUTRAL_LANGUAGE]));
		
		if ($isNeutralValue) {
			unset($entryIDs[static::NEUTRAL_LANGUAGE]);
			$intersection = 0;
			foreach ($entryIDs as $languageID => $entryID) {
				if (in_array($languageID, $languageIDs)) {
					$intersection++;
				}
			}
			
			$isNeutralValue = ($intersection == 0);
		}
		
		return $isNeutralValue;
	}
	
	/**
	 * Reloads the cache.
	 */
	public function reloadCache() {
		static::getCacheBuilderObject()->reset();
		$this->init();
	}
	
	/**
	 * Initializes the cache.
	 */
	protected function init() {
		$this->cachedData = static::getCacheBuilderObject()->getData();
	}
	
	/**
	 * Returns the object id name.
	 * 
	 * @return	string
	 */
	protected static function getObjectIDName() {
		return call_user_func(array(static::$languageEntryClass, 'getObjectIDName'));
	}
	
	/**
	 * Returns an object of the cache builder.
	 * 
	 * @return	\wcf\system\cache\builder\ICacheBuilder
	 */
	private static function getCacheBuilderObject() {
		return call_user_func(array(static::$cacheBuilderClass, 'getInstance'));
	}
}
