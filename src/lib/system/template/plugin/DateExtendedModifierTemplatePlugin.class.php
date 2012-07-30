<?php
namespace wcf\system\template\plugin;
use wcf\system\template\TemplateEngine;
use wcf\system\WCF;
use wcf\util\DateUtil;

/**
 * The 'date' modifier formats a unix timestamp.
 * 
 * Default date format contains year, month and day. This extended version of
 * DateModifierTemplatePlugin returns a HTML 5 time element.
 * 
 * Usage:
 * {$timestamp|dateExtended}
 * {"132845333"|dateExtended:"Y-m-d"}
 * 
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage system.template.plugin
 * @category Community Framework
 */
class DateExtendedModifierTemplatePlugin extends DateModifierTemplatePlugin {
    /**
     * @see \wcf\system\template\plugin\IModifierTemplatePlugin::execute()
     */
    public function execute($tagArgs, TemplateEngine $tplObj) {
        $timestamp = intval($tagArgs[0]);
        $dateTimeObject = DateUtil::getDateTimeByTimestamp($timestamp);
        $date = DateUtil::format(
            $dateTimeObject, 
            WCF::getLanguage()->getDynamicVariable(
                'ultimate.date.dateFormat', 
                array(
                    'britishEnglish' => ULTIMATE_GENERAL_ENGLISHLANGUAGE
                )
            )
        );
        $time = DateUtil::format($dateTimeObject, DateUtil::TIME_FORMAT);
        $formattedDate = parent::execute($tagArgs, $tplObj);
        return '<time datetime="'.DateUtil::format($dateTimeObject, 'c').'" class="datetime" data-timestamp="'.$timestamp.'" data-date="'.$date.'" data-time="'.$time.'" data-offset="'.$dateTimeObject->getOffset().'">'.$formattedDate.'</time>';
    }
}
