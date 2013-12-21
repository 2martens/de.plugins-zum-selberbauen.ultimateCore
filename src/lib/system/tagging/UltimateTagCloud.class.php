<?php
/**
 * Contains the UltimateTagCloud.
 * 
 * LICENSE:
 * This file is part of the Ultimate CMS.
 *
 * The Ultimate CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * The Ultimate CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with the Ultimate CMS. If not, see {@link http://www.gnu.org/licenses/}}.
 * 
 * @author		Jim Martens
 * @copyright	2011-2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.tagging
 * @category	Community Framework
 */
namespace wcf\system\tagging;
use wcf\system\cache\builder\UltimateTagCloudCacheBuilder;

/**
 * Extends the TagCloud to use the UltimateTagCloudCacheBuilder.
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.tagging
 * @category	Community Framework
 */
class UltimateTagCloud extends TagCloud {
	/**
	 * Loads the tag cloud cache.
	 */
	protected function loadCache() {
		$this->tags = UltimateTagCloudCacheBuilder::getInstance()->getData($this->languageIDs);
	}
	
	/**
	 * Gets a list of weighted tags.
	 *
	 * @param	integer				$slice
	 * @return	array<\wcf\data\tag\TagCloudTag>	the tags to get
	 */
	public function getTags($slice = 50) {
		// slice list
		$tags = array_slice($this->tags, 0, min($slice, count($this->tags)), true);
		
		// get min / max counter
		foreach ($tags as $tag) {
			if ($tag->counter > $this->maxCounter) $this->maxCounter = $tag->counter;
			if ($tag->counter < $this->minCounter) $this->minCounter = $tag->counter;
		}
		
		// assign sizes
		foreach ($tags as $tag) {
			$tag->setSize($this->calculateSize($tag->counter));
		}
		
		// sort alphabetically
		$kSortArray = array();
		foreach ($tags as $tag) {
			$kSortArray[$tag->__get('name')] = $tag;
		}
		
		ksort($kSortArray);
		$returnArray = array();
		
		foreach ($kSortArray as $tag) {
			$returnArray[$tag->__get('tagID')] = $tag;
		}
		
		// return tags
		return $returnArray;
	}
	
	/**
	 * Returns the size of a tag with given number of uses for a weighted list.
	 *
	 * @param	integer		$counter
	 * @return	double
	 */
	private function calculateSize($counter) {
		if ($this->maxCounter == $this->minCounter) {
			return 100;
		}
		else {
			return (self::MAX_FONT_SIZE - self::MIN_FONT_SIZE) / ($this->maxCounter - $this->minCounter) * $counter + self::MIN_FONT_SIZE - ((self::MAX_FONT_SIZE - self::MIN_FONT_SIZE) / ($this->maxCounter - $this->minCounter)) * $this->minCounter;
		}
	}
}
