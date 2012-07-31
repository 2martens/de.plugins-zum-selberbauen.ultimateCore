/* German initialisation for the jQuery UI time picker addon for the date picker plugin.
 *
 * Written by Jim Martens.
 */
 jQuery(function($){
    $.timepicker.regional['de'] = {
        currentText: 'Jetzt',
        closeText: 'Auswählen',
        ampm: false,
        timeFormat: 'hh:mm tt',
        timeSuffix: '',
        timeOnlyTitle: 'Uhrzeit auswählen',
        timeText: 'Zeit',
        hourText: 'Stunde',
        minuteText: 'Minute',
        secondText: 'Sekunde',
        millisecText: 'Millisekunde',
        timezoneText: 'Zeitzone'
    };
    $.timepicker.setDefaults($.timepicker.regional['de']);
});

jQuery(function($){
    /* English initialisation for the jQuery UI time picker addon for the date picker plugin.
     *
     * Written by Jim Martens.
      */
    $.timepicker.regional['en-GB'] = {
        currentText: 'Now',
        closeText: 'Done',
        ampm: true,
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        timeFormat: 'hh:mm tt',
        timeSuffix: '',
        timeOnlyTitle: 'Choose Time',
        timeText: 'Time',
        hourText: 'Hour',
        minuteText: 'Minute',
        secondText: 'Second',
        millisecText: 'Millisecond',
        timezoneText: 'Time Zone'
    };
    $.timepicker.setDefaults($.timepicker.regional['en-GB']);
});