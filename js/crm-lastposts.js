// Cromorama.com
// 12-02-2015
// CRM LastPosts Widget

(function( $ ) {
    $(function() {
         
        $( '.crm-color-picker' ).wpColorPicker();
         
    });
})( jQuery );


jQuery(document).ajaxSuccess(function(e, xhr, settings) {
  
	(function( $ ) {
    	$(function() {
         
        	$( '.crm-color-picker' ).wpColorPicker();
        });
	})( jQuery );
  
});