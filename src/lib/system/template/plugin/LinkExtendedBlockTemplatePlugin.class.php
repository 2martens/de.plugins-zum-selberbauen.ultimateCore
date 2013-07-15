<?php
namespace wcf\system\template\plugin;
use wcf\system\request\UltimateLinkHandler;
use wcf\system\template\TemplateEngine;
use wcf\util\StringUtil;

/**
 * Extended template block plugin which generates a link using UltimateLinkHandler.
 * 
 * Allows for frontend links without a controller.
 * 
 * @author		Jim Martens
 * @copyright	2013 Jim Martens
 * @license		http://www.gnu.org/licenses/lgpl-3.0 GNU Lesser General Public License, version 3
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.template.plugin
 * @category	Community Framework
 */
class LinkExtendedBlockTemplatePlugin extends LinkBlockTemplatePlugin {
	/**
	 * @see	wcf\system\template\IBlockTemplatePlugin::execute()
	 */
	public function execute($tagArgs, $blockContent, TemplateEngine $tplObj) {
		if (!array_key_exists('controller', $tagArgs)) {
			$tagArgs['controller'] = null;
		}
	
		if (!isset($tagArgs['application']) || empty($tagArgs['application'])) {
			$tagArgs['application'] = 'wcf';
		}
	
		if (isset($tagArgs['encode']) && !$tagArgs['encode']) {
			unset($tagArgs['encode']);
			return UltimateLinkHandler::getInstance()->getLink($tagArgs['controller'], $tagArgs, $blockContent);
		}
	
		return StringUtil::encodeHTML(UltimateLinkHandler::getInstance()->getLink($tagArgs['controller'], $tagArgs, $blockContent));
	}
}
