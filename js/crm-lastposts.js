// Cromorama.com
// 12-02-2015 // Last Update: 16-02-2015
// CRM LastPosts Widget


jQuery(document).ready(function($) {
	jQuery('#widgets-right .crm-color-picker, .inactive-sidebar .crm-color-picker').wpColorPicker();
	runChecks();
}).ajaxSuccess(function(e, xhr, settings) {
	if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=crm_lastposts') != -1) {  
		jQuery('#widgets-right .crm-color-picker, .inactive-sidebar .crm-color-picker').wpColorPicker();
		runChecks();
	}
});

function runChecks(){
	
	if(jQuery('.NumPostTitle').hasClass('active_1')){
		jQuery('.NumPostTitleContainer').show();
	}else{
		jQuery('.NumPostTitleContainer').hide();
	}

	if(jQuery('.NumPostDate').hasClass('active_1')){
		jQuery('.NumPostDateContainer').show();
	}else{
		jQuery('.NumPostDateContainer').hide();
	}

	if(jQuery('.NumColorCheck').hasClass('active_1')){
		jQuery('.NumColorCheckContainer').show();
	}else{
		jQuery('.NumColorCheckContainer').hide();
	}

	if(jQuery('.boxCheck').hasClass('active_1')){
		jQuery('.boxCheckContainer').show();
	}else{
		jQuery('.boxCheckContainer').hide();
	}
	
	jQuery('.NumPostTitle').click(function(){
		jQuery('.NumPostTitleContainer').toggle("slow");
	});
	
	jQuery('.NumPostDate').click(function(){
		jQuery('.NumPostDateContainer').toggle("slow");
	});
	
	jQuery('.NumColorCheck').click(function(){
		jQuery('.NumColorCheckContainer').toggle("slow");
	});

	jQuery('.boxCheck').click(function(){
		jQuery('.boxCheckContainer').toggle("slow");
	});
	
};