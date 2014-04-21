<?php
/**
 * Contains the AnchorBBCode class.
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
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.bbcode
 * @category	Community Framework
 */
namespace wcf\system\bbcode;
use wcf\util\StringUtil;

/**
 * Parses the [anchor]-bbcode.
 * 
 * Usage:
 * 
 * [anchor=id]text[/anchor] -> Creates an <code>a</code> element with id as the id of it and text as content.
 * [anchor=id,type]text[/anchor] -> Creates a <code>type</code> element with id as the id of it and text as content. Allowed types are a, h1, h2, h3, h4, h5 and h6.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.bbcode
 * @category	Community Framework
 */
class AnchorBBCode extends AbstractBBCode {
	/**
	 * @see	wcf\system\bbcode\IBBCode::getParsedTag()
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
		$id = StringUtil::trim($openingTag['attributes'][0]);
		$type = 'a';
		if (isset($openingTag['attributes'][1])) {
			$type = StringUtil::trim($openingTag['attributes'][1]);
		}
		
		$id = StringUtil::encodeHTML($id);
		$type = StringUtil::encodeHTML($type);
		
		return '<'.$type.' id="'.$id.'">'.$content.'</'.$type.'>';
	}
}
