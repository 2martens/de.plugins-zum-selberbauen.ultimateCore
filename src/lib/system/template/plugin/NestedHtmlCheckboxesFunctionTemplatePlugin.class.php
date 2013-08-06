<?php
namespace wcf\system\template\plugin;
use wcf\system\exception\SystemException;
use wcf\system\template\TemplateEngine;

/**
 * The 'nestedHtmlCheckboxes' template function generates a list of html checkboxes. 
 * 
 * Note: This function only works with options( and selected), not with output( and values).
 * In addition you can use nested checkboxes. To get nested checkboxes the given options array must contain an array per key.
 * This array has to contain the element itself at the first position and an array of sub elements at the second position. 
 * Note: Each given element must implement \wcf\data\ITitledDatabaseObject.
 * With nested you get only checkboxes in a look-like tree. They are still independent from each other.
 * 
 * Usage:
 * {nestedHtmlCheckboxes name="x" options=$array}
 * {nestedHtmlCheckboxes name="x" options=$array selected=$foo}
 * {nestedHtmlCheckboxes name="x" options=$array disabled=$foo}
 * {nestedHtmlCheckboxes name="x" options=$array selected=$foo disabled=$foo}
 * 
 * @author		Jim Martens
 * @copyright	2012 Jim Martens
 * @license		http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package		de.plugins-zum-selberbauen.ultimateCore
 * @subpackage	system.template.plugin
 * @category	Community Framework
 */
class NestedHtmlCheckboxesFunctionTemplatePlugin extends HtmlCheckboxesFunctionTemplatePlugin {
	/**
	 * Contains the tag args.
	 * @var	mixed[]
	 */
	protected $tagArgs = array();
	
	/**
	 * @see	\wcf\system\template\plugin\IFunctionTemplatePlugin::execute()
	 */
	public function execute($tagArgs, TemplateEngine $tplObj) {
		
		if (isset($tagArgs['output']) && is_array($tagArgs['output'])) {
			// call parent, since this case is not supported
			return parent::execute($tagArgs, $tplObj);
		}
		
		if (!isset($tagArgs['options']) || !is_array($tagArgs['options'])) {
			throw new SystemException("missing 'options' argument in htmlCheckboxes tag");
		}
		
		if (!isset($tagArgs['name'])) {
			throw new SystemException("missing 'name' argument in htmlCheckboxes tag");
		}
		
		if (isset($tagArgs['disableEncoding']) && $tagArgs['disableEncoding']) {
			$this->disableEncoding = true;
		}
		else {
			$this->disableEncoding = false;
		}
		
		// get selected values
		if (isset($tagArgs['selected'])) {
			if (!is_array($tagArgs['selected'])) $tagArgs['selected'] = array($tagArgs['selected']);
		}
		else {
			$tagArgs['selected'] = array();
		}
		if (!isset($tagArgs['separator'])) {
			$tagArgs['separator'] = '';
		}
		
		// get disabled values
		if (isset($tagArgs['disabled'])){
			if (!is_array($tagArgs['disabled'])) $tagArgs['disabled'] = array($tagArgs['disabled']);
		}
		else {
			$tagArgs['disabled'] = array();
		}
		
		$this->tagArgs = $tagArgs;
		
		// build html
		$html = '<ul>';
		foreach ($this->tagArgs['options'] as $elementID => $valueArray) {
			/* @value mixed[] */
			if (!empty($html)) $html .= $this->tagArgs['separator'];
			$html .= '<li>'.$this->buildHtml($elementID, $valueArray).'</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	
	/**
	 * Builds the html recursively.
	 * 
	 * @param	integer										$key
	 * @param	(\wcf\data\ITitledDatabaseObject|array)[]	$valueArray
	 * @return	string
	 */
	protected function buildHtml($key, array $valueArray) {
		$html = '';
		$html .= '<label><input data-name="'.$valueArray[0]->getTitle().'" type="checkbox" name="'.$this->encodeHTML($this->tagArgs['name']).'[]" value="'.$this->encodeHTML($key).'"'.(in_array($key, $this->tagArgs['selected']) ? ' checked="checked"' : '').(isset($this->tagArgs['disabled']) ? (is_array($this->tagArgs['disabled']) && !in_array($key, $this->tagArgs['disabled']) ? '' : ' disabled="disabled"') : '').' /> '.$this->encodeHTML($valueArray[0]).'</label>';
		if (count($valueArray[1])) {
			$html .= '<ul class="nestedList">';
			$tmpHtml = '';
			foreach ($valueArray[1] as $key => $__valueArray) {
				if (!empty($tmpHtml)) $tmpHtml .= $this->tagArgs['separator'];
				$tmpHtml .= '<li>'.$this->buildHtml($key, $__valueArray).'</li>';
			}
			$html .= $tmpHtml;
			$html .= '</ul>';
		}
		return $html;
	}
}
