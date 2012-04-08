/*intializing jqgrid
@param
options  - Array
colNames - Array
colModel - Array
*/
function initJqgrid()
{
	

}

//singleton
searchResults.getInstance = function(){
    if (searchResults.instance == null) {
            searchResults.instance = new searchResults();
    }
    return searchResults.instance; 
}

function searchResults()
{
	this.query = null;
    this.results = null;

    this.setQuery = function(query){
        this.query = query;
    }

    this.updateToolBar = function(){
			$('#tb-ex-cvs').attr('href', $('#tb-ex-cvs').attr('href').replace(/&query=.*/i,'')+"&query="+encodeURI($.toJSON(this.query)));
			$('#tb-ex-ss').attr('href', $('#tb-ex-ss').attr('href').replace(/&query=.*/i,'')+"&query="+encodeURI($.toJSON(this.query)));
			$('#tb-ex-rpt').attr('href', $('#tb-ex-rpt').attr('href').replace(/&query=.*/i,'')+"&query="+encodeURI($.toJSON(this.query)));
    }

    this.initSaveQuery = function(){
        $('#tb-save').click(function(){$.blockUI({ message: $('#qb-save-query') , css: { 
                border: '#DDDD93 solid 4px', 
                padding: '0 15px 10px 15px', 
                backgroundColor: '#584430', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: 0.95, 
                color: '#fff',
                'text-align' : 'left'
            }  });
        });
        $('#qb-qs-cancle').click(function(){$.unblockUI();});
        $('#qb-qs-save').click(function(){
            $('#query-name').text($('#query_name').val());
            var sr = searchResults.getInstance();
            $.get($('#qb-save-query').attr('action')+'&stream=text', {'query':$.toJSON(sr.query), 'query_desc':$('#query_desc').val(), 'query_name':$('#query_name').val(), 'query_save':true}, function(data){
                $('#query_desc').val('');
                $('#query_name').val('');
                $.unblockUI();
            });
        });
    }
    this.initSaveQuery();
    
	this.updateJqgrid = function(options,colNames,colModel){
		
		
		jQuery('#list123').jqGrid({ 
			url:"index.php?mod=analysis&act=load_grid&query="+encodeURI($.toJSON(this.query)), 
			datatype: 'json', 
			mtype: 'GET', 
			colNames:colNames, 
			colModel :colModel, 
            pager: jQuery('#pager'), 
            rowNum:'10', 
            rowList:[10,20,30], 
            sortorder: "desc",
            scrollOffset : 0,
            viewrecords: true,
           // emptyrecords: _('Search did not match any records.'),
            width:900,
            height: 'auto', 
            imgpath: 'theme/jqgrid/basic/images', 
            caption: _('Search results'),
            loadComplete: function(xhr){
                var data = $.evalJSON(xhr.responseText);
                if(data.error != null)
                    $.pnotify({ pnotify_title: _('Error'), pnotify_text: _('An error was returned by the server, please contact system administrator.'), pnotify_type: 'error' });
            }
		}); 
		
		
        $("#list123").setGridWidth($(window).width() - 30);

        $(window).unbind('resize');
        $(window).resize(function() {
            $("#list123").setGridWidth($(window).width() - 30);
        });

        this.updateToolBar();	
	}
	

    this.fetchResultsAndDisplay = function(){
        var data = {'query':$.toJSON(this.query)};
        var colNames = new Array();
    	var colEntity = new Array();
    	var fieldNames = new Array();
        if(this.query.group_by == null)
        {
        	
            for(var i in this.query.select){
                colNames.push(openevsysDomain.getInstance().getFieldLabel(this.query.select[i].field, this.query.select[i].entity));
                colEntity.push(this.query.select[i].entity);
                fieldNames.push(this.query.select[i].field);
            }
        }
        else
        {
        	//var colNames = new Array();
        	for(var i in this.query.group_by){
                colNames.push(openevsysDomain.getInstance().getFieldLabel(this.query.group_by[i].field, this.query.group_by[i].entity));
                colEntity.push(this.query.select[i].entity);
                fieldNames.push(this.query.group_by[i].field);
            }
        	colNames.push(_('Count'));
        }
        //console.log(colEntity);
            var colModel = new Array(colNames.length);
            var options;
            for(var count = 0;colNames.length > count;count++)
            {
            	colModel[count] = new Array(4);
            }
            for(var count = 0;colNames.length > count;count++)
            {
            	colModel[count] = {name:fieldNames[count]+"_"+colEntity[count], index:fieldNames[count]+"_"+colEntity[count], width:100}
            }
       
   
        
        $('#list123').GridUnload();
        var result_panel = $('.resultPanel');
        result_panel.show();
        
        var grid_table = document.getElementById("jqgrid");
        
        if(this.query.group_by != null)
        {
        	document.getElementById("fg-menu").style.display = "none";
        }
        else
        {
        	document.getElementById("fg-menu").style.display = "block";
        }
        
        var sr =  searchResults.getInstance();
        sr.jqinit(options,colNames,colModel,colEntity,fieldNames);
    }

	this.jqinit = function(options,colNames_new,colModel_new,colEntity,fieldNames){
		
		this.updateJqgrid(options, colNames_new, colModel_new);
        try{
            if(this.query.group_by.length < 1 )
          		this.addRemoveLink(fieldNames,colEntity);
        }catch(e){
    		this.addRemoveLink(fieldNames,colEntity);
        }
		//console.log(fieldNames);
		this.initList();		
	}
	
	this.addField = function(field , entity){
		var colNames =$('#list123').getGridParam('colNames');
		var colModel =$('#list123').getGridParam('colModel');
		
		//console.log(colModel[0].name);
		//var colEntity = this.getEntity(colModel);
		$('#list123').GridUnload();
        openevsysDomain.getInstance().setSelectField(entity,field);
		this.query.select.push({'entity':entity , 'field': field });
		var colLength = colNames.length;
		var modelLength = colModel.length;
		var colNames_new = new Array(colNames.length+1);
		var colModel_new = new Array(modelLength+1);
		for(var count = 0;colLength >= count; count++)
		{
			colNames_new[count] = colNames[count];
		}
		
		colNames_new[colLength] = openevsysDomain.getInstance().getFieldLabel(field, entity);
		
		for(var count = 0;modelLength >= count; count++)
		{
			colModel_new[count] = colModel[count];
		}
		colModel_new[modelLength] = {name:field+"_"+entity, index:field+""+entity, width:80};
		
		var options = {"div":"#list123","file":"example.php","rownum":"10","caption":"Search Results","pager":"#pager"};
		var colEntity = this.getEntity(colModel_new);
		//var rowList = [10,20,30];
		var fieldNames = new Array();
		for(var i in this.query.select){
            fieldNames.push(this.query.select[i].field);
        }
		
		this.jqinit(options,colNames_new,colModel_new,colEntity,fieldNames);
		
	}
	
	this.initList = function (){
		var search = searchResults.getInstance();
		//console.log(search.getSelectedEntities());
		var entities = search.getSelectedEntities();
		var obj = openevsysDomain.getInstance();
		var fieldSet = 0;
		//console.log(this.query.select[0].entity);
		var list = "<ul>";
		for(var count=0;entities.length > count;count++)
		{
			var fields = obj.getEntityFields(entities[count]);
			list += "<li><a href='#'>"+entities[count]+"</a><ul>";
			for(var key in fields)
			{
				//console.log(key);
				for(countIn = 0;this.query.select.length > countIn; countIn++)
				{
					
					if(this.query.select[countIn].entity == entities[count] && this.query.select[countIn].field ==fields[key].value)
					{
						
						fieldSet = 1;
						break;
					}
					else
					{
						
						fieldSet = 0;
					}
				}
				if(fieldSet != 1)
				{
					list += "<li><a href='#' data-field='"+key+"' data-entity='"+entities[count]+"'>"+fields[key].label+"</a></li>";
					fieldSet = 0;
				}
				
			}
			list += "</ul></li>";
			
		}
		list +="</ul>";
		var addList = document.getElementById("news-items");
		addList.innerHTML = list;
		$('#hierarchy').menu({
			content: $('#hierarchy').next().html(),
			crumbDefaultText: ' '
		});
		
		$('#hierarchybreadcrumb').menu({
			content: $('#hierarchybreadcrumb').next().html(),
			backLink: false
		});
	}
	
	this.addRemoveLink = function (colNames,colEntity){
		
		var sr =  searchResults.getInstance();
		for(var count = 0;colNames.length > count; count++)
		{
			
			$("#jqgh_"+colNames[count]+"_"+colEntity[count]).before("<a class='qb-field-remove' onclick='remove("+count+")' >x</a>");
			//console.log($("#jqgh_"+colNames[count]+"_"+colEntity[count]).addClass(colEntity[count]));
			
			$("#jqgh_"+colNames[count]+"_"+colEntity[count]).addClass(colEntity[count]);
			//$("#jqgh_Event Record Number_event").addClass("test");
			
		}
		
		
	}
	this.removeField = function(count){
		
		var colNames =$('#list123').getGridParam('colNames');
		var colModel =$('#list123').getGridParam('colModel');
		$('#list123').GridUnload();	
		var entity = this.query.select[count].entity;
		var field = this.query.select[count].field;
        openevsysDomain.getInstance().unsetSelectField(entity,field);
		this.removeElement(this.query.select, count);
		var colModel_new = new Array(colNames.length-1);
		//console.log(colModel);
		this.removeElement(colNames, count);
		this.removeElement(colModel, count);
		var colEntity = this.getEntity(colModel);
		var options = {"div":"#list123","file":"example.php","rownum":"10","caption":"Search Results","pager":"#pager"};
		var fieldNames = new Array();
		for(var i in this.query.select){
            fieldNames.push(this.query.select[i].field);
        }
		this.jqinit(options,colNames,colModel,colEntity,fieldNames);
		return false;
	}
	this.removeElement = function(Earray,from)
	{
		var to =null;
		var res = Earray.slice((to || from)+1 || Earray.slice.length);
		Earray.length = from < 0 ? Earray.length + from :from;
		return Earray.push.apply(Earray,res);
	}
	this.getEntity = function(colModel)
	{
		var colEntity = new Array(colModel.length);
		for(var count = 0;colModel.length >count ; count++)
		{
			var colObj = colModel[count].name;
			var colId = colObj.split("_");
			colEntity[count] = colId[(colId.length)-1];
            //hack for biographic details and intervening party and documents
            switch(colEntity[count]){
                case 'details':
                    colEntity[count] = 'biographic_details';
                    break;
                case 'party':
                    colEntity[count] = 'intervening_party';
                    break;
                case 'meta':
                    colEntity[count] = 'supporting_docs_meta';
                    break;
                case 'events':
                    colEntity[count] = 'chain_of_events';
                    break;
            }
		}
		//console.log(colEntity);
		return colEntity;
	}
	this.getSelectedEntities = function (){
		var isSet = 0;
		var entities = new Array();
		for(var count = 0;this.query.select.length > count; count++){
			//console.log(this.query.select[count].entity);
			
			if(entities.length != 0)
			{
				for(var i = 0;entities.length >= i ;i++)
				{
					if(entities[i] == this.query.select[count].entity)
					{
						break;
					}
					else
					{
						entities[i+1] = this.query.select[count].entity;
					}
				}
			}
			else
			{
				entities[0] = this.query.select[count].entity;
			}
			
			
		}
		//console.log(entities);
		return entities;
		
	}
	
	
}
