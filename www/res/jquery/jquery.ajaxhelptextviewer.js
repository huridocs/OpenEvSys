
$.Helptextviewer = function(helpId){
	
	var ajaxData;
	removeHelpDiv();
	var helpDiv = createHelpDiv();
	var helpDivControlers = createHelpDivControllers();	
	var wysiwygForm = createWYSIWYGForm();
	
	$('#wysiwyg_container').remove();
	$("body").append(helpDiv);
	$("body").append(wysiwygForm);	
		
	var timerObj;
	
	function createHelpDiv(){
		helpDiv = $("<div id=\"ajax_help_text_viewer_div\"></div>");
		return helpDiv;
    }
	
	function createHelpDivControllers(){
		//helpDivControlers = $("<div id=\"ajax_help_text_viewer_div_controllers\"><a name=\"help_edit_link\"><img src=\"theme/default/images/icons/edit.png\" alt=\"Edit\"> Edit </a> | <a name=\"help_hide_link\"><img src=\"theme/default/images/icons/hide.png\" alt=\"Hide\"> Hide </a></div>");
		helpDivControlers = $("<div id=\"ajax_help_text_viewer_div_controllers\"><table><tr><td><a name=\"help_edit_link\"><img src=\"theme/default/images/icons/edit.png\" alt=\"Edit\"> Edit </a> </td><td> <a name=\"help_hide_link\"><img src=\"theme/default/images/icons/hide.png\" alt=\"Hide\"> Hide </a></td></tr></table></div>");
		return helpDivControlers; 
	}
	
	function createWYSIWYGForm(){
		wysiwygForm = $("<div id=\"wysiwyg_container\"><form><input type=\"hidden\" name=\"element_id\"><textarea name=\"wysiwyg\" id=\"wysiwyg\" rows=\"5\" cols=\"47\"></textarea><input type=\"button\" name=\"cancel_help\" value=\"Close\"><input type=\"button\" name=\"save_help\" value=\"Save\"></form></div>");
		return wysiwygForm;
	}
	
	function removeHelpDiv(){
		if(helpDiv){
			$(helpDiv).fadeOut("slow");
			$(helpDiv).remove();
		}
		else{
			$("#ajax_help_text_viewer_div").fadeOut("slow");
			$("#ajax_help_text_viewer_div").remove();
		}
	}
	
	function removeWYSIWYGForm(){
		$('#wysiwyg_container').fadeOut("slow").remove();
	}	
	
	function initEvets(){
		helpDiv.mouseover(function(){			
			/*$(this).fadeTo("fast",1.0);*/
		}).mouseout(function(){			
			/*$(this).fadeTo("fast",0.8);*/
		}).dblclick(function(){
			//console.log($(this));
		});
		
		$('#wysiwyg_container input[type="button"]').each(function(){						
			$(this).click(function(){						
				switch($(this).attr("value"))
				{
					case "Close":
						removeWYSIWYGForm();
						break;
					case "Save":
						$.get("index.php?mod=help&act=set_help&element_id="+$("#wysiwyg_container form input[name='element_id']").val()+"&wysiwyg="+$("#wysiwyg_container form textarea[name='wysiwyg']").val(), function(responseData	){
							removeWYSIWYGForm();
						});											
						break;
				}				
			});
		});
		
		
		//Hide the help panel on body click
		/*$("*").not("#ajax_help_text_viewer_div *").not("#ajax_help_text_viewer_div").click(function(){			
			removeHelpDiv();
		});
		$("#ajax_help_text_viewer_div, #ajax_help_text_viewer_div *").click(function(e) {
			   e.stopPropagation();
		});*/

		
		$("a", helpDiv).each(function(){
			$("#wysiwyg_container input[name=element_id]").val(helpId);
			$(this).click(function(){				
				if($(this).attr("name") == "help_edit_link"){
					$("#wysiwyg_container").fadeIn("fast");					
					$("#wysiwyg_container textarea[name=wysiwyg]").val(ajaxData);					
				}
				else{
					removeWYSIWYGForm();
				}
					
				helpDiv.hide();
				removeHelpDiv();
			});
		});
	}
		
	function show(){
		//index.php?mod=help&act=get_help&id=
		$.get("index.php?mod=help&act=get_help&id="+helpId, function(ad){
			ajaxData = ad;						
			$("#wysiwyg").text(ajaxData);			
			helpDiv.append(helpDivControlers).append(ajaxData).fadeIn("fast");			
			initEvets(ajaxData);
			$('#wysiwyg').wysiwyg();
			//$("#wysiwyg_container").css("left", ((screen.width/2)-(($("#wysiwyg_container").width())/2)));
			//$("#wysiwyg_container").css("top", ((screen.height/2)-(($("#wysiwyg_container").height())/2)));			
		}); 
	}
	
	if(readCookie("helpstatus") == "on"){
		//show(helpId);
	}		
};
