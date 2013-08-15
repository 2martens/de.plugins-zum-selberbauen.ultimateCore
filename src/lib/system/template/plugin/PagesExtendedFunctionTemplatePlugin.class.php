<?php
/**
 * Contains the PagesExtendedFunctionTemplatePlugin class.
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
 * @copyright	2011-2012 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.template.plugin
 * @category	Community Framework
 */
namespace wcf\system\template\plugin;
use wcf\system\request\UltimateLinkHandler;
use wcf\system\template\TemplateEngine;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Extends the PageFunctionTemplatePlugin with the ability to use
 * all parameters you can use for a link element.
 * 
 * Usage:
 * 
 * * {pagesExtended link='xyz'} minimalistic example, only what you need (will assume WCF)
 * * {pagesExtended print=true assign='pagesLinks' application='ultimate' link='pageNo=%d' someOtherParameters}
 * 
 * As you can see, you don't need to give a controller. Of course you have to make sure that the given other parameters fit an existing route.
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.template.plugin
 * @category	Community Framework
 */
class PagesExtendedFunctionTemplatePlugin extends PagesFunctionTemplatePlugin {
	/**
	 * @see	wcf\system\template\IFunctionTemplatePlugin::execute()
	 */
	public function execute($tagArgs, TemplateEngine $tplObj) {
		// needed params: controller, link, page, pages
		if (!isset($tagArgs['link'])) throw new SystemException("missing 'link' argument in pages tag");
		if (!isset($tagArgs['controller'])) $tagArgs['controller'] = null;
		if (!isset($tagArgs['pages'])) {
			if (($tagArgs['pages'] = $tplObj->get('pages')) === null) {
				throw new SystemException("missing 'pages' argument in pages tag");
			}
		}
		
		if (!isset($tagArgs['application']) || empty($tagArgs['application'])) {
			$tagArgs['application'] = 'wcf';
		}
	
		$html = '';
	
		if ($tagArgs['pages'] > 1) {
			// create and encode route link
			$parameters = $tagArgs;
			if (isset($parameters['pages'])) unset($parameters['pages']);
			if (isset($parameters['page'])) unset($parameters['page']);
			if (isset($parameters['link'])) unset($parameters['link']);
			if (isset($parameters['print'])) unset($parameters['print']);
			if (isset($parameters['assign'])) unset($parameters['assign']);
			$link = StringUtil::encodeHTML(UltimateLinkHandler::getInstance()->getLink($tagArgs['controller'], $parameters, $tagArgs['link']));
			
			if (!isset($tagArgs['page'])) {
				if (($tagArgs['page'] = $tplObj->get('pageNo')) === null) {
					$tagArgs['page'] = 0;
				}
			}
			
			// open div and ul
			$html .= "<nav class=\"pageNavigation\" data-link=\"".$link."\" data-pages=\"".$tagArgs['pages']."\">\n<ul>\n";
			
			// previous page
			$html .= $this->makePreviousLink($link, $tagArgs['page']);
			
			// first page
			$html .= $this->makeLink($link, 1, $tagArgs['page'], $tagArgs['pages']);
			
			// calculate page links
			$maxLinks = parent::SHOW_LINKS - 4;
			$linksBeforePage = $tagArgs['page'] - 2;
			if ($linksBeforePage < 0) $linksBeforePage = 0;
			
			$linksAfterPage = $tagArgs['pages'] - ($tagArgs['page'] + 1);
			if ($linksAfterPage < 0) $linksAfterPage = 0;
			if ($tagArgs['page'] > 1 && $tagArgs['page'] < $tagArgs['pages']) {
				$maxLinks--;
			}
			
			$half = $maxLinks / 2;
			$left = $right = $tagArgs['page'];
			if ($left < 1) $left = 1;
			if ($right < 1) $right = 1;
			if ($right > $tagArgs['pages'] - 1) $right = $tagArgs['pages'] - 1;
			
			if ($linksBeforePage >= $half) {
				$left -= $half;
			}
			else {
				$left -= $linksBeforePage;
				$right += $half - $linksBeforePage;
			}
			
			if ($linksAfterPage >= $half) {
				$right += $half;
			}
			else {
				$right += $linksAfterPage;
				$left -= $half - $linksAfterPage;
			}
			
			$right = intval(ceil($right));
			$left = intval(ceil($left));
			if ($left < 1) $left = 1;
			if ($right > $tagArgs['pages']) $right = $tagArgs['pages'];
			
			// left ... links
			if ($left > 1) {
				if ($left - 1 < 2) {
					$html .= $this->makeLink($link, 2, $tagArgs['page'], $tagArgs['pages']);
				}
				else {
					$html .= '<li class="button jumpTo"><a title="'.WCF::getLanguage()->getDynamicVariable('wcf.global.page.jumpTo').'" class="jsTooltip">'.StringUtil::HELLIP.'</a></li>'."\n";
				}
			}
			
			// visible links
			for ($i = $left + 1; $i < $right; $i++) {
				$html .= $this->makeLink($link, $i, $tagArgs['page'], $tagArgs['pages']);
			}
			
			// right ... links
			if ($right < $tagArgs['pages']) {
				if ($tagArgs['pages'] - $right < 2) {
					$html .= $this->makeLink($link, $tagArgs['pages'] - 1, $tagArgs['page'], $tagArgs['pages']);
				}
				else {
					$html .= '<li class="button jumpTo"><a title="'.WCF::getLanguage()->getDynamicVariable('wcf.global.page.jumpTo').'" class="jsTooltip">'.StringUtil::HELLIP.'</a></li>'."\n";
				}
			}
			
			// last page
			$html .= $this->makeLink($link, $tagArgs['pages'], $tagArgs['page'], $tagArgs['pages']);
			
			// next page
			$html .= $this->makeNextLink($link, $tagArgs['page'], $tagArgs['pages']);
			
			// close div and ul
			$html .= "</ul></nav>\n";
		}
		
		// assign html output to template var
		if (isset($tagArgs['assign'])) {
			$tplObj->assign($tagArgs['assign'], $html);
			if (!isset($tagArgs['print']) || !$tagArgs['print']) return '';
		}
		
		return $html;
	}
}
