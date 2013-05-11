/**
 * This file contain all the js for advance search
 */
/*{{{ Entity Domain*/
//singleton

/*Set the defaults for DataTables initialisation */
$.extend( true, $.fn.dataTable.defaults, {
    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
    "sPaginationType": "bootstrap",
    "oLanguage": {
        "sLengthMenu": "Display _MENU_ records"
    }
} );


/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
    "sWrapper": "dataTables_wrapper form-inline"
} );


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
    return {
        "iStart":         oSettings._iDisplayStart,
        "iEnd":           oSettings.fnDisplayEnd(),
        "iLength":        oSettings._iDisplayLength,
        "iTotal":         oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage":          oSettings._iDisplayLength === -1 ?
        0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
        "iTotalPages":    oSettings._iDisplayLength === -1 ?
        0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
    };
};


/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
    "bootstrap": {
        "fnInit": function( oSettings, nPaging, fnDraw ) {
            var oLang = oSettings.oLanguage.oPaginate;
            var fnClickHandler = function ( e ) {
                e.preventDefault();
                if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                    fnDraw( oSettings );
                }
            };

            $(nPaging).addClass('pagination').append(
                '<ul>'+
                '<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
                '<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
                '</ul>'
                );
            var els = $('a', nPaging);
            $(els[0]).bind( 'click.DT', {
                action: "previous"
            }, fnClickHandler );
            $(els[1]).bind( 'click.DT', {
                action: "next"
            }, fnClickHandler );
        },

        "fnUpdate": function ( oSettings, fnDraw ) {
            var iListLength = 5;
            var oPaging = oSettings.oInstance.fnPagingInfo();
            var an = oSettings.aanFeatures.p;
            var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

            if ( oPaging.iTotalPages < iListLength) {
                iStart = 1;
                iEnd = oPaging.iTotalPages;
            }
            else if ( oPaging.iPage <= iHalf ) {
                iStart = 1;
                iEnd = iListLength;
            } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
                iStart = oPaging.iTotalPages - iListLength + 1;
                iEnd = oPaging.iTotalPages;
            } else {
                iStart = oPaging.iPage - iHalf + 1;
                iEnd = iStart + iListLength - 1;
            }

            for ( i=0, ien=an.length ; i<ien ; i++ ) {
                // Remove the middle elements
                $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                // Add the new list items and their event handlers
                for ( j=iStart ; j<=iEnd ; j++ ) {
                    sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
                    $('<li '+sClass+'><a href="#">'+j+'</a></li>')
                    .insertBefore( $('li:last', an[i])[0] )
                    .bind('click', function (e) {
                        e.preventDefault();
                        oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
                        fnDraw( oSettings );
                    } );
                }

                // Add / remove disabled classes from the static elements
                if ( oPaging.iPage === 0 ) {
                    $('li:first', an[i]).addClass('disabled');
                } else {
                    $('li:first', an[i]).removeClass('disabled');
                }

                if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
                    $('li:last', an[i]).addClass('disabled');
                } else {
                    $('li:last', an[i]).removeClass('disabled');
                }
            }
        }
    }
} );


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( $.fn.DataTable.TableTools ) {
    // Set the classes that TableTools uses to something suitable for Bootstrap
    $.extend( true, $.fn.DataTable.TableTools.classes, {
        "container": "DTTT btn-group",
        "buttons": {
            "normal": "btn",
            "disabled": "disabled"
        },
        "collection": {
            "container": "DTTT_dropdown dropdown-menu",
            "buttons": {
                "normal": "",
                "disabled": "disabled"
            }
        },
        "print": {
            "info": "DTTT_print_info modal"
        },
        "select": {
            "row": "active"
        }
    } );

    // Have the collection use a bootstrap compatible dropdown
    $.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
        "collection": {
            "container": "ul",
            "button": "li",
            "liner": "a"
        }
    } );
}


openevsysDomain.getInstance = function(){
    if (openevsysDomain.instance == null) {
        openevsysDomain.instance = new openevsysDomain();
    }
    return openevsysDomain.instance; 
}

function openevsysDomain()
{
    this.data = null;
    openevsysDomain.instance = null;
    this.er = {
        'event': ['act','victim','perpetrator','involvement','intervention','information','source','chain_of_events'],
        'act'  : ['involvement','victim','perpetrator', 'arrest','torture','killing','destruction'],
        'victim' : ['involvement','perpetrator'],
        'involvement'  : ['perpetrator'],
        'perpetrator': [],
        'information': ['source'],
        'source' : [],
        'intervention': ['intervening_party','victim'],
        'intervening_party' : [],
        'supporting_docs_meta':[],
        'person':['address','biographic_details'],
        'biographic_details':[],
        'address': [],
        'chain_of_events':[],
        'arrest':['involvement','victim','perpetrator'],
        'torture':['involvement','victim','perpetrator'],
        'killing':['involvement','victim','perpetrator'],
        'destruction':['involvement','victim','perpetrator']
    }

    this.setSelectField = function(entity, field){
        try{
            if(this.data[entity].ac_type != null)
                entity = this.data[entity].ac_type;
        }catch(e){}
        this.data[entity].fields[field].select = 'y';
        for (key in this.data[entity].select) {
            if (this.data[entity].select[key] == field) {
                return;
            }
        }
        if(!this.data[entity].select){
            this.data[entity].select = new Array();
        }
        this.data[entity].select.push(field)
    }

    this.unsetSelectField = function(entity, field){
        try{
            if(this.data[entity].ac_type != null)
                entity = this.data[entity].ac_type;
        }catch(e){}
        this.data[entity].fields[field].select = 'n';
        for (key in this.data[entity].select) {
            if (this.data[entity].select[key] == field) {
                this.data[entity].select.splice(key, 1);
            }
        }
    }

    this.getEntityLabel = function(entity){
        var x = this.data[entity].label
        return x ;
    }

    this.getFieldLabel = function(field , entity){
        try{
            if(this.data[entity].ac_type != null)
                entity = this.data[entity].ac_type;
        }catch(e){}
        return this.data[entity].fields[field].label;
    }

    this.getEntityFields = function(entity){
        try{
            if(this.data[entity].ac_type != null)
                entity = this.data[entity].ac_type;
        }catch(e){}
        return this.data[entity].fields; 
    }

    this.getRelatedEntities = function(entities){
        var el = [];
        if(entities.length == 0){
            for (var i in this.data){
                el.push(this.data[i]);
            }
        }
        else{
            var last = entities[entities.length - 1];
            var elv = [];
            elv.push(last);
            elv = $.merge(elv,this.er[last]);
            for(var i in elv){
                el.push(this.data[elv[i]]);
            }
        }
        return el;
    }

    this.getEntities = function(arr){
        var el = [];
        for(var i in arr){
            el.push(this.data[arr[i]]);
        }
        return el;
    }

    this.fetchDomainData = function(callback){
        $.getJSON("index.php?mod=analysis&act=get_data_dict",callback);
    }

    this.getFieldType = function(field_name , entity_name){
        try{
            if(this.data[entity_name].ac_type != null)
                entity_name = this.data[entity_name].ac_type;
        }catch(e){}
        return this.data[entity_name].fields[field_name].field_type;
    }

    this.getListCode = function(field_name , entity_name){
        try{
            if(this.data[entity_name].ac_type != null)
                entity_name = this.data[entity_name].ac_type;
        }catch(e){}
        return this.data[entity_name].fields[field_name].list_code;
    }
    this.getMtSelectOptions = function(options) {
        var defaults = {
            url: "index.php?mod=home&act=mt_select&stream=text",
            selected : null
        };
        var options = $.extend(defaults, options);

        return $(this).load( options.url+'&list_code='+options.mt+'&selected='+options.selected );
    }

    this.getSelectFields = function(entity){
        try{
            if(this.data[entity].ac_type != null)
                entity = this.data[entity].ac_type;
        }catch(e){}
        var fields = this.data[entity].fields;
        if(this.data[entity].select != null)
            return this.data[entity].select;
        var arr = new Array();
        for(var i in fields){
            if(fields[i].select == 'y')
                arr.push(fields[i].value);
        }
        this.data[entity].select = arr;
        return arr;
    }

    this.getOperator = function(fieldType){
        switch(fieldType){
            case 'hidden':
            case 'textarea':
            case 'text':
                var o = [
                {
                    'value':'contains',
                    'label':_('CONTAINS')
                },

                {
                    'value':'like',
                    'label':_('LIKE')
                },

                {
                    'value':'=',
                    'label': '='
                },

                {
                    'value':'regex',
                    'label':_('REGEX')
                },

                {
                    'value':'not_contains',
                    'label':_('NOT_CONTAINS')
                },

                {
                    'value':'not_like',
                    'label':_('NOT_LIKE')
                },

                {
                    'value':'not_=',
                    'label':_('NOT_=')
                },

                {
                    'value':'soundex',
                    'label':_('SOUND_LIKE')
                },

                {
                    'value':'empty',
                    'label':_('IS_EMPTY')
                }
                ];
                break;
            case 'number':
                var o = [
                {
                    'value':'=',
                    'label': '='
                },

                {
                    'value':'<',
                    'label': '<'
                },

                {
                    'value':'>',
                    'label': '>'
                },

                {
                    'value':'<=',
                    'label': '<='
                },

                {
                    'value':'>=',
                    'label': '>='
                },

                {
                    'value':'not_=',
                    'label':_('NOT_=')
                },

                {
                    'value':'empty',
                    'label':_('IS_EMPTY')
                }
                ];
                break;
            case 'date':
                var o = [
                {
                    'value':'=',
                    'label':_('AT')
                },

                {
                    'value':'before',
                    'label':_('BEFORE')
                },

                {
                    'value':'at_or_before',
                    'label':_('AT_OR_BEFORE')
                },

                {
                    'value':'after',
                    'label': 'after'
                },

                {
                    'value':'at_or_after',
                    'label': 'at or after'
                },

                {
                    'value':'between',
                    'label':_('IN_BETWEEN')
                },

                {
                    'value':'not_=',
                    'label':_('NOT_ON')
                },

                {
                    'value':'not_between',
                    'label':_('NOT_IN_BETWEEN')
                },

                {
                    'value':'empty',
                    'label':_('IS_EMPTY')
                }
                ];
                break;
            case 'mt_tree':
                var o = [
                {
                    'value':'=',
                    'label':'='
                },

                {
                    'value':'sub',
                    'label':_('IS_A_SUB_ELEMENT_OF')
                },

                {
                    'value':'not_=',
                    'label':'not ='
                },

                {
                    'value':'empty',
                    'label':_('IS_EMPTY')
                }
                ];
                break;
            default:
                var o = [
                {
                    'value':'=',
                    'label': '='
                },
                {
                    'value':'not_=',
                    'label':'not ='
                },

                {
                    'value':'empty',
                    'label':_('IS_EMPTY')
                }
                ];
        }
        return o; 
    }
}/*}}}*/

/*{{{ Group by class */
groupBy.getInstance = function(){
    if (groupBy.instance == null) {
        groupBy.instance = new groupBy();
    }
    return groupBy.instance; 
}


function groupBy(){

    this.init = function(){
        this.update();
    }

    this.setQuery = function(query){
        if(query.group_by != null && query.group_by.length != 0){
            $('#qb-count-empty').remove();
            $('#query_builder_count').empty();
            od = openevsysDomain.getInstance();
            for(var c in query.group_by){
                var e = query.group_by[c];
                var select = $("<select id=\"\" name=\"\" class=\"entselectgroup\" />");
                $("<option />", {
                    value:  e.entity, 
                    text: od.getEntityLabel(e.entity)
                }).appendTo(select);
          
                var div = $("<div class='row-fluid show-grid'></div>");
                div.append(select);
                $('#query_builder_count').append(div);
                select.select2({
                    width: 'resolve'
                });
               
                
                
                var select = $("<select id=\"\" name=\""+e.entity+"\" class=\"fieldselectgroup\" />");
                $("<option />", {
                    value:  e.field, 
                    text: od.getFieldLabel(e.field , e.entity)
                }).appendTo(select);
        
              
                div.append(select);
                
                $(".entselectgroup").select2("disable");
                $(".fieldselectgroup").select2("disable");
                select.select2({
                    width: 'resolve'
                });
        
                var field_type = od.getFieldType(e.field, e.entity);
                
                switch(field_type){
                    case "date":
                        var dck = (e.type == 'daily')?' selected="true" ' : '' ;
                        var mck = (e.type == 'monthly')?' selected="true" ' : '' ;
                        var yck = (e.type == 'yearly')?' selected="true" ' : '' ;
                        var selectt = $('<select id="" name="" class=" searchval qb-count-type typeselectgroup"><option value="daily" '+dck+' >'+_('GROUP_BY_DAY')+'</option><option value="monthly"  '+mck+'>'+_('GROUP_BY_MONTHLY')+'</option><option value="yearly"  '+yck+'>'+_('GROUP_BY_YEARLY')+'</option></select>"');
                        div.append(selectt);
                        selectt.select2({
                            width: 'resolve'
                        });
                        break;
                    case "mt_tree":
                        var o1ck = (e.type == '1')?' selected="true" ' : '' ;
                        var o2ck = (e.type == '2')?' selected="true" ' : '' ;
                        var o3ck = (e.type == '3')?' selected="true" ' : '' ;
                        var o4ck = (e.type == '4')?' selected="true" ' : '' ;
                        var o5ck = (e.type == '5')?' selected="true" ' : '' ;
                        var o6ck = (e.type == '6')?' selected="true" ' : '' ;
                       
                        var selectt = $('<select id="" name="" class=" searchval qb-count-type typeselectgroup"><option value="1" '+o1ck+'>'+_('1ST_LEVEL')+'</option><option value="2" '+o2ck+'>'+_('2ND_LEVEL')+'</option><option value="3" '+o3ck+'>'+_('3RD_LEVEL')+'</option><option value="4" '+o4ck+'>'+_('4TH_LEVEL')+'</option><option value="5" '+o5ck+'>'+_('5TH_LEVEL')+'</option><option value="6" '+o6ck+'>'+_('6TH_LEVEL')+'</option></select>');
                        div.append(selectt);
                        selectt.select2({
                            width: 'resolve'
                        });
                        break;
                }
                
                var but = $("<div class='closediv' style='margin-top: 7px;'><a href='#' title='"+_('REMOVE_CONDITION')+"'  ><i class='icon-remove'></i></a></div>");
                div.append(but);
       
                $('#query_builder_count .closediv a').click(function(e){
                    e.preventDefault();
                    var div = $(this).closest("div.row-fluid");
                    div.remove();
           
                    var divlast = $('#query_builder_count').find('div.row-fluid:last')
                    var entity = divlast.find('select.entselectgroup:first').val();
                    if(entity == null || entity == "" ){
                        divlast.remove();
                        qbg = groupBy.getInstance(null);
                        qbg.addNewCondition();
                    }
            
                });
                
            }
            this.addNewCondition();            
            $('#qb-search-but').data('type','count')
            $('#qb-search-but').html('<i class="icon-ok"></i> '+_('COUNT'));
            $('#collapseCount').collapse('show');
            
        
        }
        else{
            this.update();
        }
    }

    this.update = function(){
        var entities = queryBuilder.getInstance().getSelectedEntities();
        $('#qb-count-empty').remove();
        if( entities.length == 0 ){
            $('#query_builder_count').empty();
            $('#query_builder_count').prepend("<span id='qb-count-empty'>"+_('YOU_NEED_TO_SELECT_AN_ENTITY_BEFORE_COUNTING')+"</span>");
            return;
        }
        var arr = $('#query_builder_count').find('.row-fluid');
        if(!arr.length){
            this.addNewCondition();
        }
        var newarr = $('#query_builder_count').find('.row-fluid.new');
        if(newarr.length){
            newarr.remove();
            this.addNewCondition();
        }
        
    }

    this.removeCondition = function(e){
        var con = $(e).parent();
        //if this is the last condition remove the link
        if(con == $('#qb-group-by').find('li:last'))
            alert('test');
        //if there is no new conditions add new condition
        if(con.attr('data-status')== 'new'){
            con.remove();
            this.addNewCondition();
        }
        else{
            con.remove();
        }
    }

    this.clearConditions = function(){
        $('#query_builder_count').empty();
    // this.addNewCondition();
    }

    this.addNewCondition = function(){
        var options = openevsysDomain.getInstance().getEntities(queryBuilder.getInstance().getSelectedEntities());
        
        var select = $("<select id=\"\" name=\"\" class=\"entselectgroup\" />");
        $("<option />", {
            value:  "", 
            text: ""
        }).appendTo(select);
          
        for(var option in options){
            $("<option />", {
                value:  options[option].value, 
                text: options[option].label
            }).appendTo(select);
 
        }
        var div = $("<div class='row-fluid show-grid new'></div>");
         
        div.append(select);
        $('#query_builder_count').append(div);
        select.select2({
            width: 'resolve',
            placeholder: _('SELECT_ENTITY'),
            allowClear:false
        });
        select.on("change", function() { 
            //alert($(this).val());
            qbg = groupBy.getInstance(null);
            qbg.addFieldSelect($(this));
        //groupBy.getInstance().update();
        });
       
    }


    this.addFieldSelect = function(entselect){
        $(".entselectgroup").select2("disable");
        
        var options = openevsysDomain.getInstance().getEntityFields(entselect.val());
       
        var select = $("<select id=\"\" name=\""+entselect.val()+"\" class=\"fieldselectgroup\" />");
        $("<option />", {
            value:  "", 
            text: ""
        }).appendTo(select);
        
        for(var option in options){
            $("<option />", {
                value:  options[option].value, 
                text: options[option].label
            }).appendTo(select);
       
        }
        var div = $(entselect).closest("div");
        div.append(select);
        $(div).removeClass('new');
        
        $(".entselectgroup").select2("disable");
        select.select2({
            width: 'resolve',
            placeholder: _('SELECT_FIELD')
        });
        select.on("change", function() { 
            //alert($(this).val());
            qbg = groupBy.getInstance(null);
            qbg.addField($(this));
        });
        
       
        
    }

    this.addField = function(fieldselect){
        $(".fieldselectgroup").select2("disable");
        this.addController(fieldselect);
        
        this.addNewCondition();
       
    }

    this.getGroupBy = function(){
        var g = new Array();
       
        var arr = $('#query_builder_count').find('.row-fluid');
        $.each(arr ,function(){
            if($(this).attr('data-status')=='new')return true;
            var c = new Object();
            
            c.entity = $(this).find('select.entselectgroup:first').val();
            c.field = $(this).find('select.fieldselectgroup:first').val();
            if(c.entity == "" || c.field == ""){
                return true;
            }
            c.type = $(this).find('select.typeselectgroup:first').val();
            g.push(c);
        });
        return g;
       
    }

    this.addController = function(fieldselect){
        var div = $(fieldselect).closest("div");
        //var entity = div.find(".entselect");
        var entity_name = fieldselect.attr("name");
        
        var field_name = fieldselect.val();
        
        var od = openevsysDomain.getInstance();
        var field_type = od.getFieldType(field_name, entity_name);
        var options = openevsysDomain.getInstance().getOperator(field_type);
               
        switch(field_type){
            case "date":
          
                var select = $("<select id=\"\" name=\"\" class=\"select searchval qb-count-type typeselectgroup\" />");
               
                $("<option />", {
                    value:  "daily", 
                    text: _('GROUP_BY_DAY')
                }).appendTo(select);
                $("<option />", {
                    value:  "monthly", 
                    text: _('GROUP_BY_MONTHLY')
                }).appendTo(select);
                $("<option />", {
                    value:  "yearly", 
                    text: _('GROUP_BY_YEARLY')
                }).appendTo(select);
                
                div.append(select);
                select.select2({
                    width: 'resolve'
                });
               
                break;
            case "mt_tree":
                var select = $("<select id=\"\" name=\"\" class=\"select searchval qb-count-type typeselectgroup\" />");
               
                $("<option />", {
                    value:  "1", 
                    text: _('1ST_LEVEL')
                }).appendTo(select);
                $("<option />", {
                    value:  "2", 
                    text: _('2ND_LEVEL')
                }).appendTo(select);
                $("<option />", {
                    value:  "3", 
                    text: _('3RD_LEVEL')
                }).appendTo(select);
                $("<option />", {
                    value:  "4", 
                    text: _('4TH_LEVEL')
                }).appendTo(select);
                $("<option />", {
                    value:  "5", 
                    text: _('5TH_LEVEL')
                }).appendTo(select);
                $("<option />", {
                    value:  "6", 
                    text: _('6TH_LEVEL')
                }).appendTo(select);
                
                div.append(select);
                select.select2({
                    width: 'resolve'
                });
                
               
                break;
        }
        var but = $("<div class='closediv' style='margin-top: 7px;'><a href='#' title='"+_('REMOVE_CONDITION')+"'  ><i class='icon-remove'></i></a></div>");
        div.append(but);
       
        $('#query_builder_count .closediv a').click(function(e){
            e.preventDefault();
            var div = $(this).closest("div.row-fluid");
            div.remove();
           
            var divlast = $('#query_builder_count').find('div.row-fluid:last')
            var entity = divlast.find('select.entselectgroup:first').val();
            if(entity == null || entity == "" ){
                divlast.remove();
                qbg = groupBy.getInstance(null);
                qbg.addNewCondition();
            }
            
        });
        
     
    }
}/*}}}*/

/*{{{ Query Builder */

//singleton 
queryBuilder.getInstance = function(){
    if (queryBuilder.instance == null) {
        queryBuilder.instance = new queryBuilder();
    }
    return queryBuilder.instance;
}

queryBuilder.createDate = function(element, mode){
    var options = {
        format:'Y-m-d',
        date: '2000-12-23',
        current: '2004-11-11',
        starts: 1,
        position: 'right',
        element : "ssss",
        onBeforeShow: function(e){
            $(this).DatePickerSetDate($(this).val(), true);
        },
        onChange: function(formated, dates){
            registerDate.val(formated);
        }
    };
    if(mode != null) 
        options.mode = mode;
    element.DatePicker(options);
}

function queryBuilder(){
    this.query = null;
    queryBuilder.instance = null;

    this.init = function(){
        var $qb = $('#query_builder');
        //if query is null print entity select
        if(this.query == null){
            $qb.append(this.addNewCondition());
        }
    }

    this.setQuery = function(query){
        $('#query_builder').empty();
        od = openevsysDomain.getInstance();
        for(var c in query.conditions){
            var e = query.conditions[c];
            var field_name = e.field;
            var entity_name = e.entity;
            
            var entselect = $("<select id=\"\" name=\"\" class=\"entselect\" />");
            $("<option />", {
                value:  entity_name, 
                text: od.getEntityLabel(entity_name)
            }).appendTo(entselect);
 
            var div = $("<div class='row-fluid show-grid'></div>");
            div.append(entselect);
            $('#query_builder').append(div);
            entselect.select2({
                width: 'resolve'
            }).select2("disable");
        
            var fieldselect = $("<select id=\"\" name=\""+entity_name+"\" class=\"fieldselect\" />");
            $("<option />", {
                value:  field_name, 
                text: od.getFieldLabel(field_name , entity_name)
            }).appendTo(fieldselect);
       
        
            div.append(fieldselect);
        
            fieldselect.select2({
                width: 'resolve'
            }).select2("disable");
    
        
            var field_type = od.getFieldType(field_name, entity_name);
        
            var options = openevsysDomain.getInstance().getOperator(field_type);
               
            var operatorselect = $("<select id=\"\" name=\"\" class=\"operatorselect\" />");
            for(var option in options){
                $("<option />", {
                    value:  options[option].value, 
                    text: options[option].label
                }).appendTo(operatorselect);
                
            }
       
            div.append(operatorselect);
                    
            operatorselect.val( e.operator ).attr('selected',true);
            operatorselect.select2({
                width: 'resolve'
            });
            
            
            if(field_type == 'date'){
                operatorselect.on("change", function() { 
                    var ov = $(this).val();
                    var d = $(this).parent().find('.dateselect')
                
                    if( ov == 'between' || ov == 'not_between'){
                        var o = $('<input type="text" value="" class="daterangepickerinput searchval dateselect" />');
                        //div.append(o);
                        o.daterangepicker({
                            format:'yyyy-MM-dd',
                            separator: ' , '
                        });
                    }
                    else{
                        var o = $('<input type="text" value="" class="datepicker searchval dateselect" />');
                        //div.append(o);
                        o.datepicker({
                            format:'yyyy-mm-dd'
                        });
                
                    }
                    d.replaceWith(o)
                });
            }
            
            switch(field_type){
                case "date":
                    if( e.operator == 'between' || e.operator == 'not_between'){
                        var o = $('<input type="text" value="" class="daterangepickerinput searchval dateselect" />');
                        div.append(o);
                        o.val(e.value);
                        o.daterangepicker({
                            format:'yyyy-MM-dd',
                            separator: ' , '
                        });
                    }
                    else{
                        var o = $('<input type="text" value="" class="datepicker searchval dateselect"  />');
                        div.append(o);
                        o.val(e.value);
                        o.datepicker({
                            format:'yyyy-mm-dd'
                        });
                    }
               
                    break;
                case "mt_select":
                    var mt_select = $("<select id=\"\" name=\"\" class=\"select searchval\" />");
                    //$("<option />", {value:  "", text: ""}).appendTo(select);
                    mt_select.qb_mt_select({
                        mt: od.getListCode(field_name, entity_name),
                        'selected' : e.value
                    });
                    div.append(mt_select);
                    //mt_select.val( e.value ).attr('selected',true);
            
                    mt_select.select2({
                        width: 'resolve'
                    });
                    
                    //mt_select.select2("val",e.value);
                    break;
                case "mt_tree":
                    var mt_tree = $('<select id=\"\" name=\"\" class=\"mt-tree select searchval\" />');
                    mt_tree.qb_mt_tree({
                        mt: od.getListCode(field_name, entity_name),
                        'selected' : e.value
                    });
                    div.append(mt_tree);
                    //mt_tree.val( e.value ).attr('selected',true);
            
                    mt_tree.select2({
                        width: 'resolve',
                        formatResult: format_mt_tree,
                        formatSelection: format_mt_tree
                    });
                    break;
                case "radio":
                    var name = genName();
                    var yesck = (e.value == 'y')?" checked='true' " : "" ;
                    var nock = (e.value == 'n')?" checked='true' " : "" ;
                 
                    var o = $("<label class='radio inline'><input type='radio' class='searchval-radio-yes' name='"+name+"' value='y' "+yesck+" />"+_('YES')+"</label><label class='radio inline'><input class='searchval-radio-no' type='radio' name='"+name+"' value='n' "+nock+"  />"+_('NO')+"</label>");
                    div.append(o);
                    break;
                case "checkbox":
                    if(e.value != '' && e.value != null){
                        var chked = 'checked="true"';
                    }else {
                        var chked = '';
                    }
                   
                    var o = $('<label class="checkbox inline"><input class="searchval-checkbox" type="checkbox" '+chked+' name="deceased"/></label>');
                    div.append(o);
                    break;
                default:
                    var o = $('<input type="text" value="" class="searchval" />');
                    o.val(e.value);
                    div.append(o);
                
            }
            
            if( e.link != null ){
                this.addAndOperater(div, e.link);
            }else{
                this.addAndOperater(div);
            }
            
        }
        this.addNewCondition();
        groupBy.getInstance().setQuery(query);

        //add select fields
        for(i in query.select){
            //var fields = openevsysDomain.getInstance().getSelectFields(query.select[i].entity);
            od.setSelectField(query.select[i].entity, query.select[i].field);
        }
    }

    this.addNewCondition = function(){
        var options = openevsysDomain.getInstance().getRelatedEntities(this.getSelectedEntities());
        
        var select = $("<select id=\"\" name=\"\" class=\"entselect\" />");
        $("<option />", {
            value:  "", 
            text: ""
        }).appendTo(select);
          
        for(var option in options){
            $("<option />", {
                value:  options[option].value, 
                text: options[option].label
            }).appendTo(select);
 
        }
        var div = $("<div class='row-fluid show-grid'></div>");
        div.append(select);
        $('#query_builder').append(div);
        select.select2({
            width: 'resolve',
            placeholder: _('SELECT_ENTITY'),
            allowClear:false
        });
        select.on("change", function() { 
            //alert($(this).val());
            qb = queryBuilder.getInstance(null);
            qb.addFieldSelect($(this));
            
        });
        return;
        
       
    }

    this.removeCondition = function(e){
        var con = $(e).parent();
        //if this is the last condition remove the link
        if(con == $('#query_builder').find('li:last'))
            alert('test');
        //if there is no new conditions add new condition
        if(con.attr('data-status')== 'new'){
            con.remove();
            this.addNewCondition();
        }
        else{
            con.remove();
        }

        if($('#query_builder li.qb-new').length > 0){
            $('#query_builder li.qb-new').remove();
            this.addNewCondition();
        }
    //groupBy.getInstance().update();
    }

    this.addFieldSelect = function(entselect){
        var options = openevsysDomain.getInstance().getEntityFields(entselect.val());
       
        var select = $("<select id=\"\" name=\""+entselect.val()+"\" class=\"fieldselect\" />");
        $("<option />", {
            value:  "", 
            text: ""
        }).appendTo(select);
        
        for(var option in options){
            $("<option />", {
                value:  options[option].value, 
                text: options[option].label
            }).appendTo(select);
       
        }
        var div = $(entselect).closest("div");
        div.append(select);
        
        $(".entselect").select2("disable");
        select.select2({
            width: 'resolve',
            placeholder: _('SELECT_FIELD')
        });
        select.on("change", function() { 
            //alert($(this).val());
            qb = queryBuilder.getInstance(null);
            qb.addField($(this));
            groupBy.getInstance().update();
        });
        
        
        return;
        	
    }

    this.addField = function(fieldselect){
        $(".fieldselect").select2("disable");
        this.addController(fieldselect);
        this.addNewCondition();
        return;
       
    }

    this.addController = function(fieldselect){
        var div = $(fieldselect).closest("div");
        //var entity = div.find(".entselect");
        var entity_name = fieldselect.attr("name");
        
        var field_name = fieldselect.val();
        
        var od = openevsysDomain.getInstance();
        var field_type = od.getFieldType(field_name, entity_name);
        
        var options = openevsysDomain.getInstance().getOperator(field_type);
               
        var select = $("<select id=\"\" name=\"\" class=\"operatorselect\" />");
        
        for(var option in options){
            $("<option />", {
                value:  options[option].value, 
                text: options[option].label
            }).appendTo(select);
        }
       
        div.append(select);
        select.select2({
            width: 'resolve'
        });
        if(field_type == 'date'){
            select.on("change", function() { 
                var ov = $(this).val();
                var d = $(this).parent().find('.dateselect')
                
                if( ov == 'between' || ov == 'not_between'){
                    var o = $('<input type="text" value="" class="daterangepickerinput searchval dateselect" />');
                    //div.append(o);
                    o.daterangepicker({
                        format:'yyyy-MM-dd',
                        separator: ' , '
                    });
                }
                else{
                    var o = $('<input type="text" value="" class="datepicker searchval dateselect" />');
                    //div.append(o);
                    o.datepicker({
                        format:'yyyy-mm-dd'
                    });
                
                }
                d.replaceWith(o)
            });
        }
        switch(field_type){
            case "date":
                var o = $('<input type="text" value="" class="datepicker searchval dateselect" />');
                div.append(o);
                o.datepicker({
                    format:'yyyy-mm-dd'
                });
               
               
                break;
            case "mt_select":
                var mt_select = $("<select id=\"\" name=\"\" class=\"select searchval\" />");
                //$("<option />", {value:  "", text: ""}).appendTo(select);
                mt_select.qb_mt_select({
                    mt: od.getListCode(field_name, entity_name)
                });
                div.append(mt_select);
                mt_select.select2({
                    width: 'resolve'
                });
               
                break;
            case "mt_tree":
                var mt_tree = $('<select id=\"\" name=\"\" class=\"mt-tree select searchval\" />');
                mt_tree.qb_mt_tree({
                    mt: od.getListCode(field_name, entity_name)
                });
                div.append(mt_tree);
                mt_tree.select2({
                    width: 'resolve',
                    formatResult: format_mt_tree,
                    formatSelection: format_mt_tree
                });
                break;
            case "radio":
                var name = genName();
                var o = $("<label class='radio inline'><input type='radio' class='searchval-radio-yes' name='"+name+"' value='y'/>"+_('YES')+"</label><label class='radio inline'><input class='searchval-radio-no' type='radio' name='"+name+"' value='n'  />"+_('NO')+"</label>");
                div.append(o);
                break;
            case "checkbox":
                var o = $('<label class="checkbox inline"><input class="searchval-checkbox" type="checkbox"  name="deceased"/></label>');
                div.append(o);
                break;
            default:
                var o = $('<input type="text" value="" class="searchval" />');
                div.append(o);
        }
        
        this.addAndOperater(div);
        
        return;
  
    }

    this.addAndOperater = function(div , value){
        var options = [
        {
            'value':'and',
            'label':_('AND')
        },

        {
            'value':'or' ,
            'label':_('OR')
        }
        ];
        var select = $("<select id=\"\" name=\"\" class=\"operselect\" />");
        for(var i in options){
                   
            $("<option />", {
                value:  options[i].value, 
                text: options[i].label
            }).appendTo(select);
            
        }
        //var div = $(sel).closest("div");
        div.append(select);
        select.val( value ).attr('selected',true);
            
        //$('#query_builder').append(div);
        select.select2({
            width: 'resolve'
        });
        
        var but = $("<div class='closediv'><a href='#' title='"+_('REMOVE_CONDITION')+"' ><i class='icon-remove'></i></a></div>");
        div.append(but);
       
        $('#query_builder .closediv a').click(function(e){
            e.preventDefault();
            var div = $(this).closest("div.row-fluid");
            div.remove();
           
            var divlast = $('#query_builder').find('div.row-fluid:last')
            var entity = divlast.find('select.entselect:first').val();
            if(entity == null || entity == "" ){
                divlast.remove();
                qb = queryBuilder.getInstance(null);
           
                qb.addNewCondition();
            }
            
            groupBy.getInstance().update();
        });
        
        return;
        
        
       
    }

    this.clearConditions = function(){
        $('#query_builder').empty();
        this.addNewCondition();
    }

    this.getSelectedEntities = function(){
        var arr = $('#query_builder').find('.entselect');
        var entities = new Array();
        var arr = $('#query_builder').find('.row-fluid');
        $.each(arr ,function(){
            if($(this).attr('data-status')=='new')return true;
            var entity = $(this).find('select.entselect:first').val();
            var field = $(this).find('select.fieldselect:first').val();
            if(field != null && field != "" && entity != null && entity != "" && $.inArray(entity , entities) == -1){
                entities.push(entity);
            }
        });
        return entities;
        
       
    }

    this.getQuery = function(){
        //create condition array
        var conditions = new Array();
        var arr = $('#query_builder').find('.row-fluid');
        $.each(arr ,function(){
            if($(this).attr('data-status')=='new')return true;
            var c = new Object();
            
            c.entity = $(this).find('select.entselect:first').val();
            c.field = $(this).find('select.fieldselect:first').val();
            if(c.entity == "" || c.field == ""){
                return true;
            }
            c.operator = $(this).find('select.operatorselect:first').val();
            if($(this).find('.searchval').is("select")){
                c.value = $(this).find('select.searchval:first').val();
            }else{
                c.value = $(this).find('.searchval:first').val();
            }
            
            //handle checkboxes
            if($(this).find('.searchval-checkbox').length > 0 ){
                if($(this).find('.searchval-checkbox').is(':checked'))
                    c.value = 'y';
                else
                    c.value = 'n';
            }
            //handle radio buttons
            if($(this).find('.searchval-radio-yes').length > 0 ){
                if($(this).find('.searchval-radio-yes').is(':checked'))
                    c.value = 'y';
            }
            if($(this).find('.searchval-radio-no').length > 0 ){
                if($(this).find('.searchval-radio-no').is(':checked'))
                    c.value = 'n';
            }
            c.link = $(this).find('select.operselect:first').val();
            
            conditions.push(c);
        });

        //display select variables
        var select = new Array();
        var entities = this.getSelectedEntities();
        
        for(var i in entities){
            var fields = openevsysDomain.getInstance().getSelectFields(entities[i]);
            
            for(var f in fields){
                var field = {
                    'entity': entities[i], 
                    'field': fields[f]
                }
                select.push(field)
            }
        }

        var query = new Object();
        query.conditions = conditions;
        query.select = select;
        
        
        return query;
    }
}/*}}}*/

/*{{{ Advance search */
advSearch.getInstance = function(){
    if (advSearch.instance == null) {
        advSearch.instance = new advSearch();
    }
    return advSearch.instance; 
}

/* Advance search class */
function advSearch(){
    this.query_builder = null;
    this.search_result = null;
    this.group_by = null;
    this.additional_fields = new Array();
    this.oTable = null;
    
    this.setQuery = function(query){
        this.query_builder.setQuery(query);
    }

    this.updateToolBar = function(){
        $('div.toolbar #tb-ex-cvs').attr('href', $('div.toolbar #tb-ex-cvs').attr('href').replace(/&query=.*/i,'')+"&query="+encodeURI($.toJSON(this.query)));
        $('div.toolbar #tb-ex-ss').attr('href', $('div.toolbar #tb-ex-ss').attr('href').replace(/&query=.*/i,'')+"&query="+encodeURI($.toJSON(this.query)));
        $('div.toolbar #tb-ex-rpt').attr('href', $('div.toolbar #tb-ex-rpt').attr('href').replace(/&query=.*/i,'')+"&query="+encodeURI($.toJSON(this.query)));
    }
    this.initAdditionalFieldsList = function (){
        var entities = queryBuilder.getInstance().getSelectedEntities();
        
        var obj = openevsysDomain.getInstance();
        var fieldSet = 0;
        var list = "<ul>";
        for(var count=0;entities.length > count;count++)
        {
            var fields = obj.getEntityFields(entities[count]);
            
            list += "<li><a href='#'>"+obj.getEntityLabel(entities[count])+"</a><ul>";
            for(var key in fields)
            {
                if(fields[key].field_type == "location"){
                    continue;
                }
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
        $("div.toolbar #news-items").html(list)
        /*var addList = document.getElementById("news-items");
		addList.innerHTML = list;*/
        $('div.toolbar #hierarchy').menu({
            content: $('div.toolbar #hierarchy').next().html(),
            crumbDefaultText: ' ',
            callerOnState: ''
        });
		
        $('div.toolbar #hierarchybreadcrumb').menu({
            content: $('div.toolbar #hierarchybreadcrumb').next().html(),
            backLink: false,
            callerOnState: ''
        });
    /*
        *  //console.log(this.query.select[0].entity);
        var select = $("<select id=\"\" name=\"\" class=\"addfieldselect\" multiple=\"multiple\"  />");
        /*$("<option />", {
            value:  "", 
            text: ""
        }).appendTo(select);*/
    
    /*for(var count=0;entities.length > count;count++)
        {
            var fields = obj.getEntityFields(entities[count]);
            var optGroup  = $("<optGroup />", {
                label:  obj.getEntityLabel(entities[count])
            });
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
                    var option =  $("<option />", {
                        value:  key, 
                        text: fields[key].label
                    })
                    option.data('field',key);
                    option.data('entity',entities[count]);
                    option.appendTo(optGroup);
                    //list += "<li><a href='#' data-field='"+key+"' data-entity='"+entities[count]+"'>"+fields[key].label+"</a></li>";
                    fieldSet = 0;
                }
				
            }
            optGroup.appendTo(select);
            
			
        }
        
        $("#addfieldselectbox").append(select);
        select.select2({
            width: 'resolve',
            closeOnSelect:false,
            placeholder: _('ADD_FIELDS_TO_SEARCH_RESULTS'),
            allowClear:false
        });*/
        
    }
	
	
    this.addField = function(field , entity){
        openevsysDomain.getInstance().setSelectField(entity,field);
        this.Search();
        return;
        
		
    }
    this.removeField = function(field , entity){
        openevsysDomain.getInstance().unsetSelectField(entity,field);
        this.Search();
        return;
        
		
    }
	
	
    this.Search = function(){
        this.query = this.query_builder.getQuery();
        
        if(this.query.conditions < 1 ){
            $.pnotify({
                pnotify_title: 'Error', 
                pnotify_text: _('PLEASE_ADD_A_CONDITION_BEFORE_SEARCH_'), 
                pnotify_type: 'error'
            });
            return;
        }
        this.fetchResultsAndDisplay()

    }
    this.fetchResultsAndDisplay = function(stype){
        
        this.query = this.query_builder.getQuery();
        if(this.query.conditions < 1 ){
           
            return;
        }
        
        od = openevsysDomain.getInstance();
        var columns = new Array();
            
        if(stype == "count"){
            this.query.group_by = this.group_by.getGroupBy();
       
            for (var i = 0; i < this.query.group_by.length; i++) {
                var sel = this.query.group_by[i];
                var column = new Object();
                column.mData = sel.entity+"_"+sel.field
                column.sTitle = od.getFieldLabel(sel.field ,sel.entity);
                
                column.entity = sel.entity;
                column.field = sel.field;
                columns.push(column);
            } 
             
            var column = new Object();
            column.mData = "count"
            column.sTitle = _('COUNT');
            columns.push(column);
        }else{
            for (var i = 0; i < this.query.select.length; i++) {
                var sel = this.query.select[i];
                var column = new Object();
                column.mData = sel.entity+"_"+sel.field
                column.sTitle = od.getFieldLabel(sel.field ,sel.entity);
                column.entity = sel.entity;
                column.field = sel.field;
                columns.push(column);
            }
        }
        if(this.oTable){
            this.oTable.fnDestroy();
        }
        $('#datatable').html("")
        var settings =  {
            "sDom": "<'row'<'span6'<'toolbar'>><'span6'l>r>t<'row'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": 'Display <select>'+
            '<option value="10">10</option>'+
            '<option value="20">20</option>'+
            '<option value="30">30</option>'+
            '<option value="40">40</option>'+
            '<option value="50">50</option>'+
            '<option value="-1">All</option>'+
            '</select> records'
            },
            "bAutoWidth": false,
            "bDestroy": true,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=analysis&act=load_grid&query="+encodeURI($.toJSON(this.query)),
            "aoColumns":columns
        };
        
        this.oTable = $('#datatable').dataTable( settings);
        
        $("div.toolbar").html($("#toolbar2").html());
        $("#toolbar2").hide();
        this.initAdditionalFieldsList();
        this.updateToolBar();

        //oTable.fnAdjustColumnSizing();
        
        $('#datatable th').each( function (i) {
            $(this).html($(this).html()+" <a href='' title='remove column'  onclick='return removeField(\""+columns[i].field+"\",\""+columns[i].entity+"\")'>x</a>");
        } );
        $('#qb-qs-save').click(function(){
            
            $('#query-name').text($('#query_name').val());
            var q = queryBuilder.getInstance().getQuery()
            q.group_by = groupBy.getInstance().getGroupBy();
        
            $.get($('#qb-save-query').attr('action')+'&stream=text', {
                'query':$.toJSON(q), 
                'query_desc':$('#query_desc').val(), 
                'query_name':$('#query_name').val(), 
                'query_save':true
            }, function(data){
                $('#query_desc').val('');
                $('#query_name').val('');
                $('#saveModal').modal('hide')
            });
        });
        var result_panel = $('.resultPanel');
        result_panel.show();
    }

    this.Count = function(){
        this.query = this.query_builder.getQuery();
        this.query.group_by = this.group_by.getGroupBy();
        
        if(this.query.group_by.length < 1 ){
            $.pnotify({
                pnotify_title: 'Error', 
                pnotify_text: _('PLEASE_ADD_A_CONDITION_INSIDE_THE_COUNT_BOX_TO_COUNT_'), 
                pnotify_type: 'error'
            });
            return;
        }
        this.fetchResultsAndDisplay("count")
    }

    /* This function will initialise the advance search class */
    this.init = function(){
        this.group_by = groupBy.getInstance();
        
        $(document).ready(function(){
            var domain = openevsysDomain.getInstance();
            domain.fetchDomainData(advSearch.initObjects);
        });
        this.query_builder = queryBuilder.getInstance();
        this.group_by = groupBy.getInstance();
        this.group_by.init();
        $('#collapseCount').on('show', function () {
            $('#qb-search-but').data('type','count')
            $('#qb-search-but').html('<i class="icon-ok"></i> '+_('COUNT'))
        })
        $('#collapseCount').on('hide', function () {
            $('#qb-search-but').data('type','search')
            $('#qb-search-but').html('<i class="icon-ok"></i> '+_('SEARCH'))
        })
        
        $('#qb-clear-but').click(function(){
            queryBuilder.getInstance().clearConditions();
            //groupBy.getInstance().clearConditions();
            groupBy.getInstance().update();
            $('.resultPanel').hide();
        });
        $('#qb-search-but').click(function(){
            var stype = $('#qb-search-but').data('type')
            if(stype == "count"){
                as.Count();
            }else{
                as.Search();
            }
        });
    }

    advSearch.initObjects = function(data){
        var domain = openevsysDomain.getInstance();
        domain.data = data;
        queryBuilder.getInstance().init();
        if(query != ''){
            
            //var sr = searchResults.getInstance();
            var q = $.secureEvalJSON(query);
            //console.log(q)
           
            as.setQuery(q);
            
            if(q.group_by != null && q.group_by.length != 0){
                as.fetchResultsAndDisplay("count");
            }else{
                as.fetchResultsAndDisplay();
            }
            //sr.setQuery(q);
            //sr.fetchResultsAndDisplay();
            queryBuilder.getInstance().setQuery(q);
        }
        
    }

  
}
/*}}}*/

var registerDate = null;
var namenumber = 0;
function genName(){
    return 'name' + namenumber++;
}
function removeField(field,entity){
    var as = advSearch.getInstance();
    as.removeField(field,entity);
    return false;
		
}

//Jquery plugin to fetch microthusari values
(function($){
    $.fn.qb_mt_select = function(options) {
        var defaults = {
            url: "index.php?mod=home&act=mt_select&stream=text",
            selected : null
        };
        var options = $.extend(defaults, options);

        return this.each(function()
        {
            $(this).load( options.url+'&list_code='+options.mt+'&selected='+options.selected,function(){
                if(options.selected){
                    $(this).val( options.selected ).attr('selected',true);
            
                    $(this).select2("val",options.selected);
                }
            } );
        });


        function debug($obj) {
            if (window.console && window.console.log) {
            ;//console.log();
            }
        };
    };
})(jQuery);



(function($){
    $.fn.qb_mt_tree = function(options) {
        var defaults = {
            options: ['Empty...'],
            multi  : false,
            url:'index.php?mod=home&act=mt_tree&stream=text',
            selected: null
        };
        var options = $.extend(defaults, options);

        return this.each(function()
        {
            $(this).load( options.url+'&list_code='+options.mt+'&selected='+options.selected,function(){
                if(options.selected){
                    $(this).val( options.selected ).attr('selected',true);
            
                    $(this).select2("val",options.selected);
                }
            } );
        });
        
        

        function debug($obj) {
            if (window.console && window.console.log) {
            ;//console.log();
            }
        };
    };
})(jQuery);
