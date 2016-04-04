jQuery(document).ready(function($) {	  
jQuery('.avatar').live('mouseenter', function() {
 if ($(this).attr('id') == "non-pop") {
 return false;
 } else {
$("a #avatar-user ").each( function() {
        var clazz = $(this).attr('class');
		var reg = new RegExp("user-(.*)-avatar");
		var matchs = clazz.match(reg);
		var id = -1;     
		if (matchs != null)
			id = matchs[1];
		if (id == _member.id ){
		} else {

	 $(this).tooltipster({
        content: '<div class="loading-pop"></div>',	
        interactive: true,
		delay: 500,
        contentCloning: false,
        contentAsHTML: true,		
        animation: 'fade',		
	position: 'right',
    functionBefore: function(origin, continueTooltip) {
        if (origin.data('ajax') !== 'cached') {
		
           jQuery.ajax({
            url: ajaxurl,
				    type: 'post',
				    data: {
				        'action': 'bp_pop_member',
				        'id': id
				    },
                success: function(html) {                  
                    origin.tooltipster('content', html).html();
                }
            });    
		}       
     continueTooltip();
    }
   });  
  } 

});


$("a #avatar-group").each( function() {
        //var link = $(this).attr('href');
		//var reg = new RegExp("http://.*?/groups/(.*)/");
		//var matchs = link.match(reg); 
		//var id = null;
		var clazz = $(this).attr('class');
		var reg = new RegExp("group-(.*)-avatar");
		var matchs = clazz.match(reg);
		var id = -1;   
		if (matchs != null)
			id = matchs[1];
	 $(this).tooltipster({
        content: '<div class="loading-pop"></div>',	
        interactive: true,
		delay: 500,
        contentCloning: false,
        contentAsHTML: true,
        animation: 'fade',
	    position: 'right',
	    //offsetX: -70,
	    //offsetY: -15,
		
    functionBefore: function(origin, continueTooltip) {
        if (origin.data('ajax') !== 'cached') {		
           jQuery.ajax({
            url: ajaxurl,
				    type: 'post',
				    data: {
				        'action': 'bp_pop_group',
				        'group_slug': id
				    },
                success: function(html) {                  
                     origin.tooltipster('content', html).html('ajax', 'cached');
                }
            });    
		}       
     continueTooltip();
    }
   });  
      }); 
  }	  
	  });
	
});