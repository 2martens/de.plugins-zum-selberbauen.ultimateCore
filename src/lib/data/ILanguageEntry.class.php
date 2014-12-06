<?php
/**
 * Contains the interface ILanguageEntry.
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
 * along with the Ultimate Core. If not, see {@link http://www.gnu.org/licenses/}}.
 * 
 * @author		Jim Martens
 * @copyright	2011-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	
 * @category	Community Framework
 */
namespace wcf\data;

/**
 * Interface for all language entries.
 * 
 * A language entry consists of all multilingual fields of an object in one language.
 * 
 * @author		Jim Martens
 * @copyright	2012-2014 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	data
 * @category	Community Framework
 */
interface ILanguageEntry extends IStorableObject {
	/**
	 * Returns the language of the entry or null if is language-independent.
	 * 
	 * @return	\wcf\data\language\Language|null
	 */
	public function getLanguage();
}
