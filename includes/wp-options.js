$ = jQuery;

$(document).ready(function(){
	
	$("#submit").click(function(e){
		/*

		*/
		
		if ($("#loader").css("display") == "block")	
			return;
		
		$("#loader").css("display", "block");
		$("#message").hide();
		
		
		
		var mgadata = {
				color_default	: 	$("#wpoptions-color-scheme-default").attr("checked"),
				color_dark	:	$("#wpoptions-color-scheme-dark").attr("checked"),
				color_light		:	$("#wpoptions-color-scheme-light").attr("checked"),
				title_font		:	$("#title-font").val(),
				content_font 	:	$("#content-font").val(),
				custom_css		: 	$("#custom-css").val(),

				action			: 	'wp-options-update',
				whatever		: 	Math.random()
			};
			

			var bodyContent = jQuery.ajax({
				  url: ajaxurl,
				  global: false,
				  type: "POST",
				  data: mgadata,
				  dataType: "html",
				  async: false,	
				  success: function(result){					  
					  $("#loader").css("display", "none");
					  $("#message").show("slow");
				  }
			}).responseText;
					
	
		
	});

});