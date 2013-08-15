<?php
/**
 * Contains the CacheResetListener class.
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
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
namespace wcf\system\event\listener;
use wcf\system\cache\builder\CommentCacheBuilder;
use wcf\system\cache\builder\CommentResponseCacheBuilder;
use wcf\system\cache\builder\UserCacheBuilder;
use wcf\system\event\IEventListener;

/**
 * Resets the user, comment and comment response cache.
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class CacheResetListener implements IEventListener {
	/**
	 * @link	http://doc.codingcorner.info/WoltLab-WCFSetup/classes/wcf.system.event.IEventListener.html#execute
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($className) {
			case 'wcf\acp\form\UserAddForm':
			case 'wcf\acp\form\UserEditForm':
				$this->resetUserCache();
				break;
			case 'wcf\acp\action\AJAXProxyAction':
				if ($eventObj->className == 'wcf\data\user\UserAction' && $eventName == 'delete') {
					$this->resetUserCache();
				}
				else if ($eventObj->className == 'wcf\data\comment\CommentAction' && $eventName == 'addComment') {
					$this->resetCommentCache();
				}
				else if ($eventObj->className == 'wcf\data\comment\CommentAction' && $eventName == 'addResponse') {
					$this->resetCommentResponseCache();
				}
				break;
		}
	}
		
	/**
	 * Resets the user cache.
	 */
	protected function resetUserCache() {
		UserCacheBuilder::getInstance()->reset();
	}
	
	/**
	 * Resets the comment cache.
	 */
	protected function resetCommentCache() {
		CommentCacheBuilder::getInstance()->reset();
	}
	
	/**
	 * Resets the comment response cache.
	 */
	protected function resetCommentResponseCache() {
		CommentResponseCacheBuilder::getInstance()->reset();
	}
}
