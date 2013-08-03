<?php
/**
 * Contains the TruncateMore modifier template plugin.
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
 * @subpackage	system.template.plugin
 * @category	Community Framework
 */
namespace wcf\system\template\plugin;
use wcf\system\exception\SystemException;
use wcf\system\template\TemplateEngine;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * The 'truncateMore' modifier truncates a string.
 * 
 * Usage (definite):
 * * {$text|truncateMore:$length}
 * * {$text|truncateMore:$length:$breakWords}
 * * {'a small test'|truncateMore:4:false}
 * 
 * If $length is 0 then the text will only be truncated if a 'More'-BBCode has been used.
 * If $length is greater than 0 then the text will be truncated to the 'More'-BBCode (if used) or to the given $length.
 * 
 * @author		Jim Martens
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.template.plugin
 * @category	Community Framework
 */
class TruncateMoreModifierTemplatePlugin implements IModifierTemplatePlugin {
	/**
	 * @see \wcf\system\template\plugin\IModifierTemplatePlugin::execute()
	 */
	public function execute($tagArgs, TemplateEngine $tplObj) {
		$length = 0;
		$breakWords = false;
		
		// get values
		$string = $tagArgs[0];
		if (isset($tagArgs[1])) $length = intval($tagArgs[1]);
		else throw new SystemException('Parameter length is missing');
		if (isset($tagArgs[2])) $breakWords = (boolean) $tagArgs[2];
		// fix for StringUtil
		if ($length == 0) $length = StringUtil::length($string);
		
		// check if More-BBCode has been used
		if (($position = StringUtil::indexOf($string, '<a id="more"')) !== false) {
			// if that is so and the position is within the allowed length,
			// cut the text after the more tag
			if ($position < $length) {
				$length = $position;
			}
		}
		
		// calculate real needed length
		$subString = StringUtil::substring($string, 0, $length);
		$length = StringUtil::length(StringUtil::stripHtml($subString));
		
		return StringUtil::truncateHTML($string, $length, StringUtil::HELLIP, $breakWords);
	}
}
