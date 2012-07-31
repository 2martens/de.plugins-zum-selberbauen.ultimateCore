<?php
namespace wcf\util;

/**
 * Provides date and time related functions.
 * 
 * @author Jim Martens
 * @copyright 2012 Jim Martens
 * @license http://www.plugins-zum-selberbauen.de/index.php?page=CMSLicense CMS License
 * @package de.plugins-zum-selberbauen.ultimateCore
 * @subpackage util
 * @category Community Framework
 */
class DateTimeUtil {
    
    /**
     * Returns the PHP date() compatible date format synonym for the given jQuery UI DatePicker date format.
     * 
     * @param  string $datePickerDateFormat
     * @return string
     */
    public static function getPHPDateFormatFromDateTimePicker($datePickerDateFormat) {
        $phpDateFormat = '';
        switch ($datePickerDateFormat) {
            case 'mm/dd/yy':
                $phpDateFormat = 'm/d/Y';
                break;
            case 'dd/mm/yy':
                $phpDateFormat = 'd/m/Y';
                break;
            case 'dd-mm-yy':
                $phpDateFormat = 'd-m-Y';
                break;
            case 'yy/mm/dd':
                $phpDateFormat = 'Y/m/d';
                break;
            case 'yy.mm.dd':
                $phpDateFormat = 'Y.m.d';
                break;
            case 'd M, y':
                $phpDateFormat = 'j M, y';
                break;
            case 'd MM, y':
                $phpDateFormat = 'j F, y';
                break;
            case 'DD, d MM, yy':
                $phpDateFormat = 'l, j F, Y';
                break;
        }
        
        return $phpDateFormat;
    }
}
