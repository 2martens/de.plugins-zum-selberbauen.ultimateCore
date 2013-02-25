/**
 * Modify the jQuery UI datepicker function for the today button.
 */
$(function() {
	$.datepicker._gotoToday = function(id) {

		var target = $(id);
		var inst = this._getInst(target[0]);
		if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
			inst.selectedDay = inst.currentDay;
			inst.drawMonth = inst.selectedMonth = inst.currentMonth;
			inst.drawYear = inst.selectedYear = inst.currentYear;
		}
		else {
			var date = new Date();
			inst.selectedDay = date.getDate();
			inst.drawMonth = inst.selectedMonth = date.getMonth();
			inst.drawYear = inst.selectedYear = date.getFullYear();
			this._setDateDatepicker(target, date);
			this._selectDate(id, this._getDateDatepicker(target));
		}
		this._notifyChange(inst);
		this._adjustDate(target);
	};
});

$.extend(WCF, {
	/**
	 * Shows a modal dialog with a built-in AJAX-loader.
	 * 
	 * @param	string		dialogID
	 * @param	boolean		resetDialog
	 * @return	jQuery
	 */
	showAJAXDialog: function(dialogID, resetDialog) {
		if (!dialogID) {
			dialogID = this.getRandomID();
		}
		
		if (!$.wcfIsset(dialogID)) {
			$('<div id="' + dialogID + '"></div>').appendTo(document.body);
		}
		
		var dialog = $('#' + $.wcfEscapeID(dialogID));
		
		if (resetDialog) {
			dialog.empty();
		}
		
		var dialogOptions = arguments[2] || {};
		dialogOptions.ajax = true;
		
		dialog.ultimateDialog(dialogOptions);
		
		return dialog;
	},
	
	/**
	 * Shows a modal dialog.
	 * 
	 * @param	string		dialogID
	 */
	showDialog: function(dialogID) {
		// we cannot work with a non-existant dialog, if you wish to
		// load content via AJAX, see showAJAXDialog() instead
		if (!$.wcfIsset(dialogID)) return;
		
		var $dialog = $('#' + $.wcfEscapeID(dialogID));
		
		var dialogOptions = arguments[1] || {};
		$dialog.ultimateDialog(dialogOptions);
	}
});

/**
 * WCF implementation for dialogs, based upon ideas by jQuery UI.
 */
$.widget('ui.ultimateDialog', {
	/**
	 * close button
	 * @var	jQuery
	 */
	_closeButton: null,
	
	/**
	 * dialog container
	 * @var	jQuery
	 */
	_container: null,
	
	/**
	 * dialog content
	 * @var	jQuery
	 */
	_content: null,
	
	/**
	 * modal overlay
	 * @var	jQuery
	 */
	_overlay: null,
	
	/**
	 * plain html for title
	 * @var	string
	 */
	_title: null,
	
	/**
	 * title bar
	 * @var	jQuery
	 */
	_titlebar: null,
	
	/**
	 * dialog visibility state
	 * @var	boolean
	 */
	_isOpen: false,
	
	/**
	 * option list
	 * @var	object
	 */
	options: {
		// dialog
		autoOpen: true,
		closable: true,
		closeButtonLabel: null,
		hideTitle: false,
		modal: true,
		title: '',
		zIndex: 400,
		
		// AJAX support
		ajax: false,
		data: { },
		showLoadingOverlay: true,
		success: null,
		type: 'POST',
		url: 'index.php/AJAXProxy/?t=' + SECURITY_TOKEN + SID_ARG_2ND,
		
		// event callbacks
		onClose: null,
		onShow: null
	},
	
	/**
	 * Initializes a new dialog.
	 */
	_init: function() {
		if (this.options.ajax) {
			new WCF.Action.Proxy({
				autoSend: true,
				data: this.options.data,
				showLoadingOverlay: this.options.showLoadingOverlay,
				success: $.proxy(this._success, this),
				type: this.options.type,
				url: this.options.url
			});
			this.loading();
			
			// force open if using AJAX
			this.options.autoOpen = true;
		}
		
		if (this.options.autoOpen) {
			this.open();
		}
		
		// act on resize
		$(window).resize($.proxy(this._resize, this));
	},
	
	/**
	 * Creates a new dialog instance.
	 */
	_create: function() {
		if (this.options.closeButtonLabel === null) {
			this.options.closeButtonLabel = WCF.Language.get('wcf.global.button.close');
		}
		
		WCF.DOMNodeInsertedHandler.enable();
		
		// create dialog container
		this._container = $('<div class="dialogContainer" />').hide().css({ zIndex: this.options.zIndex }).appendTo(document.body);
		this._titlebar = $('<header class="dialogTitlebar" />').hide().appendTo(this._container);
		this._title = $('<span class="dialogTitle" />').hide().appendTo(this._titlebar);
		this._closeButton = $('<a class="dialogCloseButton jsTooltip" title="' + this.options.closeButtonLabel + '"><span /></a>').click($.proxy(this.close, this)).hide().appendTo(this._titlebar);
		this._content = $('<div class="dialogContent" />').appendTo(this._container);
		
		this._setOption('title', this.options.title);
		this._setOption('closable', this.options.closable);
		
		// move target element into content
		var $content = this.element.detach();
		this._content.html($content);
		
		// create modal view
		if (this.options.modal) {
			this._overlay = $('#jsWcfDialogOverlay');
			if (!this._overlay.length) {
				this._overlay = $('<div id="jsWcfDialogOverlay" class="dialogOverlay" />').css({ height: '100%', zIndex: 399 }).appendTo(document.body);
			}
			
			if (this.options.closable) {
				this._overlay.click($.proxy(this.close, this));
				
				$(document).keyup($.proxy(function(event) {
					if (event.keyCode && event.keyCode === $.ui.keyCode.ESCAPE) {
						this.close();
						event.preventDefault();
					}
				}, this));
			}
		}
		
		WCF.DOMNodeInsertedHandler.disable();
	},
	
	/**
	 * Sets the given option to the given value.
	 * See the jQuery UI widget documentation for more.
	 */
	_setOption: function(key, value) {
		this.options[key] = value;
		
		if (key == 'hideTitle' || key == 'title') {
			if (!this.options.hideTitle && this.options.title != '') {
				this._title.html(this.options.title).show();
			} else {
				this._title.html('');
			}
		} else if (key == 'closable' || key == 'closeButtonLabel') {
			if (this.options.closable) {
				WCF.DOMNodeInsertedHandler.enable();
				
				this._closeButton.attr('title', this.options.closeButtonLabel).show().find('span').html(this.options.closeButtonLabel);
				
				WCF.DOMNodeInsertedHandler.disable();
			} else {
				this._closeButton.hide();
			}
		}
		
		if ((!this.options.hideTitle && this.options.title != '') || this.options.closable) {
			this._titlebar.show();
		} else {
			this._titlebar.hide();
		}
		
		return this;
	},
	
	/**
	 * Handles successful AJAX requests.
	 *
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		if (this._isOpen) {
			// initialize dialog content
			this._initDialog(data);
			
			if (this.options.success !== null && $.isFunction(this.options.success)) {
				this.options.success(data, textStatus, jqXHR);
			}
		}
	},
	
	/**
	 * Initializes dialog content if applicable.
	 * 
	 * @param	object		data
	 */
	_initDialog: function(data) {
		// remove spinner
		this._content.css('position', 'static').children('.icon-spinner').remove();
		
		// insert template
		if (this._getResponseValue(data, 'template')) {
			this._content.children().html(this._getResponseValue(data, 'template'));
			this.render(false, true);
		}
		
		// set title
		if (this._getResponseValue(data, 'title')) {
			this._setOption('title', this._getResponseValue(data, 'title'));
		}
	},
	
	/**
	 * Returns a response value, taking care of different object
	 * structure returned by AJAXProxy.
	 * 
	 * @param	object		data
	 * @param	string		key
	 */
	_getResponseValue: function(data, key) {
		if (data.returnValues && data.returnValues[key]) {
			return data.returnValues[key];
		}
		else if (data[key]) {
			return data[key];
		}
		
		return null;
	},
	
	/**
	 * Opens this dialog.
	 */
	open: function() {
		if (this.isOpen()) {
			return;
		}
		
		if (this._overlay !== null) {
			WCF.activeDialogs++;
			
			if (WCF.activeDialogs === 1) {
				this._overlay.show();
			}
		}
		
		this.render();
		this._isOpen = true;
	},
	
	/**
	 * Returns true if dialog is visible.
	 * 
	 * @return	boolean
	 */
	isOpen: function() {
		return this._isOpen;
	},
	
	/**
	 * Clears the dialog and applies a loading overlay
	 */
	loading: function() {
		$('<span class="icon icon48 icon-spinner" />').appendTo(this._content.css('position', 'relative'));
		this.render();
	},
	
	/**
	 * Closes this dialog.
	 */
	close: function() {
		if (!this.isOpen() || !this.options.closable) {
			return;
		}
		
		this._isOpen = false;
		this._container.wcfFadeOut();
		
		if (this._overlay !== null) {
			WCF.activeDialogs--;
			
			if (WCF.activeDialogs === 0) {
				this._overlay.hide();
			}
		}
		
		if (this.options.onClose !== null) {
			this.options.onClose();
		}
	},
	
	/**
	 * Renders dialog on resize if visible.
	 */
	_resize: function() {
		if (this.isOpen()) {
			this.render();
		}
	},
	
	/**
	 * Renders this dialog, should be called whenever content is updated.
	 * 
	 * @param	boolean		loaded
	 * @param	boolean		disableAnimation
	 */
	render: function(loaded, disableAnimation) {
		loaded = loaded || false;
		disableAnimation = disableAnimation || false;
		
		if (loaded) {
			// remove spinner
			this._content.children('.icon-spinner').remove();
		}
		
		// force dialog and it's contents to be visible
		this._container.show();
		this._content.children().show();
		
		// remove fixed content dimensions for calculation
		this._content.css({
			height: 'auto',
			width: 'auto'
		});
		
		// terminate concurrent rendering processes
		this._container.stop();
		this._content.stop();
		
		// set dialog to be fully opaque, prevents weird bugs in WebKit
		this._container.show().css('opacity', 1.0);
		
		// handle positioning of form submit controls
		var $heightDifference = 0;
		if (this._content.find('.formSubmit').length) {
			$heightDifference = this._content.find('.formSubmit').outerHeight();
			
			this._content.addClass('dialogForm').css({ marginBottom: $heightDifference + 'px' });
		}
		else {
			this._content.removeClass('dialogForm');
		}
		
		// calculate dimensions
		var $windowDimensions = $(window).getDimensions();
		var $containerDimensions = this._container.getDimensions('outer');
		var $contentDimensions = this._content.getDimensions();
		
		// calculate maximum content height
		var $heightDifference = $containerDimensions.height - $contentDimensions.height;
		var $maximumHeight = $windowDimensions.height - $heightDifference - 120;
		this._content.css({ maxHeight: $maximumHeight + 'px' });
		
		// re-caculate values if container height was previously limited
		if ($maximumHeight < $contentDimensions.height) {
			$containerDimensions = this._container.getDimensions('outer');
		}
		
		// calculate new dimensions
		$contentDimensions = this._getContentDimensions($maximumHeight);
		
		// move container
		var $leftOffset = Math.round(($windowDimensions.width - $containerDimensions.width) / 2);
		var $topOffset = Math.round(($windowDimensions.height - $containerDimensions.height) / 2);
		
		// place container at 20% height if possible
		var $desiredTopOffset = Math.round(($windowDimensions.height / 100) * 20);
		if ($desiredTopOffset < $topOffset) {
			$topOffset = $desiredTopOffset;
		}
		
		// apply offset
		this._container.css({
			left: $leftOffset + 'px',
			top: $topOffset + 'px'
		});
		
		// remove static dimensions
		this._content.css({
			height: 'auto',
			width: 'auto'
		});
		
		this._determineOverflow();
		
		if (!this.isOpen()) {
			// hide container again
			this._container.hide();
			
			// fade in container
			this._container.wcfFadeIn($.proxy(function() {
				if (this.options.onShow !== null) {
					this.options.onShow();
				}
			}, this));
		}
	},
	
	/**
	 * Determines content overflow based upon static dimensions.
	 */
	_determineOverflow: function() {
		var $max = $(window).getDimensions();
		var $maxHeight = this._content.css('maxHeight');
		this._content.css('maxHeight', 'none');
		var $dialog = this._container.getDimensions('outer');
		
		var $overflow = 'visible';
		if (($max.height * 0.8 < $dialog.height) || ($max.width * 0.8 < $dialog.width)) {
			$overflow = 'auto';
		}
		
		this._content.css('overflow', $overflow);
		this._content.css('maxHeight', $maxHeight);
	},
	
	/**
	 * Returns calculated content dimensions.
	 * 
	 * @param	integer		maximumHeight
	 * @return	object
	 */
	_getContentDimensions: function(maximumHeight) {
		var $contentDimensions = this._content.getDimensions();
		
		// set height to maximum height if exceeded
		if (maximumHeight && $contentDimensions.height > maximumHeight) {
			$contentDimensions.height = maximumHeight;
		}
		
		return $contentDimensions;
	}
});
