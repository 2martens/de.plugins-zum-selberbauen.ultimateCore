<?php
/**
 * Contains the MoreBBCode class.
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
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.bbcode
 * @category	Community Framework
 */
namespace wcf\system\bbcode;
use wcf\util\StringUtil;

/**
 * Parses the [more] bbcode tag.
 * 
 * Do not put all your "hidden" text in here. All text below this BBCode will automatically be invisible on archive (category, index) pages.
 * 
 * Usage:
 * * [more][/more]			Creates an empty anchor.
 * * [more]some text[/more]	Creates an anchor with the given text.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.bbcode
 * @category	Community Framework
 */
class MoreBBCode extends AbstractBBCode {
	/**
	 * @see \wcf\system\bbcode\IBBCode::getParsedTag()
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
		return '<a id="more">'.StringUtil::trim($content).'</a>';
	}
}
