/**
 * This file contain all the js for advance search
 */
/*{{{ Entity Domain*/
//singleton
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
                'destruction':['involvement','victim','perpetrator'],
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
                            {'value':'contains','label':_('CONTAINS')},
                            {'value':'like','label':_('LIKE')},
                            {'value':'=','label': '='},
                            {'value':'regex','label':_('REGEX')},
                            {'value':'not_contains','label':_('NOT_CONTAINS')},
                            {'value':'not_like','label':_('NOT_LIKE')},
                            {'value':'not_=','label':_('NOT_=')},
                            {'value':'soundex','label':_('SOUND_LIKE')},
                            {'value':'empty','label':_('IS_EMPTY')}
                        ];
                break;
            case 'number':
                var o = [
                            {'value':'=','label': '='},
                            {'value':'<','label': '<'},
                            {'value':'>','label': '>'},
                            {'value':'<=','label': '<='},
                            {'value':'>=','label': '>='},
                            {'value':'not_=','label':_('NOT_=')},
                            {'value':'empty','label':_('IS_EMPTY')}
                        ];
                break;
            case 'date':
                var o = [
                            {'value':'=','label':_('AT')},
                            {'value':'before','label':_('BEFORE')},
                            {'value':'at_or_before','label':_('AT_OR_BEFORE')},
                            {'value':'after','label': 'after'},
                            {'value':'at_or_after','label': 'at or after'},
                            {'value':'between','label':_('IN_BETWEEN')},
                            {'value':'not_=','label':_('NOT_ON')},
                            {'value':'not_between','label':_('NOT_IN_BETWEEN')},
                            {'value':'empty','label':_('IS_EMPTY')}
                        ];
                break;
            case 'mt_tree':
                var o = [
                            {'value':'=','label':'='},
                            {'value':'sub','label':_('IS_A_SUB_ELEMENT_OF')},
                            {'value':'not_=','label':'not ='},
                            {'value':'empty','label':_('IS_EMPTY')}
                        ];
                break;
            default:
                var o = [
                            {'value':'=','label': '=' },
                            {'value':'not_=','label':'not ='},
                            {'value':'empty','label':_('IS_EMPTY')}
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
        if(query.group_by != null ){
            $('#qb-count-empty').remove();
            $('#qb-group-list').empty();
            od = openevsysDomain.getInstance();
            for(var c in query.group_by){
                var e = query.group_by[c];
                var con = $('<li class="qb-condition"></li>');
                con.append('<a class="qb-entity qb-selected" data-value="'+e.entity+'">'+od.getEntityLabel(e.entity)+'</a><span>.</span>');
                con.append('<a class="qb-field qb-selected" data-value="'+e.field+'">'+od.getFieldLabel(e.field , e.entity)+'</a>');
                var field_type = od.getFieldType(e.field, e.entity);
                /* Add controller */
                switch(field_type){
                    case "date":
                        var dck = (e.type == 'daily')?' selected="true" ' : '' ;
                        var mck = (e.type == 'monthly')?' selected="true" ' : '' ;
                        var yck = (e.type == 'yearly')?' selected="true" ' : '' ;
                        var o = $('<select class="qb-count-type"><option value="daily" '+dck+' >'+_('GROUP_BY_DAY')+'</option><option value="monthly"  '+mck+'>'+_('GROUP_BY_MONTHLY')+'</option><option value="yearly"  '+yck+'>'+_('GROUP_BY_YEARLY')+'</option></select>"');
                        con.append(o);
                        break;
                    case "mt_tree":
                        var o1ck = (e.type == '1')?' selected="true" ' : '' ;
                        var o2ck = (e.type == '2')?' selected="true" ' : '' ;
                        var o3ck = (e.type == '3')?' selected="true" ' : '' ;
                        var o4ck = (e.type == '4')?' selected="true" ' : '' ;
                        var o5ck = (e.type == '5')?' selected="true" ' : '' ;
                        var o6ck = (e.type == '6')?' selected="true" ' : '' ;
                        var o = $('<select class="qb-count-type"><option value="1" '+o1ck+'>'+_('1ST_LEVEL')+'</option><option value="2" '+o2ck+'>'+_('2ND_LEVEL')+'</option><option value="3" '+o3ck+'>'+_('3RD_LEVEL')+'</option><option value="4" '+o4ck+'>'+_('4TH_LEVEL')+'</option><option value="5" '+o5ck+'>'+_('5TH_LEVEL')+'</option><option value="6" '+o6ck+'>'+_('6TH_LEVEL')+'</option></select>');
                        con.append(o);
                        break;
                }
                              //add remove link
                var remove = $('<a class="qb-remove" title="'+_('REMOVE_CONDITION')+'">x</a>').click(function(e){
                    qb = groupBy.getInstance(null);
                    qb.removeCondition(e.currentTarget);
                }); 
                con.prepend(remove);
                //add the condition to the list
                $('#qb-group-list').append(con);
            }
            this.addNewCondition();
            $('#qb-count-toggle').trigger('click');
        }
        else
            this.update();
    }

    this.update = function(){
        var entities = queryBuilder.getInstance().getSelectedEntities();
        $('#qb-count-empty').remove();
        if( entities.length == 0 ){
            $('#qb-group-list').empty();
            $('#qb-group-by').prepend("<span id='qb-count-empty'>"+_('YOU_NEED_TO_SELECT_AN_ENTITY_BEFORE_COUNTING')+"</span>");
            return;
        }
        else if($('#qb-group-list > li').length == 0 ){
            this.addNewCondition();
            return;
        }
        //remove elements which are not in search conditions
        for(var e in entities){
            var r = $('#qb-group-list > li > a');
        }

        if($('#qb-group-list li.qb-new').length > 0){
            $('#qb-group-list li.qb-new').remove();
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
        $('#qb-group-list').empty();
        this.addNewCondition();
    }

    this.addNewCondition = function(){
        var con = $('<li class="qb-condition qb-new" data-status="new"></li>');
        var a   = $('<a class="qb-entity">'+_('SELECT_ENTITY')+'</a>');
        con.append(a);
        var options = openevsysDomain.getInstance().getEntities(queryBuilder.getInstance().getSelectedEntities());
        a.menupop({'options': options });
        a.bind('onchange', function(e){  
            qb = groupBy.getInstance();
            qb.addFieldSelect(e);
        });
        //add remove link
        var remove = $('<a class="qb-remove" title="'+_('REMOVE_CONDITION')+'">x</a>').click(function(e){
            qb = groupBy.getInstance();
            qb.removeCondition(e.currentTarget);
        }); 
        con.prepend(remove);
        $('#qb-group-list').append(con);
    }


    this.addFieldSelect = function(e){
        //if there is a previous condition operater to it
        var a = $(e.currentTarget);
        a.parent().removeClass('qb-new');
        //disable entity select
        a.unbind('click');
        a.addClass('qb-selected');
        var sa = $('<a class="qb-field">'+_('SELECT_FIELD')+'</a>');
        a.after(sa);
        sa.menupop({'options': openevsysDomain.getInstance().getEntityFields(a.attr('data-value')), 'mode':'multix'});
        sa.bind('onchange', function(e){  
            qb = groupBy.getInstance(null);
            qb.addField(e);
        });
        a.after('<span>.</span>');
    }

    this.addField = function(e){
        var a = $(e.currentTarget);
        a.unbind('click');
        a.addClass('qb-selected');
        this.addController(a);
        //remove the new attribute
        a.parent().removeAttr('data-status');
        this.addNewCondition();
    }

    this.getGroupBy = function(){
        //create condition array
        var g = new Array();
        var arr = $('#qb-group-list').find('.qb-condition');
        $.each(arr ,function(){
            if($(this).attr('data-status')=='new')return true;
            var c = new Object();
            c.entity = $(this).find('.qb-entity:first').attr('data-value');
            c.field = $(this).find('.qb-field:first').attr('data-value');
            c.type = $(this).find('.qb-count-type:first').val();
            g.push(c);
        });
        return g;
    }

    this.addController = function(e){
        var entity = e.parent().find('.qb-entity:first');
        var entity_name = entity.attr('data-value');
        var field_name = e.attr('data-value');
        var od = openevsysDomain.getInstance();
        var field_type = od.getFieldType(field_name, entity_name);
        switch(field_type){
            case "date": 
                var o = $('<select class="qb-count-type"><option value="daily">'+_('GROUP_BY_DAY')+'</option><option value="monthly">'+_('GROUP_BY_MONTHLY')+'</option><option value="yearly">'+_('GROUP_BY_YEARLY')+'</option></select>"');
                e.after(o);
                break;
            case "mt_tree":
                var o = $('<select class="qb-count-type"><option value="1">'+_('1ST_LEVEL')+'</option><option value="2">'+_('2ND_LEVEL')+'</option><option value="3">'+_('3RD_LEVEL')+'</option><option value="4">'+_('4TH_LEVEL')+'</option><option value="5">'+_('5TH_LEVEL')+'</option><option value="6">'+_('6TH_LEVEL')+'</option></select>');
                e.after(o);
                break;
        }
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
            var con = $('<li class="qb-condition"></li>');
            con.append('<a class="qb-entity qb-selected" data-value="'+e.entity+'">'+od.getEntityLabel(e.entity)+'</a>');
            con.append('<span>	&#46;</span>');
            con.append('<a class="qb-field qb-selected" data-value="'+e.field+'">'+od.getFieldLabel(e.field , e.entity)+'</a>');
            var field_type = od.getFieldType(e.field, e.entity);
            var op = od.getOperator(field_type);
            for(var i in op){
                if(op[i].value == e.operator){
                    var o = $('<a class="qb-operator" data-value="'+op[i].value+'">'+op[i].label+'</a>');
                    break;
                }
            }
            con.append(o);
            o.menupop({'options': op});
            /* Add controller */
            switch(field_type){
                case "date": 
                    var o = $('<input type="text" class="qb-con" value="'+e.value+'" />').click(function(){
                        registerDate = $(this);
                    });
                    o.attr('readonly',true);
                    con.append(o);
                    queryBuilder.createDate(o,null);
                    break;
                case "mt_select":
                    var o = $('<select class="qb-con"  class="qb-con"><option value="" selected="selected"></option></select>');
                    con.append(o);
                    o.qb_mt_select({mt: od.getListCode(e.field, e.entity), 'selected' : e.value });
                    break;
                case "mt_tree":
                    var o = $('<a class="qb-mt-tree"></a>');
                    o.load('index.php?mod=home&act=get_mt_term&stream=text&huricode='+e.value);
                    con.append(o);
                    o.qb_mt_tree({mt: od.getListCode(e.field, e.entity), 'selected' : e.value });
                    break;
                case "radio":
                    var name = genName();
                    var yesck = (e.value == 'y')?" checked='true' " : "" ;
                    var nock = (e.value == 'n')?" checked='true' " : "" ;
                    var o = $("<input type='radio'  class='qb-radio-yes' name='"+name+"' value='y' "+yesck+" /><span class='qb-empty-hide' >"+_('YES')+"</span><input type='radio' name='"+name+"' value='n'  class='qb-radio-no' "+nock+" /><span  class='qb-empty-hide' >"+_('NO')+"</span>");
                    con.append(o);
                    break;
                case "checkbox":
                    if(e.value != '' && e.value != null)
                        var chked = 'checked="true"';
                    else 
                        var chked = '';
                    var o = $('<input class="qb-checkbox" type="checkbox" tabindex="27" name="deceased" '+chked+'/>');
                    con.append(o);
                    break;
                default:
                    var o = $('<input type="text" class="qb-con"  value="'+e.value+'" />');
                    con.append(o);
            }
            /* add link type */
            if( e.link != null ){
                this.addAndOperater(con, e.link);
            }

            //add remove link
            var remove = $('<a class="qb-remove" title="'+_('REMOVE_CONDITION')+'">x</a>').click(function(e){
                qb = queryBuilder.getInstance(null);
                qb.removeCondition(e.currentTarget);
            }); 
            con.prepend(remove);
            //add the condition to the list
            $('#query_builder').append(con);
        }
        this.addNewCondition();
        groupBy.getInstance().setQuery(query);

        //add select fields
        for(i in query.select){
            var fields = openevsysDomain.getInstance().getSelectFields(query.select[i].entity);
            od.setSelectField(query.select[i].entity, query.select[i].field);
        }
    }

    this.addNewCondition = function(){
        var options = openevsysDomain.getInstance().getRelatedEntities(this.getSelectedEntities());
        
        var select = $("<select id=\"\" name=\"\" class=\"entselect\" />");
        $("<option />", {value:  "", text: ""}).appendTo(select);
          
        for(var option in options){
            $("<option />", {value:  options[option].value, text: options[option].label}).appendTo(select);
           
            /*li.bind('click',this,function(e){
                if($(e.originalTarget).hasClass('qb-multi-check'))return;
                e.data.value = $(this).find('a').attr('data-value');
                e.data.element.text($(this).text());
                e.data.element.attr('data-value',e.data.value);
                var oc = jQuery.Event("onchange");
                e.data.element.trigger(oc);
            });
            ul.append(li);*/
        }
        var div = $("<div class='row-fluid show-grid'></div>");
        div.append(select);
        $('#query_builder').append(div);
        select.select2({width: 'resolve',placeholder: _('SELECT_ENTITY'),allowClear:false});
        select.on("change", function() { 
            //alert($(this).val());
            qb = queryBuilder.getInstance(null);
            qb.addFieldSelect($(this));
            groupBy.getInstance().update();
        });
        return;
        
        var con = $('<li class="qb-condition qb-new" data-status="new"></li>');
        var a   = $('<a class="qb-entity">'+_('SELECT_ENTITY')+'</a>');
        con.append(a);
        var options = openevsysDomain.getInstance().getRelatedEntities(this.getSelectedEntities());
        a.menupop({'options': options });
        a.bind('onchange', function(e){  
            qb = queryBuilder.getInstance(null);
            qb.addFieldSelect(e);
            groupBy.getInstance().update();
        });
        //add remove link
        var remove = $('<a class="qb-remove" title="'+_('REMOVE_CONDITION')+'">x</a>').click(function(e){
            qb = queryBuilder.getInstance(null);
            qb.removeCondition(e.currentTarget);
        }); 
        con.prepend(remove);
        $('#query_builder').append(con);
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
        groupBy.getInstance().update();
    }

    this.addFieldSelect = function(entselect){
        /*var a = $(e.currentTarget);
        var options = openevsysDomain.getInstance().getEntityFields(a.attr('data-value'));*/
        //var a = $(e.currentTarget);
        var options = openevsysDomain.getInstance().getEntityFields(entselect.val());
       
        var select = $("<select id=\"\" name=\""+entselect.val()+"\" class=\"fieldselect\" />");
        $("<option />", {value:  "", text: ""}).appendTo(select);
        
        for(var option in options){
            $("<option />", {value:  options[option].value, text: options[option].label}).appendTo(select);
           
            /*li.bind('click',this,function(e){
                if($(e.originalTarget).hasClass('qb-multi-check'))return;
                e.data.value = $(this).find('a').attr('data-value');
                e.data.element.text($(this).text());
                e.data.element.attr('data-value',e.data.value);
                var oc = jQuery.Event("onchange");
                e.data.element.trigger(oc);
            });
            ul.append(li);*/
        }
        var div = $(entselect).closest("div");
        div.append(select);
        
        $(".entselect").select2("disable");
        select.select2({width: 'resolve',placeholder: _('SELECT_FIELD')});
        select.on("change", function() { 
            //alert($(this).val());
            qb = queryBuilder.getInstance(null);
            qb.addField($(this));
        });
        this.addAndOperater(div);
        
        return;
        
        //if there is a previous condition operater to it
        var a = $(e.currentTarget);
        a.parent().removeClass('qb-new');
        //disable entity select
        a.unbind('click');
        a.addClass('qb-selected');
        this.addAndOperater(a.parent().prev());
        var sa = $('<a class="qb-field">'+_('SELECT_FIELD')+'</a>');
        a.after(sa);
        sa.menupop({'options': openevsysDomain.getInstance().getEntityFields(a.attr('data-value')), 'mode':'multix'});
        sa.bind('onchange', function(e){  
            qb = queryBuilder.getInstance(null);
            qb.addField(e);
        });
        a.after('<span>.</span>');
		var hlpObj = new $.Helptextviewer('field_select');	
    }

    this.addField = function(fieldselect){
        $(".fieldselect").select2("disable");
        this.addController(fieldselect);
        this.addNewCondition();
        return;
        
        var a = $(e.currentTarget);
        a.unbind('click');
        a.addClass('qb-selected');
        this.addController(a);
        //remove the new attribute
        a.parent().removeAttr('data-status');
        this.addNewCondition();
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
            $("<option />", {value:  options[option].value, text: options[option].label}).appendTo(select);
        }
       
        div.append(select);
        select.select2({width: 'resolve'});
        if(field_type == 'date'){
            select.on("change", function() { 
                var ov = $(this).val();
                if( ov == 'between' || ov == 'not_between'){
                    var o = $('<input type="text" value="" class="daterangepicker" />');
                    div.append(o);
                    o.daterangepicker();
                    //queryBuilder.createDate($(e.currentTarget).parent().find('.qb-con:first'), "range");
                }
                else{
                    var o = $('<input type="text" value="" class="datepicker" />');
                    div.append(o);
                    o.datepicker({format:'yyyy-mm-dd' });
                    /*queryBuilder.createDate($(e.currentTarget).parent().find('.qb-con:first'), null);
                    var val = $(e.currentTarget).parent().find('.qb-con:first').attr('value');
                    $(e.currentTarget).parent().find('.qb-con:first').attr('value',val.substring(0,10));*/
                }
            });
        }
        switch(field_type){
            case "date": 
                var o = $('<input type="text" value="" class="datepicker qb-con" />');
                div.append(o);
                o.datepicker({format:'yyyy-mm-dd' });
               
                /*var o = $('<input type="text" value="" class="qb-con" />').click(function(){
                    registerDate = $(this);
                });
                o.attr('readonly',true);
                div.append(o);
                queryBuilder.createDate(o,null);*/
                break;
            case "mt_select":
                var mt_select = $("<select id=\"\" name=\"\" class=\"qb-con\" />");
                //$("<option />", {value:  "", text: ""}).appendTo(select);
                mt_select.qb_mt_select({mt: od.getListCode(field_name, entity_name) });
                div.append(mt_select);
                mt_select.select2({width: 'resolve'});
               
                /*var o = $('<select class="qb-con"  class="qb-con"><option value="" selected="selected"></option></select>');
                e.after(o);
                o.qb_mt_select({mt: od.getListCode(field_name, entity_name) });*/
                break;
            case "mt_tree":
                var mt_tree = $('<select id=\"\" name=\"\" class=\"mt-tree\" />');
                mt_tree.qb_mt_tree({mt: od.getListCode(field_name, entity_name) });
                div.append(mt_tree);
                mt_tree.select2({width: 'resolve',formatResult: format_mt_tree,formatSelection: format_mt_tree});
                
                /*var o = $('<a class="qb-mt-tree">'+_('SELECT_TREE_OPTION')+'</a>');
                e.after(o);
                o.qb_mt_tree({mt: od.getListCode(field_name, entity_name) });*/
                break;
            case "radio":
                var name = genName();
                var o = $("<label class='radio inline'><input type='radio' class='qb-radio-yes' name='"+name+"' value='y'/>"+_('YES')+"</label><label class='radio inline'><input class='qb-radio-no' type='radio' name='"+name+"' value='n'  />"+_('NO')+"</label>");
                div.append(o);
                break;
            case "checkbox":
                var o = $('<label class="checkbox inline"><input class="qb-checkbox" type="checkbox" tabindex="27" name="deceased"/></label>');
                div.append(o);
                break;
            default:
                var o = $('<input type="text" value="" class="qb-con" />');
                div.append(o);
        }
        return;
  
        
        
        var entity = e.parent().find('.qb-entity:first');
        var entity_name = entity.attr('data-value');
        var field_name = e.attr('data-value');
        var od = openevsysDomain.getInstance();
        var field_type = od.getFieldType(field_name, entity_name);
        switch(field_type){
            case "date": 
                var o = $('<input type="text" value="" class="qb-con" />').click(function(){
                    registerDate = $(this);
                });
                o.attr('readonly',true);
                e.after(o);
                queryBuilder.createDate(o,null);
                break;
            case "mt_select":
                var o = $('<select class="qb-con"  class="qb-con"><option value="" selected="selected"></option></select>');
                e.after(o);
                o.qb_mt_select({mt: od.getListCode(field_name, entity_name) });
                break;
            case "mt_tree":
                var o = $('<a class="qb-mt-tree">'+_('SELECT_TREE_OPTION')+'</a>');
                e.after(o);
                o.qb_mt_tree({mt: od.getListCode(field_name, entity_name) });
                break;
            case "radio":
                var name = genName();
                var o = $("<input type='radio'  class='qb-radio-yes' name='"+name+"' value='y'/><span  class='qb-empty-hide' >"+_('YES')+"</span><input type='radio' name='"+name+"' value='n'  class='qb-radio-no'/><span  class='qb-empty-hide' >"+_('NO')+"</span>");
                e.after(o);
                break;
            case "checkbox":
                var o = $('<input class="qb-checkbox" type="checkbox" tabindex="27" name="deceased"/>');
                e.after(o);
                break;
            default:
                var o = $('<input type="text" value="" class="qb-con" />');
                e.after(o);
        }

        //add operaters according to field types
        var op = openevsysDomain.getInstance().getOperator(field_type);
        var o = $('<a class="qb-operator" data-value="'+op[0].value+'">'+op[0].label+'</a>');
        e.after(o);
        o.menupop({'options': op});
        if(field_type == 'date'){
            o.bind('onchange', function(e){ 
                var ov = $(e.currentTarget).attr('data-value');
                if( ov == 'between' || ov == 'not_between'){
                    queryBuilder.createDate($(e.currentTarget).parent().find('.qb-con:first'), "range");
                }
                else{
                    queryBuilder.createDate($(e.currentTarget).parent().find('.qb-con:first'), null);
                    var val = $(e.currentTarget).parent().find('.qb-con:first').attr('value');
                    $(e.currentTarget).parent().find('.qb-con:first').attr('value',val.substring(0,10));
                }
            });
        }
        o.bind('onchange', function(e){ 
            var ov = $(e.currentTarget).attr('data-value');
            if(ov == 'empty')
                $(e.currentTarget).parent().addClass('qb-empty-field');
            else
                $(e.currentTarget).parent().removeClass('qb-empty-field');

        });
    }

    this.addAndOperater = function(div , value){
        var options = [
                    {'value':'and','label':_('AND')},
                    {'value':'or' ,'label':_('OR')}
                 ];
        var select = $("<select id=\"\" name=\"\" class=\"operselect\" />");
        for(var option in options){
            $("<option />", {value:  options[option].value, text: options[option].label}).appendTo(select);
           
        }
        //var div = $(sel).closest("div");
        div.append(select);
        //$('#query_builder').append(div);
        select.select2({width: 'resolve'});
        return;
        
        
        if(condition == null || condition.children('.qb-link').length != 0)return;
        var op = [
                    {'value':'and','label':_('AND')},
                    {'value':'or' ,'label':_('OR')}
                 ];
        if(value != null){
            for(var i in op){
                if(value == op[i].value){
                    var a = $('<a class="qb-link" data-value="'+op[i].value+'">'+op[i].label+'</a>');
                    break;
                }
            }
        }
        else
            var a = $('<a class="qb-link" data-value="'+op[0].value+'">'+op[0].label+'</a>');
        condition.append(a);
        a.menupop({'options': op });
    }

    this.clearConditions = function(){
        $('#query_builder').empty();
        this.addNewCondition();
    }

    this.getSelectedEntities = function(){
        var arr = $('#query_builder').find('.entselect');
        var entities = new Array();
        $.each(arr ,function(){
            var v = $(this).val();
            //var v = $(this).attr('data-value');
            if(v != null && v != "" && $.inArray(v , entities) == -1)
            entities.push(v);
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
            c.field = $(this).find('select.fieldselectd:first').val();
            c.operator = $(this).find('select.operatorselect:first').val();
            c.value = $(this).find('.qb-con:first').attr('value');
            //handle checkboxes
            if($(this).find('.qb-checkbox').length > 0 ){
                if($(this).find('.qb-checkbox').attr('checked'))
                    c.value = 'y';
                else
                    c.value = 'n';
            }
            //handle radio buttons
            if($(this).find('.qb-radio-yes').length > 0 ){
                if($(this).find('.qb-radio-yes').attr('checked'))
                    c.value = 'y';
            }
            if($(this).find('.qb-radio-no').length > 0 ){
                if($(this).find('.qb-radio-no').attr('checked'))
                    c.value = 'n';
            }
            c.link = $(this).find('.qb-link:first').attr('data-value');
            conditions.push(c);
        });

        //display select variables
        var select = new Array();
        var entities = this.getSelectedEntities();
        
        for(var i in entities){
            var fields = openevsysDomain.getInstance().getSelectFields(entities[i]);
            for(var f in fields){
                var field = {'entity': entities[i], 'field': fields[f]}
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

    this.setQuery = function(query){
        this.query_builder.setQuery(query);
    }

    this.Search = function(){
        this.query = this.query_builder.getQuery();
        
        if(this.query.conditions < 1 ){
            $.pnotify({ pnotify_title: 'Error', pnotify_text: _('PLEASE_ADD_A_CONDITION_BEFORE_SEARCH_'), pnotify_type: 'error' });
            return;
        }
        
        $("#list123").dataTable( {
		/*"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		},*/
                "bProcessing": true,
                "sAjaxSource": "index.php?mod=analysis&act=load_grid&query="+encodeURI($.toJSON(this.query))
	} );
        var result_panel = $('.resultPanel');
        result_panel.show();
        
    	/*this.search_result = searchResults.getInstance();
    	this.search_result.setQuery(this.query);
        this.search_result.fetchResultsAndDisplay();*/
    }

    this.Count = function(){
        this.query = this.query_builder.getQuery();
        this.query.group_by = this.group_by.getGroupBy();
        if(this.query.group_by.length < 1 ){
            $.pnotify({ pnotify_title: 'Error', pnotify_text: _('PLEASE_ADD_A_CONDITION_INSIDE_THE_COUNT_BOX_TO_COUNT_'), pnotify_type: 'error' });
            return;
        }
    	this.search_result = searchResults.getInstance();
    	this.search_result.setQuery(this.query);
        this.search_result.fetchResultsAndDisplay();
    }

    /* This function will initialise the advance search class */
    this.init = function(){
        $(document).ready(function(){
            var domain = openevsysDomain.getInstance();
            domain.fetchDomainData(advSearch.initObjects);
        });
        this.query_builder = queryBuilder.getInstance();
        this.group_by = groupBy.getInstance();
        this.group_by.init();
        this.toggleQueryPanel();
        this.toggleCountPanel();
        $('#qb-clear-but').click(function(){
            queryBuilder.getInstance().clearConditions();
            groupBy.getInstance().clearConditions();
            $('.resultPanel').hide();
        });
        $('#qb-search-but').click(function(){
            as.Search();
        });
    }

    advSearch.initObjects = function(data){
        var domain = openevsysDomain.getInstance();
        domain.data = data;
        queryBuilder.getInstance().init();
        if(query != ''){
            var sr = searchResults.getInstance();
            var q = $.secureEvalJSON(query);
            sr.setQuery(q);
            sr.fetchResultsAndDisplay();
            queryBuilder.getInstance().setQuery(q);
        }
		var hlpObj = new $.Helptextviewer('welcome');	
    }

    this.toggleQueryPanel = function(){
        $('#qb-query-toggle').toggle(
            function(){
                $('#qb-query-toggle').addClass('active');
                $('#qb-panel').addClass('qb-collapse');
                $('#qb-panel > form').hide()
            },
            function(){
                $('#qb-query-toggle').removeClass('active');
                $('#qb-panel').removeClass('qb-collapse');
                $('#qb-panel > form').show()
            });
    }

    this.toggleCountPanel = function(){
        $('#qb-count-toggle').toggle(
            function(){
                $('#qb-count-toggle').removeClass('active');
                $('#qb-group-by').show();
                $('#qb-search-but').unbind('click');
                $('#qb-search-but').val(_('COUNT')).click(function(){
                    as.Count();
                });
            },
            function(){
                $('#qb-count-toggle').addClass('active');
                $('#qb-group-by').hide();
                $('#qb-search-but').unbind('click');
                $('#qb-search-but').val(_('SEARCH')).click(function(){
                    as.Search();
                });
            }
        );

    }
}
/*}}}*/

var registerDate = null;
var namenumber = 0;
function genName(){
        return 'name' + namenumber++;
}


//Jquery plugin to fetch microthusari values
(function($){
    $.fn.qb_mt_select = function(options) {
        var defaults = {
            url: "index.php?mod=home&act=mt_select&stream=text",
            selected : null,
        };
        var options = $.extend(defaults, options);

        return this.each(function()
        {
            $(this).load( options.url+'&list_code='+options.mt+'&selected='+options.selected );
        });


        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);


function treePop(element){
    this.element = element;
    this.menu = null;
    this.value = null;

    this.createPopup= function(options){
        var ul = $('<ul></ul>').click(this.mttreeClickEvent);
        var div = $("<div class='pop'></div>").click(function(){return false;});
        div.append(ul);
        ul.wrap('<div class="pop_menu qb-tree-pop"></div>');
        ul.after('<input type="hidden" class="qb-con" value="'+options.selected+'" />');
        this.element.before(div);
        this.menu = div;

        ul.treeview({
            collapsed: true,
            animated: "none",
            control:"#sidetreecontrol",
            persist: "location",
            url : 'index.php?mod=home&act=mt_tree&stream=text&list_code=' + options.mt
        });
    }

    this.mttreeClickEvent = function(e)
    {
        if (e.target) targ = e.target;
        else if (e.srcElement) targ = e.srcElement;
        var el = $(targ);
        if(el.attr('huricode') == undefined)return true;
        //var obj = $('#'+data.field_id+'_ul') 
        //obj.triggerHandler('selectTreeItem',data);
        $(this).parent().find('.qb-con:first').attr('value',el.attr('huricode'));
        $(this).parent().parent().parent().find('.qb-mt-tree:first').text(el.text());
        $('.pop').removeClass('active');
    }

    this.showPopup = function(){
        this.menu.addClass("active");
        return false;
    }

    this.hidePopup = function(){
//        this.menu.removeClass("active");
        return false;
    }

    $(document).click(function(){ 
        $('.pop').removeClass('active');
    });
}

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
            $(this).load( options.url+'&list_code='+options.mt+'&selected='+options.selected );
        });
        
        /*return this.each(function()
        {
            var obj = $(this);
            var mp = new treePop(obj);
            mp.createPopup(options);
            obj.click(function(){return mp.showPopup();});
        });*/

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);
