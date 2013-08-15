<?php
/**
 * Contains the CommentCacheBuilder class.
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
 * along with the Ultimate Core.  If not, see {@link http://www.gnu.org/licenses/}.
 *
 * @author		Jim Martens
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
namespace wcf\system\cache\builder;
use wcf\data\comment\CommentList;

/**
 * Caches the comments.
 *
 * Provides two variables:
 * * \wcf\data\comment\Comment[] comments
 * * integer[] commentIDs
 *
 * @author		Jim Martens
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class CommentCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @see \wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected function rebuild(array $parameters) {
		$data = array(
			'comments' => array(),
			'commentIDs' => array()
		);
		
		$commentList = new CommentList();
		$commentList->readObjects();
		$comments = $commentList->getObjects();
		$commentIDs = $commentList->getObjectIDs();
		
		if (empty($comments)) return $comments;
		
		$data['comments'] = $comments;
		$data['commentIDs'] = $commentIDs;
		
		return $data;
	}
}
