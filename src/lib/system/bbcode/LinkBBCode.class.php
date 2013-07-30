<?php
/**
 * Contains the LinkBBCode class.
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
 * @subpackage	system.bbcode
 * @category	Community Framework
 */
namespace wcf\system\bbcode;
use wcf\system\request\UltimateLinkHandler;
use wcf\util\StringUtil;

/**
 * Parses the [link]-bbcode.
 * 
 * This bbcode can be used to create links with controllers. It cannot be used for ordinary external links. For external/fixed links please use the [url] bbcode.
 * 
 * Usage:
 * 
 * [link=parameter1]text[/link]
 * [link=parameter1,parameter2]text[/link]
 * [link=parameter1,postRouteURL]text[/link]
 * [link=parameter1,parameter2,postRouteURL]text[/link]
 * [link=parameter1,parameter2,parameter3]text[/link]
 * 
 * To count as parameter an attribute must be built like this: key=value.
 * To count as postRouteURL an attribute must contain a # and no = .
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.bbcode
 * @category	Community Framework
 */
class LinkBBCode extends AbstractBBCode {
	/**
	 * @see	wcf\system\bbcode\IBBCode::getParsedTag()
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
		$attributes = $this->splitArguments($openingTag['attributes']);
		if (!isset($attributes['controller'])) {
			$attributes['controller'] = null;
		}
		$url = '';
		if (isset($attributes['url'])) {
			$url = $attributes['url'];
			unset($attributes['url']);
			$url = StringUtil::decodeHTML($url);
		}
		
		$link = UltimateLinkHandler::getInstance()->getLink($attributes['controller'], $attributes, $url);
		return StringUtil::getAnchorTag($link, $content, false);
	}
	
	/**
	 * Splits the arguments.
	 * 
	 * @param	string[]	$attributes
	 * @return	string[]
	 */
	protected function splitArguments(array $attributes) {
		$arguments = array();
		foreach ($attributes as $attribute) {
			if (strpos($attribute, '=') !== false) {
				$tmpSplit = explode('=', $attribute);
				$arguments[$tmpSplit[0]] = $tmpSplit[1];
			} elseif (strpos($attribute, '#') !== false) {
				$arguments['url'] = $attribute;
			}
		}
		return $arguments;
	}
}
