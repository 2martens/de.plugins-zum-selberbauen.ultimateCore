<?php
/**
 * Contains the UltimateTagCloud cache builder.
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
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
namespace wcf\system\cache\builder;
use wcf\data\tag\Tag;
use wcf\data\tag\TagCloudTag;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Extends the TagCloudCacheBuilder with a more useful tags array.
 * 
 * Returns the tags:
 * * TagCloudTag[] (tagID => tag)
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class UltimateTagCloudCacheBuilder extends TagCloudCacheBuilder {
	/**
	 * Reads associated tags.
	 */
	protected function getTags() {
		$this->tags = array();
	
		if (!empty($this->objectTypeIDs)) {
			// get tag ids
			$tagIDs = array();
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add('object.objectTypeID IN (?)', array($this->objectTypeIDs));
			$conditionBuilder->add('object.languageID IN (?)', array($this->languageIDs));
			$sql = "SELECT		COUNT(*) AS counter, object.tagID
				FROM		wcf".WCF_N."_tag_to_object object
				".$conditionBuilder."
				GROUP BY	object.tagID
				ORDER BY	counter DESC";
			$statement = WCF::getDB()->prepareStatement($sql, 500);
			$statement->execute($conditionBuilder->getParameters());
			while ($row = $statement->fetchArray()) {
				$tagIDs[$row['tagID']] = $row['counter'];
			}
				
			// get tags
			if (!empty($tagIDs)) {
				$sql = "SELECT	*
					FROM	wcf".WCF_N."_tag
					WHERE	tagID IN (?".(count($tagIDs) > 1 ? str_repeat(',?', count($tagIDs) - 1) : '').")";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array_keys($tagIDs));
				while ($row = $statement->fetchArray()) {
					$row['counter'] = $tagIDs[$row['tagID']];
					$this->tags[$row['tagID']] = new TagCloudTag(new Tag(null, $row));
				}
				
				// sort by counter
				uasort($this->tags, array('self', 'compareTags'));
			}
		}
	}
}
