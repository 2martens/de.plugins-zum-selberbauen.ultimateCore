<?php
/**
 * Contains the TagObjectCacheBuilder class.
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
 * along with the Ultimate Core. If not, see {@link http://www.gnu.org/licenses/}.
 * 
 * @author		Jim Martens
 * @copyright	2011-2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
namespace wcf\system\cache\builder;
use wcf\system\WCF;

/**
 * Caches the tag to object relation.
 * 
 * Provides one variable:
 * * integer[][] tagIDsToObjectTypeID (objectTypeID => array(=> tagID))
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class TagObjectCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @see \wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected function rebuild(array $parameters) {
		$data = array(
			'tagsIDToObjectTypeID' => array()
		);
		
		$sql = 'SELECT tagID, objectTypeID
		        FROM   wcf'.WCF_N.'_tag_to_object';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		
		while ($row = $statement->fetchArray()) {
			if (!isset($data['tagIDsToObjectTypeID'][intval($row['objectTypeID'])])) {
				$data['tagIDsToObjectTypeID'][intval($row['objectTypeID'])] = array();
			}
			$data['tagIDsToObjectTypeID'][intval($row['objectTypeID'])][] = intval($row['tagID']);
		}
		
		return $data;
	}
}
