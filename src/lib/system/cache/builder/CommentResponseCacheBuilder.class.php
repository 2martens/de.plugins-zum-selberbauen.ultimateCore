<?php
/**
 * Contains the CommentResponseCacheBuilder class.
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
use wcf\data\comment\response\CommentResponseList;

/**
 * Caches the comment responses.
 *
 * Provides two variables:
 * * \wcf\data\comment\response\CommentResponse[] responses
 * * integer[] responseIDs
 *
 * @author		Jim Martens
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class CommentResponseCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @see \wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected function rebuild(array $parameters) {
		$data = array(
			'responses' => array(),
			'responseIDs' => array()
		);
		
		$responseList = new CommentResponseList();
		$responseList->readObjects();
		$responses = $responseList->getObjects();
		$responseIDs = $responseList->getObjectIDs();
		
		if (empty($reponses)) return $data;
		
		$data['responses'] = $responses;
		$data['responseIDs'] = $responseIDs;
		
		return $data;
	}
}
