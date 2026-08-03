/*header script*/
jQuery( document ).on( "click", "#activity-panel-tab-help", function(e) {
	e.preventDefault(); // stops link from making page jump to the top
	e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too
	jQuery(this).addClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).addClass( 'is-open is-switching' );
});

jQuery( document ).on( "click", ".woocommerce-layout__activity-panel-wrapper", function(e) {	
	e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too	
});

jQuery( document ).on( "click", "body", function() {	
	jQuery('#activity-panel-tab-help').removeClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).removeClass( 'is-open is-switching' );
});
/*header script end*/ 


/* cbr_snackbar — delegate to library's ZUI.snackbar (glassmorphic top-right toast).
   Kept as a jQuery shim so existing callers (jQuery(document).cbr_snackbar('Saved.'))
   transparently get the new library design without per-call rewrites. */
(function( $ ){
	$.fn.cbr_snackbar = function(msg) {
		if ( window.ZUI && typeof window.ZUI.snackbar === 'function' ) {
			window.ZUI.snackbar( msg, { type: 'success' } );
		}
		return this;
	};
	$.fn.cbr_snackbar_warning = function(msg) {
		if ( window.ZUI && typeof window.ZUI.snackbar === 'function' ) {
			window.ZUI.snackbar( msg, { type: 'error', html: true } );
		}
		return this;
	};
})( jQuery );

jQuery(document).ready(function(){
	"use strict";
	jQuery(".tipTip").tipTip();
	/* Skip select2 on this dropdown — ZUI's .zui-select-wrap already styles it cleanly. */
	jQuery('#cbrw_border_color, #cbrw_background_color, #cbrw_font_color, #cbrwl_box_background_color, #cbrwl_background_color').wpColorPicker();
	
	jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().hide();
	if( jQuery("#wpcbr_redirect_404_page").is(":checked") === true ){
		jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().show();
	}

	jQuery(".product_visibility:checked").trigger("click");

	/* Show sidebar only on Settings tab on page load */
	var activeTab = jQuery('.cbr_tab_input:checked').data('tab');
	if (!activeTab || activeTab === 'settings') {
		jQuery('.cbr-pro-sidebar').addClass('cbr-sidebar-visible');
	}
	
	jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().hide();
	if( jQuery("#wpcbr_make_non_purchasable1").is(":checked") === true ){
		jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().show();
	}
	
	jQuery("#wpcbr_message_position").parent().addClass('hidden-desc');
	if( jQuery("#wpcbr_message_position").val() === "custom_shortcode"){
		jQuery("#wpcbr_message_position").parent().removeClass('hidden-desc');
	}
	var restriction_type = jQuery(".cbr_restricted_type").find(":selected").val();
	if( restriction_type === 'all' ){
		jQuery(".restricted_countries").hide();
	}
	
});

(function( $ ){
	$.fn.isInViewport = function( element ) {
		var win = $(window);
		var viewport = {
			top : win.scrollTop()			
		};
		viewport.bottom = viewport.top + win.height();
		
		var bounds = this.offset();		
		bounds.bottom = bounds.top + this.outerHeight();

		if( bounds.top >= 0 && bounds.bottom <= window.innerHeight) {
			return true;
		} else {
			return false;	
		}		
	};
})( jQuery );

jQuery(document).on("click", ".catelog_visibility", function(){
	"use strict";
	
	var InsideClass = jQuery(".catelog_visibility").parent().find(".inside");
	var hasClass = InsideClass.hasClass("active");
	InsideClass.removeClass("active");
	if(hasClass === true ){
		jQuery(this).parent().find(".inside").addClass("active");
	}
});


jQuery(document).on("change", "#wpcbr_message_position", function(){
	"use strict";
	jQuery(this).parent().addClass('hidden-desc');
	if( jQuery(this).val() === "custom_shortcode"){
		jQuery(this).parent().removeClass('hidden-desc');
	}
});

jQuery(document).on("change", "#wpcbr_make_non_purchasable1", function(){
	"use strict";
	jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().hide();
	if( jQuery(this).is(":checked") === true){
		jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", "#wpcbr_redirect_404_page", function(){
	"use strict";
	jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().hide();
	if( jQuery(this).is(":checked") === true){
		jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", ".cbr_restricted_type", function(){
	"use strict";
	if( jQuery(this).find(":selected").val() === 'specific' || jQuery(this).find(":selected").val() === 'excluded'){
		jQuery(".restricted_countries").show();
	}
	if(jQuery(this).find(":selected").val() === 'all' ){
		jQuery(".restricted_countries").hide();
	}
});

/* Library-spec savebtn lifecycle: replace the .zui-savebtn__label text
 * with "Saving…" while the AJAX is in flight, then restore it on completion.
 * Matches the canonical pattern shown in ZUI-COMPONENTS-PREVIEW.html. */
function cbrSavebtnSaving($btn) {
	var $label = $btn.find(".zui-savebtn__label");
	if ( $label.length && ! $btn.data("cbrSavebtnOriginalLabel") ) {
		$btn.data("cbrSavebtnOriginalLabel", $label.html());
	}
	$label.html("Saving…");
	$btn.addClass("is-saving").removeClass("is-saved").prop("disabled", true);
}
function cbrSavebtnReset($btn) {
	var original = $btn.data("cbrSavebtnOriginalLabel");
	if ( original ) {
		$btn.find(".zui-savebtn__label").html(original);
	}
	$btn.removeClass("is-saving").prop("disabled", false);
}

/*ajex call for general tab form save*/
jQuery(document).on("click", "#cbr_setting_tab_form .cbr-save", function(){
	"use strict";
	var $btn = jQuery(this);
	cbrSavebtnSaving($btn);
	$btn.parent().find(".spinner").addClass("active");
	var form = jQuery('#cbr_setting_tab_form');
	jQuery.ajax({
		url: ajaxurl+"?action=cbr_setting_form_update",//csv_workflow_update,
		data: form.serialize(),
		type: 'POST',
		dataType:"json",
		success: function(response) {
			jQuery("#cbr_setting_tab_form .spinner").removeClass("active");
			cbrSavebtnReset($btn);
			if( response.success === "true" ){
				$btn.addClass("is-saved");
				jQuery(document).cbr_snackbar( "Settings Successfully Saved." );
				setTimeout(function(){ $btn.removeClass("is-saved"); }, 1500);
			}
		},
		error: function(response) {
			cbrSavebtnReset($btn);
			console.log(response);
		}
	});
	return false;
});

/* ZUI tab switching — no page reload (mirrors PRO behavior). */
function cbrActivateTab(tab) {
	"use strict";
	jQuery('.zui-tab-panel').prop('hidden', true);
	jQuery('.zui-tab-panel[data-tab="' + tab + '"]').prop('hidden', false);
	jQuery('.zui-tabs__item').removeClass('is-active').removeAttr('aria-current');
	jQuery('.zui-tabs__item[data-tab="' + tab + '"]').addClass('is-active').attr('aria-current', 'page');
}

jQuery(document).ready(function(){
	"use strict";
	var $active = jQuery('.zui-tabs__item.is-active').first();
	if ($active.length) {
		cbrActivateTab($active.data('tab'));
	}
});

jQuery(document).on("click", ".zui-tabs__item", function(e){
	"use strict";
	e.preventDefault();
	var tab = jQuery(this).data('tab');
	cbrActivateTab(tab);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=woocommerce-product-country-base-restrictions&tab="+tab;
	window.history.pushState({path:url},'',url);
	jQuery(window).trigger('resize');
});

// Settings tab sidebar section switching (ZUI).
jQuery(document).on("click", ".zui-sidebar__item[data-section]", function(e){
	"use strict";
	e.preventDefault();
	var $btn = jQuery(this);
	var section = $btn.data("section");
	if ( ! section ) {
		return;
	}
	jQuery(".zui-sidebar__item").removeClass("is-active").removeAttr("aria-current");
	$btn.addClass("is-active").attr("aria-current", "true");
	var $panel = $btn.closest(".zui-tab-panel");
	$panel.find("> .zui-layout > .zui-content > form > .zui-section, > .zui-layout .zui-content > form > .zui-section").each(function(){
		var $section = jQuery(this);
		if ( $section.data("section") === section ) {
			$section.addClass("is-active").prop("hidden", false);
		} else {
			$section.removeClass("is-active").prop("hidden", true);
		}
	});
	jQuery("#ast-settings-app").removeClass("zui-sidebar-open");
	if ( window.history && window.history.replaceState ) {
		var url = new URL( window.location.href );
		url.searchParams.set("section", section);
		window.history.replaceState({path: url.toString()}, "", url.toString());
	}
});

// Mobile sidebar drawer toggle (ZUI).
jQuery(document).on("click", "[data-ast-drawer-toggle]", function(e){
	"use strict";
	e.preventDefault();
	jQuery("#ast-settings-app").addClass("zui-sidebar-open");
});
jQuery(document).on("click", "[data-ast-drawer-close]", function(e){
	"use strict";
	e.preventDefault();
	jQuery("#ast-settings-app").removeClass("zui-sidebar-open");
});

// Radio card selection visual (ZUI radio cards in Catalog Visibility).
jQuery(document).on("change", ".zui-radio-card__input.product_visibility", function(){
	"use strict";
	jQuery(".zui-radio-card.product_visibility").removeClass("is-selected");
	jQuery(this).closest(".zui-radio-card").addClass("is-selected");
	// Toggle inside panels.
	jQuery(".main-panel .inside").removeClass("active");
	var val = jQuery(this).val();
	jQuery(".main-panel .inside[data-value='" + val + "']").addClass("active");
});

/* PRO feature lock - prevent any interaction with locked elements */
jQuery(document).on("click", ".cbr-pro-feature-row, .cbr-pro-feature-row input, .cbr-pro-feature-row select, .cbr-pro-feature-row textarea", function(e){
	"use strict";
	e.preventDefault();
	e.stopPropagation();
	return false;
});

jQuery(document).on("click", ".cbr-pro-locked-section .panel", function(e){
	"use strict";
	e.preventDefault();
	e.stopPropagation();
	return false;
});

/* License Ecosystem grid: filter pills + search (canonical .zui-lic-* classes). */
jQuery(function($){
	"use strict";
	var $grid = $("#cbr-lic-grid");
	if ( ! $grid.length ) { return; }
	var $search  = $("#cbr-lic-search");
	var $empty   = $grid.find(".zui-lic-eco__empty");
	var $filters = $(".zui-lic-eco__filters .zui-lic-eco__filter");
	var current  = "all";

	function applyFilter() {
		var q = $.trim( ( $search.val() || "" ).toLowerCase() );
		var shown = 0;
		$grid.find(".zui-lic-plugin").each(function(){
			var $card = $(this);
			var name = ( $card.attr("data-name") || "" ).toLowerCase();
			var active = $card.attr("data-active") === "1";
			var matchSearch = ! q || name.indexOf(q) !== -1;
			var matchFilter = ( current === "all" )
				|| ( current === "active" && active )
				|| ( current === "addons" && ! active );
			var show = matchSearch && matchFilter;
			$card.toggle( show );
			if ( show ) { shown++; }
		});
		if ( shown === 0 ) { $empty.removeAttr("hidden"); } else { $empty.attr("hidden", ""); }
	}

	$search.on("input", applyFilter);
	$filters.on("click", function(){
		current = $(this).attr("data-filter");
		$filters.removeClass("is-active");
		$(this).addClass("is-active");
		applyFilter();
	});
});