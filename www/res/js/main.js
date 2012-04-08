
var hurimap = new Object(); 

$(document).ready(function() {

	var  input = $("#filter_text").find('td').find('div').find('input');	
	var  span = $("#filter_text").find('td').find('span');

	$(span).parent().click(function()
	{		
		$(this).find('span').hide();
		var element_id = $(this).find('span').text().replace(/ /gi, '_').toLowerCase();		
        var title = $("#"+element_id).attr("title");        
        
        $("#"+element_id).show();
        $("#"+element_id).focus();
        
	});


				    
	$(input).blur(function()
	{
		if($(this).val().length == 0){
			var title = $(this).attr("title");
//			$(this).hide();
			$("#span_"+title).show();			
		}
	});
					    
	$(input).blur();
	
	var  select = $("#filter_text").find('td').find('select');
						    
	$(select).blur(function()
	{		
		if ($(this).val().length == 0){			
			var title = $(this).attr("title");
//			$(this).hide();
			$("#span_"+title).show();
		}			
	});
							    
	$(select).blur();

});


function getAge(formname) {	
	var date_of_birth = formname.vdate_of_birth.value;
	var dob_type = formname.vdob_type.value;	
	
	if(date_of_birth == ''){
		alert(_("PERSON_S_DATE_OF_BIRTH_IS_NOT_ENTERED_"));
		return false;
	}
	
	var initial_date = formname.initial_date.value;
	
	year = date_of_birth.substring(0,4);
	month = date_of_birth.substring(5,7) - 1;	
	date = date_of_birth.substring(8,10);
	
	yearStr = initial_date.substring(0,4);
	monthStr = initial_date.substring(5,7) - 1;	
	dateStr = initial_date.substring(8,10);	
	
	var inyear = '';
	var inmonth = '';
	var indate = '';	
	
	if (month != parseInt(month)) { 
		alert(_('TYPE_MONTH_OF_BIRTH_IN_DIGITS_ONLY_')); 
		return false; 
	}
	 
	if (date != parseInt(date)){ 
		alert(_('TYPE_DATE_OF_BIRTH_IN_DIGITS_ONLY_')); 
		return false; 
	}
	if (year != parseInt(year)) { 
		alert(_('TYPE_YEAR_OF_BIRTH_IN_DIGITS_ONLY_')); 
		return false; 
	}
	if (year.length < 4) { 
		alert(_('TYPE_YEAR_OF_BIRTH_IN_FULL_')); 
		return false; 
	}	
	
	theYear = yearStr - year;	
	theMonth = monthStr - month;
	theDate = dateStr - date;
	
	var days = "";
	if (monthStr == 0 || monthStr == 2 || monthStr == 4 || monthStr == 6 || monthStr == 7 || monthStr == 9 || monthStr == 11) days = 31;
	if (monthStr == 3 || monthStr == 5 || monthStr == 8 || monthStr == 10) days = 30;
	if (monthStr == 1) days = 28;
	
	inyear = theYear;
	
	if (month < monthStr && date > dateStr) { 
		inyear = parseInt(inyear) + 1;
	    inmonth = theMonth - 1; 
	}
	
	if (month < monthStr && date <= dateStr) { 
		inmonth = theMonth; 
	}
	else if (month == monthStr && (date < dateStr || date == dateStr)) { 
		inmonth = 0; 
	}
	else if (month == monthStr && date > dateStr) { 
		inmonth = 11; 
	}
	else if (month > monthStr && date <= dateStr) { 
		inyear = inyear - 1;
		inmonth = ((12 - -(theMonth)) + 1); 
	}
	else if (month > monthStr && date > dateStr) { 
		inmonth = ((12 - -(theMonth))); 
	}
	
	if (date < dateStr) { 
		indate = theDate; 
	}
	else if (date == dateStr) { 
		indate = 0; 
	}
	else { 
		inyear = inyear - 1; 
		indate = days - (-(theDate)); 
	}
	
	if(inyear < 0){
		inyear = 0;
	}	
	
	//02000000000048 - Unknown day
	//03000000000048 - Unknown month and day
	//01000000000048 - Estimate
	
	if(dob_type == '02000000000048'){
		formname.age_at_time_of_victimisation.value = _("YEAR_S__") + inyear +_("_MONTH_S__")+ inmonth;
	}
	else if(dob_type == '03000000000048'){
		formname.age_at_time_of_victimisation.value = _("YEAR_S__") + inyear;
	}
	else if(dob_type == '01000000000048'){
		formname.age_at_time_of_victimisation.value = _("YEAR_S__") + inyear;
	}
	else{
		formname.age_at_time_of_victimisation.value = _("YEAR_S__") + inyear +_("_MONTH_S__")+ inmonth +_("_DAY_S__")+ indate;
	}
	
	
}

function add_anchor(oformCtrl,pageAnchor)
{
	var formname = $(oformCtrl).attr('id');	
	var link = $('#'+formname).attr("action");
	$('#'+formname).attr("action", link+ "#"+pageAnchor);	
}


function selected_doclist(oform)
{	
	var ol = $('#doclist').show();	
	var list = "";	
	
	var selected = new Array();	
	var m =0;
	for (k = 0; k < oform.length; k++){
		if(oform.elements[k].name == 'supporting_documents[]' && oform.elements[k].value != ''){			
			selected[m++] = oform.elements[k].value;			
		}		
	}
	
	var checked = new Array();
	var n = 0;
	var isExist = false;
	
	for (i = 0; i < oform.length; i++){		
		if(oform.elements[i].name == 'doc_id_list[]'){
			if(oform.elements[i].checked){
				str = oform.elements[i].value;				
				checked[n++] = str.substring(0,str.indexOf('_'));
			}
		}
	}	
	
	for(x = 0; x < selected.length; x++){
		for(y =0; y < checked.length; y++){
			if(checked[y] == selected[x]){				
				checked.splice(y,1);
				isExist = true;
			}			
		}
	}
				
	for (i = 0; i < oform.length; i++){		
		if(oform.elements[i].name == 'doc_id_list[]'){
			if(oform.elements[i].checked){
				str = oform.elements[i].value;
				docid = str.substring(0,str.indexOf('_'));				
				doctitle = str.substring(str.indexOf('_')+1, str.length);
				 				
				for(z = 0; z < checked.length; z++ ){
					if(checked[z] == docid){
						list += '<li id="'+ i +'">' + doctitle + '<input type="hidden" name ="supporting_documents[]" value="'+ docid + '"/>&nbsp;&nbsp;&nbsp;<a href="#address_field" onclick="removeElement('+ i +');">'+_('REMOVE')+'</a>' + '</li>';
						isExist = true;
					}
				}
				
				if(!isExist){
					list += '<li id="'+ i +'">' + doctitle + '<input type="hidden" name ="supporting_documents[]" value="'+ docid + '"/>&nbsp;&nbsp;&nbsp;<a href="#address_field" onclick="removeElement('+ i +');">'+_('REMOVE')+'</a>' + '</li>';
				}
			}
			oform.elements[i].checked = false;
		}		
	}
	ol.append(list);	
}

function edit_address(address_id)
{	
	var oforms = document.forms['person_form'];
	var address_record_number = 'person_address[' + address_id + '][address_record_number]';
	var address_type = 'person_address[' + address_id + '][address_type]';
	var address = 'person_address[' + address_id + '][address]';
	var country = 'person_address[' + address_id + '][country]';
	var phone = 'person_address[' + address_id + '][phone]';
	var cellular = 'person_address[' + address_id + '][cellular]';
	var fax = 'person_address[' + address_id + '][fax]';
	var email = 'person_address[' + address_id + '][email]';
	var web = 'person_address[' + address_id + '][web]';
	var start_date = 'person_address[' + address_id + '][start_date]';
	var end_date = 'person_address[' + address_id + '][end_date]';
		
	oforms['address_record_number'].value = oforms[address_record_number].value;
	oforms['address_type'].value = oforms[address_type].value;
	oforms['address'].value = oforms[address].value;
	oforms['country'].value = oforms[country].value;
	oforms['phone'].value = oforms[phone].value;
	oforms['cellular'].value = oforms[cellular].value;
	oforms['fax'].value = oforms[fax].value;
	oforms['email'].value = oforms[email].value;
	oforms['web'].value = oforms[web].value;
	oforms['start_date'].value = oforms[start_date].value;
	oforms['end_date'].value = oforms[end_date].value;
	//alert(oforms[address_record_number].value);
}

var address_count = 1;

function listAddress()
{
	var selected_country = document.getElementById('country');
	var selected_address = document.getElementById('address_type');
	var selected_address_str = selected_address.options[selected_address.selectedIndex].text;
	var selected_country_str = selected_country.options[selected_country.selectedIndex].text;
	
	var oforms = document.forms['person_form'];
	
	$('#addressArrayList').show();
	var table = $('#addressTable').show();	
	var tbody = table.find('tbody');
	
	var validate_msg = _('FOLLOWING_FIELDS_CAN_NOT_BE_EMPTY') + "\n";
	var is_validated = true;
	
	if(oforms['address_type'].value == ""){
		validate_msg += _("ADDRESS_TYPE") +"\n";
		is_validated = false;
	}
	
	if(oforms['validate_address'].value != ""){
		if(oforms['address'].value == ""){
			validate_msg += _("ADDRESS") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_country'].value != ""){
		if(oforms['country'].value == ""){
			validate_msg += _("COUNTRY") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_phone'].value != ""){
		if(oforms['phone'].value == ""){
			validate_msg += _("PHONE") +"\n";
			is_validated = false;
		}
	}	
	
	if(oforms['validate_cellular'].value != ""){
		if(oforms['cellular'].value == ""){
			validate_msg += _("CELLULAR") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_fax'].value != ""){
		if(oforms['fax'].value == ""){
			validate_msg += _("FAX") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_email'].value != ""){
		if(oforms['email'].value == ""){
			validate_msg += _("EMAIL") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_web'].value != ""){
		if(oforms['web'].value == ""){
			validate_msg += _("WEB") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_start_date'].value != ""){
		if(oforms['start_date'].value == ""){
			validate_msg += _("START_DATE") +"\n";
			is_validated = false;
		}
	}
	
	if(oforms['validate_end_date'].value != ""){
		if(oforms['end_date'].value == ""){
			validate_msg += _("END_DATE") +"\n";
			is_validated = false;
		}
	}
	
	if(is_validated){
		if(oforms['address_record_number'].value != ''){
			var address_id = oforms['address_record_number'].value;
			var add_count = 'person_address[' + address_id + '][address_count]';
			
			remove_field(oforms[add_count].value);
			
			tbody.append(''+
					'<tr id="'+ address_count +'" >'+
					'<td>'+ selected_address_str +'<input type="hidden" name="person_address['+address_id+']['+ 'address_count' + ']" value="'+ address_count +'" /><input type="hidden" name="person_address['+address_id+']['+ 'address_record_number' + ']" value="'+ oforms['address_record_number'].value +'" /><input type="hidden" name="person_address['+address_id+']['+ 'address_type' + ']" value="'+ oforms['address_type'].value +'" /></td>'+
					'<td>'+ oforms['address'].value  +'<input type="hidden" name="person_address['+address_id+']['+ 'address' + ']" value="'+ oforms['address'].value +'" /></td>'+
					'<td>'+ selected_country_str +'<input type="hidden" name="person_address['+address_id+']['+ 'country' + ']" value="'+ oforms['country'].value +'" /></td>'+
					'<td>'+ oforms['phone'].value  +'<input type="hidden" name="person_address['+address_id+']['+ 'phone' + ']" value="'+ oforms['phone'].value +'" /></td>'+
					'<td>'+ oforms['cellular'].value +'<input type="hidden" name="person_address['+address_id+']['+ 'cellular' + ']" value="'+ oforms['cellular'].value +'" /></td>'+
					'<td>'+ oforms['fax'].value +'<input type="hidden" name="person_address['+address_id+']['+ 'fax' + ']" value="'+ oforms['fax'].value +'" /></td>'+
					'<td>'+ oforms['email'].value +'<input type="hidden" name="person_address['+address_id+']['+ 'email' + ']" value="'+ oforms['email'].value +'" /></td>'+
					'<td>'+ oforms['web'].value +'<input type="hidden" name="person_address['+address_id+']['+ 'web' + ']" value="'+ oforms['web'].value +'" /></td>'+
					'<td>'+ oforms['start_date'].value +'<input type="hidden" name="person_address['+address_id+']['+ 'start_date' + ']" value="'+ oforms['start_date'].value +'" /></td>'+
					'<td>'+ oforms['end_date'].value +'<input type="hidden" name="person_address['+address_id+']['+ 'end_date' + ']" value="'+ oforms['end_date'].value +'" /></td>'+
					"<td><a id='edit_address_frm' href='#address_field' onclick='edit_address(\""+ address_id + "\");'>"+_('EDIT')+"</a> | <a href='#address_field' onclick='removeElement("+ address_count +");'>"+_('REMOVE')+"</a></td>"+
					'</tr>');
		}
		else{
			tbody.append(''+
					'<tr id="'+ address_count +'" >'+
					'<td>'+ selected_address_str +'<input type="hidden" name="person_address['+address_count+']['+ 'address_count' + ']" value="'+ address_count +'" /><input type="hidden" name="person_address['+address_count+']['+ 'address_record_number' + ']" value="'+ address_count +'" /><input type="hidden" name="person_address['+address_count+']['+ 'address_type' + ']" value="'+ oforms['address_type'].value +'" /></td>'+
					'<td>'+ oforms['address'].value  +'<input type="hidden" name="person_address['+address_count+']['+ 'address' + ']" value="'+ oforms['address'].value +'" /></td>'+
					'<td>'+ selected_country_str +'<input type="hidden" name="person_address['+address_count+']['+ 'country' + ']" value="'+ oforms['country'].value +'" /></td>'+
					'<td>'+ oforms['phone'].value  +'<input type="hidden" name="person_address['+address_count+']['+ 'phone' + ']" value="'+ oforms['phone'].value +'" /></td>'+
					'<td>'+ oforms['cellular'].value +'<input type="hidden" name="person_address['+address_count+']['+ 'cellular' + ']" value="'+ oforms['cellular'].value +'" /></td>'+
					'<td>'+ oforms['fax'].value +'<input type="hidden" name="person_address['+address_count+']['+ 'fax' + ']" value="'+ oforms['fax'].value +'" /></td>'+
					'<td>'+ oforms['email'].value +'<input type="hidden" name="person_address['+address_count+']['+ 'email' + ']" value="'+ oforms['email'].value +'" /></td>'+
					'<td>'+ oforms['web'].value +'<input type="hidden" name="person_address['+address_count+']['+ 'web' + ']" value="'+ oforms['web'].value +'" /></td>'+
					'<td>'+ oforms['start_date'].value +'<input type="hidden" name="person_address['+address_count+']['+ 'start_date' + ']" value="'+ oforms['start_date'].value +'" /></td>'+
					'<td>'+ oforms['end_date'].value +'<input type="hidden" name="person_address['+address_count+']['+ 'end_date' + ']" value="'+ oforms['end_date'].value +'" /></td>'+
					"<td><a id='edit_address_frm' href='#address_field' onclick='edit_address("+ address_count + ");'>"+_('EDIT')+"</a> | <a href='#address_field' onclick='removeElement("+ address_count +");'>"+_('REMOVE')+"</a></td>"+
					'</tr>');
		}
	
		address_count++;
		
		oforms['address_record_number'].value = "";
		oforms['address_type'].value = "";
		oforms['address'].value = "";
		//$('#country').remove();
		//$('#country_label').remove();
		//$('#country_remove').remove();            
		oforms['phone'].value = "";
		oforms['cellular'].value = ""
		oforms['fax'].value = "";
		oforms['email'].value ="";
		oforms['web'].value = "";
		oforms['start_date'].value = "";
		oforms['end_date'].value = "";
	}
	else{
		alert(validate_msg);
	}
}

function removeElement(elementID) {	
	if (confirm("Do you really want to remove this field?")){
		$('#'+elementID).remove();
	}
}

function remove_field(elementID) {	
	$('#'+elementID).remove();
}

    $(document).ready(function () {
		$("#admin_menu").treeview({
			collapsed: false,
			animated: "medium",
			control:"#sidetreecontrol",
			persist: "location"
		});
   	});


function addControler(name)
{
    $('#'+name).attr('name',name+'[]');
    $('#multi_slider_'+name).before('<br />')
    .before('<label><label>')
    .before('<input type="text" name="'+name+'[]" />');
}

window.onload = function(){ 
	$('#hidefrm').hide();
	$('#hideQueryfrm').hide();
	$('#frm_search').hide();
	$('#frm_saved_query').hide();
	$('#address_frm').hide();
	$('#hide_address_frm').hide();	
	$('#addressArrayList').hide();	
/*	
	if(readCookie("helpstatus") != "on" && readCookie("helpstatus") != "off"){
		createCookie("helpstatus","on",0);
		aObj = document.getElementById("help_switch_link");
		aObj.innerHTML = _("DISABLE_HELP");
	}else{
		aObj = document.getElementById("help_switch_link");
        if(readCookie("helpstatus") == "on")
		    aObj.innerHTML = _("DISABLE_HELP");
        else
		    aObj.innerHTML = _("ENABLE_HELP");
    }	*/
	$("input[type=submit]").each(function(){
		$(this).addClass("but");
		$(this).css("height",24);
	});
}

$(document).ready(function(){
	$("#show_related_event").click(function(){
		$('#show_related_event').hide();
		$('#hide_related_event').show();	
		$('#related_event_search').show();		
	});

	$("#hide_related_event").click(function(){
		$('#show_related_event').show();
		$('#hide_related_event').hide();	
		$('#related_event_search').hide();		
	});
	
	$("#related_event_search_close").click(function(){
		$('#show_related_event').show();
		$('#hide_related_event').hide();	
		$('#related_event_search').hide();		
	});
});

$(document).ready(function(){
	$("#show_related_victim").click(function(){
		$('#show_related_victim').hide();
		$('#hide_related_victim').show();	
		$('#related_victim_search').show();		
	});

	$("#hide_related_victim").click(function(){
		$('#show_related_victim').show();
		$('#hide_related_victim').hide();	
		$('#related_victim_search').hide();		
	});
	
	$("#related_victim_search_close").click(function(){
		$('#show_related_victim').show();
		$('#hide_related_victim').hide();	
		$('#related_victim_search').hide();		
	});
});


$(document).ready(function(){
	$("#show_related_person").click(function(){
		$('#show_related_person').hide();
		$('#hide_related_person').show();	
		$('#person_search_form').show();		
	});

	$("#hide_related_person").click(function(){
		$('#show_related_person').show();
		$('#hide_related_person').hide();	
		$('#person_search_form').hide();		
	});
	
	$("#related_person_search_close").click(function(){
		$('#show_related_person').show();
		$('#hide_related_person').hide();	
		$('#person_search_form').hide();		
	});
});

$(document).ready(function(){
	$("#show_address_frm").click(function(){
		$('#show_address_frm').hide();
		$('#hide_address_frm').show();	
		$('#address_frm').slideToggle();		
	});
	
	$("#edit_address_frm").click(function(){
		$('#show_address_frm').hide();
		$('#hide_address_frm').show();	
		$('#address_frm').show();		
	});

	$("#hide_address_frm").click(function(){
		$('#show_address_frm').show();
		$('#hide_address_frm').hide();	
		$('#address_frm').slideToggle();		
	});

	$("#close_address_frm").click(function(){
		$('#show_address_frm').show();		
		$('#hide_address_frm').hide();
		$('#address_frm').slideToggle();		
	});	
	
	$("#show_document").click(function(){
		$('#hide_document').show();
		$('#show_document').hide();		
		$('#document_search').show();		
	});
	
	$("#hide_document").click(function(){
		$('#hide_document').hide();
		$('#show_document').show();		
		$('#document_search').hide();		
	});

	$("#close_doc_search_form").click(function(){		
		$('#show_document').show();		
		$('#hide_document').hide();	
		$('#document_search').hide();	
	});

	$("#close_doc_add_form").click(function(){		
		$('#show_document').show();		
		$('#hide_document').hide();		
		$('#document_search').hide();				
	});	
	
});
/*
$(document).ready(function(){	
	$("#footer").click(function(){
		var hlpObj = new $.Helptextviewer($(this).attr("id"));					
	});
});
*/

function field_set_to_tab(id)
{
    $panel = $('#'+id);
    if($panel.length < 1)return;
    //get field sets
    $fieldset = $panel.find('fieldset');
    //hide selected
    $fieldset.hide();

    $card_list = $("<div class='card_list'></div>");
    $panel.before($card_list);
    
    //create ids
    $fieldset.each(function(i){
        $f = $(this);
        $f.attr('id','fieldset'+i);
        legend = $f.find('legend').text();
        $f.find('legend').remove();
        $tab = $("<a href='' class='tab' id='card"+i+"'>"+legend+"</a>");
        $card_list.append($tab);
        $tab.click(function (e){
            $fieldset.hide();
            $('#fieldset'+i).show();
            $('.tab').each(function (ii){$(this).removeClass('active')});
            $(this).addClass('active');
            return false;
        });
    });

    $('#fieldset0').show();
    $('#card0').addClass('active');
}

//====================== key board short cuts ============================================

jQuery(document).bind('keydown', 'Alt+Shift+e',function (evt){ keyEvent('events','browse');return false;});

jQuery(document).bind('keydown', 'Alt+Shift+p',function (evt){ keyEvent('person','browse');return false;});

jQuery(document).bind('keydown', 'Alt+Shift+d',function (evt){ keyEvent('docu','browse');return false;});

jQuery(document).bind('keydown', 'Alt+Shift+s',function (evt){ keyEvent('analysis','search');return false;});

jQuery(document).bind('keydown', 'Alt+Shift+a',function (evt){ keyEvent('admin','field_customization');return false;});

jQuery(document).bind('keydown', 'Alt+Shift+b',function (evt){ testMod("browse");return false;});

jQuery(document).bind('keydown', 'Alt+Shift+v',function (evt){ testMod("view");return false;});

jQuery(document).bind('keydown', 'Alt+Shift+n',function (evt){ testMod("new");return false;});

function keyEvent(mod,act){
    tMod=mod;
    tAct=act;
    var serverurl = window.location.href;
    var baseServerURL = new Array();
	baseServerURL=serverurl.split('?');
    targetURL=baseServerURL[0]+"?mod="+tMod+"&act="+tAct;           		
    setTimeout( "window.location.href = targetURL", 1*1 );  
}

function testMod(key){
    var serverurl = window.location.href;
    var baseServerURL = new Array();										
    baseServerURL=serverurl.split('?');                       
    modURL=baseServerURL[1].split('&');           
    modURL= modURL[0].split('mod=');

    switch(modURL[1])
    {
        case "events":
            switch(key){
                case "browse":
                    keyEvent('events','browse');
                    break;
                case "view":
                    keyEvent('events','get_event');
                    break;
                case "new":
                    keyEvent('events','new_event');
                    break;
            }
            break;
        case "person":
            switch(key){
                case "browse":
                    keyEvent('person','browse');
                    break;
                case "view":
                    keyEvent('person','person');
                    break;
                case "new":
                    keyEvent('person','new_person');
                    break;
            }
            break;
        case "docu":
            switch(key){
                case "browse":
                    keyEvent('docu','browse');
                    break;
                case "view":
                    keyEvent('docu','view_document');
                    break;
                case "new":
                    keyEvent('docu','new_document');
                    break;
            }
            break;
        default:
            return;
    }
        return false;
}


// ==== JQuery plugin for help popups ====
(function($){
    $.fn.help = function(options) {
        var defaults = {
            xOffset: 10,
            yOffset: 10,
            args: ""
        };
        var options = $.extend(defaults, options);

        return this.each(function()
        {
            obj = $(this);
            obj.click(function(event)
            {
                $('#help_popup').remove();
                $('body').append('<div id="help_popup" style="position: absolute;">Loading....</div>');
                $('#help_popup')
                    .css('top', (event.pageY - options.xOffset) + 'px')
                    .css('left',(event.pageX + options.yOffset) + 'px');
	            $("#help_popup ").load($(this).attr('href')+'&'+options.args);
                $(document).click(function (){$("#help_popup").remove();})
                return false; 
            });
        });

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);

// ==== clarifying note JQuery plugin ====
(function($){
    $.fn.clarify = function(options) {
        var defaults = {
            removeLinkClass: "remove_clarify",
            showLinkClass:"show_clarify"
        };
        var options = $.extend(defaults, options);

        return this.each(function() {
            obj = $(this);
            var id = obj.attr('id');

            function init() {
                //remove existing links
                $('#'+id+'_link').remove();

                //add a link
                var link = $("<a></a>");
                link.attr('id',id+'_link');
                link.attr('rel',id);
                obj.after(link);
                if(obj.attr('value') == ''){
                    obj.hide();
                    link.text(_('CLARIFY'));
                    link.attr('class',options.showLinkClass);
                    link.unbind('click');
                    link.click(showClarifyBox);
                }
                else{
                    link.text(_('REMOVE_CLARIFY'));
                    link.attr('class',options.removeLinkClass);
                    link.unbind('click');
                    link.click(removeClarifyBox);
                }
            }

            function showClarifyBox(){
                var obj = $('#'+$(this).attr('rel'));
                obj.show('fast');
                $(this).text(_("REMOVE_CLARIFY"))
                    .attr('class',options.removeLinkClass);
                $(this).unbind('click');
                $(this).bind('click', removeClarifyBox);
            }

            function removeClarifyBox(){
                var obj = $('#'+$(this).attr('rel'));
                obj.attr('value','');
                $(this).remove();
                obj.hide('fast');
                obj.clarify();
            }

            init();
        });

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);


//========= MT-Tree plugin =========
(function($){
    $.fn.mttree = function(options) {
        var defaults = {
            removeLinkClass: "remove_tree",
            showLinkClass:"show_tree"
        };
        var options = $.extend(defaults, options);

        return this.each(function()
        {
            obj = $(this);
            var id = obj.attr('id');
            obj.hide();
            
            obj.click(mttreeClickEvent);
            obj.bind('hideTree',hideTreeBox);
            if(obj.attr('mt_url') == undefined ){
                obj.treeview({
                    collapsed: true,
                    animated: "medium",
                    control:"#sidetreecontrol",
                    persist: "location"
                });
            }
            else{
                obj.treeview({
                    collapsed: true,
                    animated: "medium",
                    control:"#sidetreecontrol",
                    persist: "location",
                    url : obj.attr('mt_url')
                });
            }

            //add a link
            var link = $("<a></a>");
            link.attr('id',id+'_link');
            obj.after(link);

            link.text(_('CLICK_TO_SELECT_AN_OPTION'));
            link.attr('class',options.showLinkClass)
                    .click(showTreeBox);

            function mttreeClickEvent(e)
            {
            	if (e.target) targ = e.target;
            	else if (e.srcElement) targ = e.srcElement;
                var el = $(targ);
                if(el.attr('huricode') == undefined)return true;
                var data = {
                    huricode : el.attr('huricode'),
                    label    : el.text(),
                    field_id : $('#'+id).attr('field_id')
                };
                var obj = $('#'+data.field_id+'_ul') 
                obj.triggerHandler('selectTreeItem',data);
            }
 
            function showTreeBox(){
                var obj = $('#'+id);
                obj.show('fast');
                $('#'+id+'_link').text(_("HIDE_TREE"))
                    .attr('class',options.removeLinkClass)
                    .unbind('click',showTreeBox)
                    .click(hideTreeBox);
            }

            function hideTreeBox(){
                var obj = $('#'+id);
                obj.hide('fast');
                $('#'+id+'_link').text(_("CLICK_TO_SELECT_AN_OPTION"))
                    .attr('class',options.showLinkClass)
                    .unbind('click',hideTreeBox)
                    .click(showTreeBox);
            }           
        });

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);


(function($){
    $.fn.singletree = function(options) {
        var defaults = {
        };
        var options = $.extend(defaults, options);

        return this.each(function() {
            obj = $(this);
            var id = obj.attr('id');

            function init() {
                //remove existing links
                var ul = $('#'+id+'_ul');
                var value = obj.attr('value');
                if(value != ''){
                    var text = ul.find('span[huricode='+value+']').text();
                    if(text==''){
                        text = hurimap['code'+value];
                    }
                    addSelectLable(id , text);
                }
                ul.mttree().bind('selectTreeItem',selectTreeItem);
            }


            function selectTreeItem(event, data)
            {
                var obj = $('#'+data.field_id);
                obj.attr('value',data.huricode);
                //display the label
                addSelectLable(data.field_id , data.label);
                $('#'+data.field_id+'_ul').trigger('hideTree');
            }

            function addSelectLable(id , text)
            {
                var obj = $('#'+id);
                var label = $('#'+id+'_label');
                if(label.length == 0){
                    label = $('<span id="'+id+'_label"></span>');
                    obj.after(label);
                }
                label.text(text+'  ');
                var remove = $('#'+id+'_remove');
                if(remove.length == 0){
                    remove = $("<a id='"+id+"_remove'>"+_('REMOVE_SELECTION')+"</a>");
                    remove.click(removeTreeSelect);
                    label.after(remove);
                }
            }

            function removeTreeSelect()
            {
                var obj = $('#'+id);
                obj.attr('value','');
                $('#'+id+'_label').remove();
                $(this).remove();
            }

            init();
        });

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);


(function($){
    $.fn.multitree = function(options) {
        var defaults = {
            clarify:true
        };
        var options = $.extend(defaults, options);

        return this.each(function() {
            obj = $(this);
            var id = obj.attr('field_id');
            function init() {
                //remove existing links
                var ul = $('#'+id+'_ul');
                ul.mttree().bind('selectTreeItem',selectMultiTreeItem);
            }

            function selectMultiTreeItem(event, data)
            {
                //find if a duplicate exists
                var ol = $('#'+data.field_id+'_ol');
                var li = ol.find('[value='+data.huricode+']');
                if(li.length == 0){
                    //add list item
                    var li = $('<li></li>');
                    var lable = $('<span>'+data.label+' </span>');
                    var input = $('<input type="hidden" name="'+data.field_id+'[]" value="'+data.huricode+'" />');
                    var remove = $('<a>'+_('REMOVE')+'</a>').click(function(){$(this).parent().remove()});
                    li.append(input).append(lable).append(remove);
                    if(options.clarify){
                        var clari_id = id+'_'+data.huricode+'_clarify';
                        var clarify = $('<textarea name="'+clari_id+'" id="'+clari_id+'" class="clarify"></textarea>');
                        li.append($('<span> </span>'));
                        li.append(clarify);
                        clarify.clarify();
                    }
                    ol.append(li);
                }
            }

            init();
        });

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);



$(document).ready(function(){
    $('.single_tree').singletree();
    $('.multi_tree').multitree();
    $('.clarify').clarify();
    $('.help').help({args:'stream=text'});

    //reload page when logout
    $(document).ajaxComplete( function(event, xhr, ajaxOptions){
        var regex = /Sign in to OpenEvSys/i;
        if(xhr.responseText.search(regex) != -1){
           window.location.reload();
	}
    });
});


function update_mt(val, ori , id , lan)
{
    //send the data to server
    $.post("index.php?mod=admin&act=update_mt&stream=text", { 'msgid' : id , 'locale' : lan, 'msgstr' : val },
    function(data){
        if(data != "true"){
            alert(_('ERROR_OCCORED_WHILE_UPDATING_SERVER_')+data);
        }
      }, "text");
}

function addJqgrid(field)
{
	var colNames =$('#list123').getGridParam('colNames');
	var colModel =$('#list123').getGridParam('colModel');
	$('#list123').GridUnload();	
	
	var colLength = colNames.length;
	var modelLength = colModel.length;
	var colNames_new = new Array(colNames.length+1);
	var colModel_new = new Array(modelLength+1);
	for(var count = 0;colLength >= count; count++)
	{
		colNames_new[count] = colNames[count];
	}
	
	colNames_new[colLength] = field;
	
	for(var count = 0;modelLength >= count; count++)
	{
		colModel_new[count] = colModel[count];
	}
	colModel_new[modelLength] = {name:field, index:field, width:80};
	
	var options = {"div":"#list123","file":"example.php","rownum":"10","caption":"Search Results","pager":"#pager"};

	var rowList = [10,20,30];
	
	var jqgrid = new initJqgrid();
	jqgrid.init(options,colNames_new,colModel_new,rowList);
	
	//console.log(colNames_new);
	//debugger;
	
	
}

function createList(id)
{
	var test = queryBuilder.getInstance();
	alert(test.getSelectedEntities());
	
}
function remove(count)
{
	var sr =  searchResults.getInstance();
	sr.removeField(count);
}

//Help Enable Disable
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function setHelpStatus(){
	var helpstatus = readCookie("helpstatus");
	aObj = document.getElementById("help_switch_link");
	if(helpstatus == "off"){
		createCookie("helpstatus","on",0);
		aObj.innerHTML = _("DISABLE_HELP");		
	}
	else{
		createCookie("helpstatus","off",0);		
		aObj.innerHTML = _("ENABLE_HELP");
	}
		
}
