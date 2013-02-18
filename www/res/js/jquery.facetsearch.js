/*
 * jquery.facetsearch.js
 *
 * displays faceted browse results by querying a specified index
 * can read config locally or can be passed in as variable when executed
 * or a config variable can point to a remote config
 * config options include specifying SOLR or ElasticSearch index
 * 
 * created by Mark MacGillivray - mark@cottagelabs.com
 *
 * http://facetsearch.cottagelabs.com
 *
*/

// first define the bind with delay function from (saves loading it separately) 
// https://github.com/bgrins/bindWithDelay/blob/master/bindWithDelay.js
(function($) {
    $.fn.bindWithDelay = function( type, data, fn, timeout, throttle ) {
        var wait = null;
        var that = this;

        if ( $.isFunction( data ) ) {
            throttle = timeout;
            timeout = fn;
            fn = data;
            data = undefined;
        }

        function cb() {
            var e = $.extend(true, { }, arguments[0]);
            var throttler = function() {
                wait = null;
                fn.apply(that, [e]);
            };

            if (!throttle) {
                clearTimeout(wait);
            }
            if (!throttle || !wait) {
                wait = setTimeout(throttler, timeout);
            }
        }

        return this.bind(type, data, cb);
    }
})(jQuery);


// now the facetsearch function
(function($){
    $.fn.facetsearch = function(options) {

        // some big default values
        var resdisplay = [
        [
        {
            "field": "author.name"
        },
        {
            "pre": "(",
            "field": "year",
            "post": ")"
        }
        ],
        [
        {
            "pre": "<strong>",
            "field": "title",
            "post": "</strong>"
        }
        ],
        [
        {
            "field": "howpublished"
        },
        {
            "pre": "in <em>",
            "field": "journal.name",
            "post": "</em>,"
        },
        {
            "pre": "<em>",
            "field": "booktitle",
            "post": "</em>,"
        },
        {
            "pre": "vol. ",
            "field": "volume",
            "post": ","
        },
        {
            "field": "pages"
        },
        {
            "field": "publisher"
        }
        ],
        [
        {
            "field": "link.url"
        }
        ]
        ]


        // specify the defaults
        var defaults = {
            "config_file":false,
            "facets":[],
            "result_display": resdisplay,
            "ignore_fields":["_id","_rev"],
            "description":"",
            "search_url":"",
            "search_index":"httpsearch",
            "default_url_params":{},
            "freetext_submit_delay":"700",
            "query_parameter":"q",
            "q":"*:*",
            "predefined_filters":{},
            "paging":{}
        };

        // and add in any overrides from the call
        var options = $.extend(defaults, options);
        !options.paging.size ? options.paging.size = 10 : ""
        !options.paging.from ? options.paging.from = 0 : ""

		
        var selected_terms = new Object();
		
        var fmap = null;
        var fmarkerclusterer = null;
		
        // ===============================================
        // functions to do with filters
        // ===============================================
        
        // show the filter values
        var showfiltervals = function(event) {
            event.preventDefault();
        /*if ( $(this).hasClass('facetsearch_open') ) {
                $(this).children('i').replaceWith('<i class="icon-plus"></i>')
                $(this).removeClass('facetsearch_open');
                $('#facetsearch_' + $(this).attr('rel') ).children().hide();
            } else {
                $(this).children('i').replaceWith('<i class="icon-minus"></i>')
                $(this).addClass('facetsearch_open');
                $('#facetsearch_' + $(this).attr('rel') ).children().show();      
            }*/
        }

        // function to perform for sorting of filters
        var sortfilters = function(event) {
            event.preventDefault()
            var sortwhat = $(this).attr('href')
            var which = 0
            for (item in options.facets) {
                if ('field' in options.facets[item]) {
                    if ( options.facets[item]['field'] == sortwhat) {
                        which = item
                    }
                }
            }
            if ( $(this).hasClass('facetsearch_count') ) {
                options.facets[which]['order'] = 'count'
            } else if ( $(this).hasClass('facetsearch_term') ) {
                options.facets[which]['order'] = 'term'
            } else if ( $(this).hasClass('facetsearch_rcount') ) {
                options.facets[which]['order'] = 'reverse_count'
            } else if ( $(this).hasClass('facetsearch_rterm') ) {
                options.facets[which]['order'] = 'reverse_term'
            }
            dosearch()
        /*if ( !$(this).parent().parent().siblings('.facetsearch_filtershow').hasClass('facetsearch_open') ) {
                $(this).parent().parent().siblings('.facetsearch_filtershow').trigger('click')
            }*/
        }

        // adjust how many results are shown
        var morefacetvals = function(event) {
            event.preventDefault()
            var morewhat = options.facets[ $(this).attr('rel') ]
            if ('size' in morewhat ) {
                var currentval = morewhat['size']
            } else {
                var currentval = 10
            }
            var newmore = prompt('Currently showing ' + currentval + 
                '. How many would you like instead?')
            if (newmore) {
                options.facets[ $(this).attr('rel') ]['size'] = parseInt(newmore)
                $(this).html('show up to ' + newmore )
                dosearch()
            /*if ( !$(this).parent().parent().siblings('.facetsearch_filtershow').hasClass('facetsearch_open') ) {
                    $(this).parent().parent().siblings('.facetsearch_filtershow').trigger('click')
                }*/
            }
        }

        /* // insert a facet range once selected
        var dofacetrange = function(event) {
            event.preventDefault()
            var rel = $('#facetsearch_rangerel').html()
            var range = $('#facetsearch_rangechoices').html()
            var newobj = '<a class="facetsearch_filterselected facetsearch_facetrange facetsearch_clear ' + 
                'btn btn-info" rel="' + rel + 
                '" alt="remove" title="remove"' +
                ' href="' + $(this).attr("href") + '">' +
                range + ' <i class="icon-remove"></i></a>';
            $('#facetsearch_selectedfilters').append(newobj);
            $('.facetsearch_filterselected').unbind('click',clearfilter);
            $('.facetsearch_filterselected').bind('click',clearfilter);
            $('#facetsearch_rangemodal').modal('hide')
            $('#facetsearch_rangemodal').remove()
            options.paging.from = 0
            dosearch();
        }
        // remove the range modal from page altogether on close (rebuilt for each filter)
        var removerange = function(event) {
            event.preventDefault()
            $('#facetsearch_rangemodal').modal('hide')
            $('#facetsearch_rangemodal').remove()
        }
        // build a facet range selector
        var facetrange = function(event) {
            event.preventDefault()
            var modal = '<div class="modal" id="facetsearch_rangemodal"> \
                <div class="modal-header"> \
                <a class="facetsearch_removerange close">×</a> \
                <h3>Set a filter range</h3> \
                </div> \
                <div class="modal-body"> \
                <div style=" margin:20px;" id="facetsearch_slider"></div> \
                <h3 id="facetsearch_rangechoices" style="text-align:center; margin:10px;"> \
                <span class="facetsearch_lowrangeval">...</span> \
                <small>to</small> \
                <span class="facetsearch_highrangeval">...</span></h3> \
                <p>(NOTE: ranges must be selected based on the current content of \
                the filter. If you require more options than are currently available, \
                cancel and return to the filter options; select sort by term, and set \
                the number of values you require)</p> \
                </div> \
                <div class="modal-footer"> \
                <a id="facetsearch_dofacetrange" href="#" class="btn btn-primary">Apply</a> \
                <a class="facetsearch_removerange btn close">Cancel</a> \
                </div> \
                </div>';
            $('#facetsearch').append(modal)
            $('#facetsearch_rangemodal').append('<div id="facetsearch_rangerel" style="display:none;">' + $(this).attr('rel') + '</div>')
            $('#facetsearch_rangemodal').modal('show')
            $('#facetsearch_dofacetrange').bind('click',dofacetrange)
            $('.facetsearch_removerange').bind('click',removerange)
            var values = []
            var valsobj = $( '#facetsearch_' + $(this).attr('href').replace(/\./gi,'_') )
            valsobj.children('li').children('a').each(function() {
                values.push( $(this).attr('href') )
            })
            $( "#facetsearch_slider" ).slider({
            values = values.sort()
	            range: true,
	            min: 0,
	            max: values.length-1,
	            values: [0,values.length-1],
	            slide: function( event, ui ) {
		            $('#facetsearch_rangechoices .facetsearch_lowrangeval').html( values[ ui.values[0] ] )
		            $('#facetsearch_rangechoices .facetsearch_highrangeval').html( values[ ui.values[1] ] )
	            }
            })
            $('#facetsearch_rangechoices .facetsearch_lowrangeval').html( values[0] )
            $('#facetsearch_rangechoices .facetsearch_highrangeval').html( values[ values.length-1] )
        }
*/

        // pass a list of filters to be displayed
        var buildfilters = function() {
            var filters = options.facets;
            var thefilters = ' \
			 <div class="box"><form method="GET" action="#search" class="form-inline"> \
                   <div id="facetsearch_searchbar" class="input-prepend input-append"> \
                   <span class="add-on"><i class="icon-search"></i></span> \
                   <input class="input-block-level" id="facetsearch_freetext" name="q" value="" placeholder="search term" autofocus /> \
                   <div  class="btn-group"> \
                    <a  class="btn dropdown-toggle" data-toggle="dropdown" href="#"> \
                    <i class="icon-cog"></i> <span class="caret"></span></a> \
                    <ul class="dropdown-menu"> \
                    <li><a id="facetsearch_clearall" href="#">clear all</a></li> \
                    <li class="divider"></li> \
                    <li><a id="facetsearch_howmany" href="#">results per page ({{HOW_MANY}})</a></li> \
                    </ul></div>></div> \
				   </form></div> \
				    ';
            thefilters = thefilters.replace(/{{HOW_MANY}}/gi,options.paging.size)
            
            for ( var idx in filters ) {
                /*var _filterTmpl = ' \
                    <div class="box btn-group"> \
                    <h4 class="box-header round-top">{{FILTER_NAME}}<a class="box-btn" title="toggle"><i class="icon-minus"></i></a></h4> \
							<div class="box-container-toggle"> \
							<a style="text-align:left; min-width:70%;" class="facetsearch_filtershow btn facetsearch_open" \
                      rel="{{FILTER_NAME}}" href=""> \
                      <i class="icon-minus"></i> \
                      {{FILTER_DISPLAY}}</a> \
                      <a class="btn dropdown-toggle" data-toggle="dropdown" \
                      href="#"><span class="caret"></span></a> \
                      <ul class="dropdown-menu"> \
                        <li><a class="facetsearch_clearfacet" rel="{{FILTER_EXACT}}">Clear this selections</a></li> \
                        <!--<li><a class="facetsearch_sort facetsearch_count" href="{{FILTER_EXACT}}">sort by count</a></li> \
                        <li><a class="facetsearch_sort facetsearch_term" href="{{FILTER_EXACT}}">sort by term</a></li> \
                        <li><a class="facetsearch_sort facetsearch_rcount" href="{{FILTER_EXACT}}">sort reverse count</a></li> \
                        <li><a class="facetsearch_sort facetsearch_rterm" href="{{FILTER_EXACT}}">sort reverse term</a></li> \
                        <li class="divider"></li> \
                        <li><a class="facetsearch_facetrange" rel="{{FACET_IDX}}" href="{{FILTER_EXACT}}">select a filter range</a></li> \
                        <li class="divider"></li> \
                        <li><a class="facetsearch_morefacetvals" rel="{{FACET_IDX}}" href="{{FILTER_EXACT}}">show up to {{FILTER_HOWMANY}}</a></li> \
                        --></ul></div> \
                  <ul id="facetsearch_{{FILTER_NAME}}" \
                    class="facetsearch_filters"></ul> \
                    ';*/
                var _filterTmpl = ' \
                    <div class="box"> \
                    <h4 class="box-header round-top">{{FILTER_NAME}}<a class="box-btn" title="toggle"><i class="icon-minus"></i></a> \
					<div class="dropdown" style="display:inline"><a class="box-btn dropdown-toggle" data-toggle="dropdown" \
                      href="#"><span class="caret" style="margin-top:6px;margin-rigth:3px;"></span></a> \
                      <ul class="dropdown-menu"> \
                        <li><a class="facetsearch_clearfacet" rel="{{FILTER_EXACT}}">Clear this selections</a></li> \
                        </ul></div></h4> \
							<div class="box-container-toggle"><div class="box-content"> \
							<ul id="facetsearch_{{FILTER_NAME}}" \
                    class="facetsearch_filters"></ul></div></div></div> \
                    ';
                thefilters += _filterTmpl.replace(/{{FILTER_NAME}}/g, filters[idx]['field'].replace(/\./gi,'_')).replace(/{{FILTER_EXACT}}/g, filters[idx]['field']);
                if ('size' in filters[idx] ) {
                    thefilters = thefilters.replace(/{{FILTER_HOWMANY}}/gi, filters[idx]['size'])
                } else {
                    thefilters = thefilters.replace(/{{FILTER_HOWMANY}}/gi, 10)
                }
                thefilters = thefilters.replace(/{{FACET_IDX}}/gi,idx)
                if ('display' in filters[idx]) {
                    thefilters = thefilters.replace(/{{FILTER_DISPLAY}}/g, filters[idx]['display'])
                } else {
                    thefilters = thefilters.replace(/{{FILTER_DISPLAY}}/g, filters[idx]['field'])
                }
				
				
            }
            $('#facetsearch_filters').append(thefilters)
            $('.facetsearch_morefacetvals').bind('click',morefacetvals)
            //$('.facetsearch_facetrange').bind('click',facetrange)
            $('.facetsearch_sort').bind('click',sortfilters)
            //$('.facetsearch_filtershow').bind('click',showfiltervals)
            $('.facetsearch_clearfacet').bind('click',clearfacetfilters)
            $('#facetsearch_clearall').bind('click',clearallfilters)
			
			
        }

        // set the available filter values based on results
        var putvalsinfilters = function(data) {
            // for each filter setup, find the results for it and append them to the relevant filter
            for ( var each in options.facets ) {
			
                $('#facetsearch_' + options.facets[each]['field'].replace(/\./gi,'_')).children().remove();
                var records = data["facets"][ options.facets[each]['field'] ].terms;
                var lev = 0;
                var field_type = data["facets"][ options.facets[each]['field'] ].field_type;
                var tree = "";
                var i = 0;
                for ( var item in records ) {
                    if(field_type == "mt_tree"){
                        var append = '<li><a class="facetsearch_filterchoice' +
                        '" data-entity="'+data["facets"][ options.facets[each]['field'] ].entity+'" data-field="' + options.facets[each]['field'] + '" data-value="' + records[item].term + '" href="#">' + records[item].label;
                        append += '</a>';
                        if(records[item].level < lev){
                            var append2 = "";
                            while (lev > records[item].level) {
                                append2 = "</li></ul>"+append2;
                                lev--;
                            }
                            append = append2+append;
                        }else if(records[item].level > lev){                            
                            append = "<ul>"+append;
                            lev = records[item].level
                        }else{
                            
                            if(i!=0){
                               append = "</li>"+append
                            }
                        }
                    }else{
                        var append = '<li><a class="facetsearch_filterchoice' +
                        '" data-entity="'+data["facets"][ options.facets[each]['field'] ].entity+'" data-field="' + options.facets[each]['field'] + '" data-value="' + records[item].term + '" href="#" >' + records[item].label;
                        append += '</a></li>';
                    }
                    tree += append;
                    i++;
                }
                if(tree != ""){
                    
                    while (lev > 0) {
                        tree = tree+"</li></ul>";
                        lev--;
                    }
                    tree += "</li>";
                }
                $('#facetsearch_' + options.facets[each]['field'].replace(/\./gi,'_')).append(tree);
                    
                
            /*if ( !$('.facetsearch_filtershow[rel="' + options.facets[each]['field'].replace(/\./gi,'_') + '"]').hasClass('facetsearch_open') ) {
                    $('#facetsearch_' + options.facets[each]['field'].replace(/\./gi,'_') ).children().hide();
                }*/
            }
            $('.facetsearch_filterchoice').bind('click',clickfilterchoice);
        }

        // ===============================================
        // functions to do with filter options
        // ===============================================

        // show the advanced functions
        var showadvanced = function(event) {
            event.preventDefault();
            if ( $(this).hasClass('facetsearch_open') ) {
                $(this).removeClass('facetsearch_open').siblings().hide();
            } else {
                $(this).addClass('facetsearch_open').siblings().show();
            }
        }

        // add a filter when a new one is provided
        var addfilters = function() {
            options.facets.push({
                'field':$(this).val()
            });
            // remove any current filters
            $('#facetsearch_filters').html("");
            buildfilters();
            dosearch();
        }

        // set the user admin filters
        var advanced = function() {
            var advanceddiv = '<div id="facetsearch_advanced">' + 
            '<a class="facetsearch_advancedshow" href="">ADVANCED ...</a>' +
            '<p>add filter:<br /><select id="facetsearch_addfilters"></select></p></div>';
            $('#facetsearch_filters').after(advanceddiv);
            $('.facetsearch_advancedshow').bind('click',showadvanced).siblings().hide();
        }
        
        // populate the advanced options
        var populateadvanced = function(data) {
            // iterate through source keys
            var options = "";
			
            if( typeof data["records"] != 'undefined' ){
                for (var item in data["records"][0]) {
                    options += '<option>' + item + '</option>';
                }
            }
            $('#facetsearch_addfilters').html("");
            $('#facetsearch_addfilters').append(options);
            $('#facetsearch_addfilters').change(addfilters);
        
        }
        
        // ===============================================
        // functions to do with building results
        // ===============================================

        // read the result object and return useful vals depending on if ES or SOLR
        // returns an object that contains things like ["data"] and ["facets"]
        var parseresults = function(dataobj) {
		
            var resultobj = new Object();
            resultobj["records"] = new Array();
            resultobj["start"] = "";
            resultobj["found"] = "";
            resultobj["facets"] = new Object();
            if ( options.search_index == "elasticsearch" ) {
                for (var item in dataobj.hits.hits) {
                    resultobj["records"].push(dataobj.hits.hits[item]._source);
                    resultobj["start"] = "";
                    resultobj["found"] = dataobj.hits.total;
                }
                for (var item in dataobj.facets) {
                    var facetsobj = new Object();
                    for (var thing in dataobj.facets[item]["terms"]) {
                        facetsobj[ dataobj.facets[item]["terms"][thing]["term"] ] = dataobj.facets[item]["terms"][thing]["count"];
                    }
                    resultobj["facets"][item] = facetsobj;
                }
            } else if ( options.search_index == "solrsearch" ) {
           
                resultobj["records"] = dataobj.response.docs;
                resultobj["start"] = dataobj.response.start;
                resultobj["found"] = dataobj.response.numFound;
                if (dataobj.facet_counts) {
                    for (var item in dataobj.facet_counts.facet_fields) {
                        var facetsobj = new Object();
                        var count = 0;
                        for ( var each in dataobj.facet_counts.facet_fields[item]) {
                            if ( count % 2 == 0 ) {
                                facetsobj[ dataobj.facet_counts.facet_fields[item][each] ] = dataobj.facet_counts.facet_fields[item][count + 1];
                            }
                            count += 1;
                        }
                        resultobj["facets"][item] = facetsobj;
                    }
                }
				
            }else{
                resultobj["records"] = dataobj.response.records;
                resultobj["start"] = dataobj.response.start;
                resultobj["found"] = dataobj.response.found;
                resultobj["markers"] = dataobj.markers;
            
                resultobj["facets"] = dataobj.facets
            /*for (var item in dataobj.facets) {
                    var facetsobj = new Object();
                    for (var thing in dataobj.facets[item]["terms"]) {
                        facetsobj[ dataobj.facets[item]["terms"][thing]["term"] ] = dataobj.facets[item]["terms"][thing];// dataobj.facets[item]["terms"][thing]["label"];
                    }
                    resultobj["facets"][item] = facetsobj;
                }*/
            }
            
            return resultobj;
        }

        // decrement result set
        var decrement = function(event) {
            event.preventDefault()
            if ( $(this).html() != '..' ) {
                options.paging.from = options.paging.from - options.paging.size
                options.paging.from < 0 ? options.paging.from = 0 : ""
                dosearch();
            }
        }

        // increment result set
        var increment = function(event) {
            event.preventDefault()
            if ( $(this).html() != '..' ) {
                options.paging.from = parseInt($(this).attr('href'))
                dosearch()
            }
        }

        // write the metadata to the page
        var putmetadata = function(data) {
            var metaTmpl = ' \
              <div class="pagination"> \
                <ul> \
                  <li class="prev"><a id="facetsearch_decrement" href="{{from}}">&laquo; back</a></li> \
                  <li class="active"><a>{{from}} &ndash; {{to}} of {{total}}</a></li> \
                  <li class="next"><a id="facetsearch_increment" href="{{to}}">next &raquo;</a></li> \
                </ul> \
              </div> \
              ';
            $('#facetsearch_metadata').html("Not found...")
            if (data.found) {
                var from = options.paging.from + 1
                var size = options.paging.size
                !size ? size = 10 : ""
                var to = options.paging.from+size
                data.found < to ? to = data.found : ""
                var meta = metaTmpl.replace(/{{from}}/g, from);
                meta = meta.replace(/{{to}}/g, to);
                meta = meta.replace(/{{total}}/g, data.found);
                $('#facetsearch_metadata').html("").append(meta);
                $('#facetsearch_decrement').bind('click',decrement)
                from < size ? $('#facetsearch_decrement').html('..') : ""
                $('#facetsearch_increment').bind('click',increment)
                data.found <= to ? $('#facetsearch_increment').html('..') : ""
            }

        }

        // given a result record, build how it should look on the page
        var buildrecord = function(record) {
            /*var result = '<tr>';
             result +=  ' \
            <div style="float:right;" class="btn-group"> \
                <a style="margin-left:10px;" class="btn dropdown-toggle" data-toggle="dropdown" href="#"> \
                <i class="icon-cog"></i> <span class="caret"></span></a> \
                <ul class="dropdown-menu"> \
                <li><a href="">no options yet...</a></li> \
                </ul> \
               </div>';
			   
            var display = options.result_display
            var lines = ''
            for (lineitem in display) {
                line = ""
                for (object in display[lineitem]) {
                    var thekey = display[lineitem][object]['field']
                    parts = thekey.split('.')
                    // TODO: this should perhaps recurse..
                    if (parts.length == 1) {
                        var res = record
                    } else if (parts.length == 2) {
                        var res = record[parts[0]]
                    }

                    var counter = parts.length - 1
                    if (res && res.constructor.toString().indexOf("Array") == -1) {
                        var thevalue = res[parts[counter]]  // if this is a dict
                    } else {
                        var thevalue = []
                        for (var row in res) {
                            thevalue.push(res[row][parts[counter]])
                        }
                    }
                    if (thevalue && thevalue.length) {
                        display[lineitem][object]['pre'] 
                            ? line += display[lineitem][object]['pre'] : false
                        if ( typeof(thevalue) == 'object' ) {
                            for (var val in thevalue) {
                                val != 0 ? line += ', ' : false
                                line += thevalue[val]
                            }
                        } else {
                            line += thevalue
                        }
                        display[lineitem][object]['post'] 
                            ? line += display[lineitem][object]['post'] : false
                        line += ' '
                    }
                }
                if (line) {
                    lines += line.replace(/^\s/,'').replace(/\s$/,'').replace(/\,$/,'') + "<br />"
                }
            }
            lines ? result += lines : result += 'unidentified item'
            */
            var line = "<tr>"
            for(i in record){
                line+="<td>"+record[i]+"</td>"
            }
            line = line.replace(/^\s/,'').replace(/\s$/,'').replace(/\,$/,'') + "<br />"
			
            line += '</tr>'
            return line;
        }

		
        var addmapmarkers = function(data){
            fmap.gmap('clear', 'markers');
			
			
            if(typeof data.markers != 'undefined'){
			
                $.each(data.markers, function(i, marker) {
                    fmap.gmap('addMarker', { 
                        'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
                        'bounds': true 
                    }).click(function() {
                        fmap.gmap('openInfoWindow', {
                            'content': marker.content
                        }, this);
                    });
                });
                fmarkerclusterer.clearMarkers();
                fmarkerclusterer =  new MarkerClusterer(fmap.gmap('get', 'map'), fmap.gmap('get', 'markers'));

            }
        }
        // put the results on the page
        showresults = function(sdata) {
			
            // get the data and parse from the solr / es layout
            var data = parseresults(sdata);
			
            addmapmarkers(data);
			
            // change filter options
            putvalsinfilters(data);
            // put result metadata on the page
            putmetadata(data);
            // populate the advanced options
            populateadvanced(data);
            // put the filtered results on the page
            $('#facetsearch_results').html("");
            if(typeof data.records != 'undefined'){
                $.each(data.records, function(index, value) {
                     
                    $('#facetsearch_results').append( buildrecord(value) );
                 });
            }
            $('.facetsearch_more').bind('click',showmore);
			
			
        }
		
		

        // show more details of an event, and trigger the book search
        var showmore = function(event) {
            event.preventDefault();
            alert("show record view options")
        }

        // ===============================================
        // functions to do with searching
        // ===============================================

        // build the search query URL based on current params
        var solrsearchquery = function() {
            // set default URL params
            var urlparams = "";
            for (var item in options.default_url_params) {
                urlparams += item + "=" + options.default_url_params[item] + "&";
            }
            // do paging params
            var pageparams = "";
            for (var item in options.paging) {
                pageparams += item + "=" + options.paging[item] + "&";
            }
            // set facet params
            var urlfilters = "";
            for (var item in options.facets) {
                urlfilters += "facet.field=" + options.facets[item]['field'] + "&";
            }
            // build starting URL
            var theurl = options.search_url + urlparams + pageparams + urlfilters + options.query_parameter + "=";
            // add default query values
            // build the query, starting with default values
            var query = "";
            for (var item in options.predefined_filters) {
                query += item + ":" + options.predefined_filters[item] + " AND ";
            }
            $('.facetsearch_filterselected',obj).each(function() {
                query += $(this).attr('rel') + ':"' + 
                $(this).attr('href') + '" AND ';
            });
            // add any freetext filter
            if ($('#facetsearch_freetext').val() != "") {
                query += $('#facetsearch_freetext').val() + '*';
            }
            query = query.replace(/ AND $/,"");
            // set a default for blank search
            if (query == "") {
                query = options.q;
            }
            theurl += query;
            return theurl;
        }

        // build the search query URL based on current params
        var elasticsearchquery = function() {
            var qs = {}
            var bool = false
            $('.facetsearch_filterselected',obj).each(function() {
                !bool ? bool = {
                    'must': []
                } : ""
                if ( $(this).hasClass('facetsearch_facetrange') ) {
                    var rel = options.facets[ $(this).attr('rel') ]['field']
                    var rngs = {
                        'from': $('.facetsearch_lowrangeval', this).html(),
                        'to': $('.facetsearch_highrangeval', this).html()
                    }
                    var obj = {
                        'range': {}
                    }
                    obj['range'][ rel ] = rngs
                    bool['must'].push(obj)
                } else {
                    var obj = {
                        'term':{}
                    }
                    obj['term'][ $(this).attr('rel') ] = $(this).attr('href')
                    bool['must'].push(obj)
                }
            });
            for (var item in options.predefined_filters) {
                !bool ? bool = {
                    'must': []
                } : ""
                var obj = {
                    'term': {}
                }
                obj['term'][ item ] = options.predefined_filters[item]
                bool['must'].push(obj)
            }
            if (bool) {
                $('#facetsearch_freetext').val() != ""
                ? bool['must'].push( {
                    'query_string': {
                        'query': $('#facetsearch_freetext').val()
                    }
                } )
                : ""
                qs['query'] = {
                    'bool': bool
                }
            } else {
                $('#facetsearch_freetext').val() != ""
                ? qs['query'] = {
                    'query_string': {
                        'query': $('#facetsearch_freetext').val()
                    }
                }
                : qs['query'] = {
                    'match_all': {}
                }
            }
            // set any paging
            options.paging.from != 0 ? qs['from'] = options.paging.from : ""
            options.paging.size != 10 ? qs['size'] = options.paging.size : ""
            // set any facets
            qs['facets'] = {};
            for (var item in options.facets) {
                var obj = options.facets[item]
                delete obj['display']
                qs['facets'][obj['field']] = {
                    "terms":obj
                }
            }
            return JSON.stringify(qs)
        }

        var httpsearchquery = function() {
            var searchparams = new Object();
            // set default URL params
            var urlparams = "";
            for (var item in options.default_url_params) {
                urlparams += item + "=" + options.default_url_params[item] + "&";
            }
            
          
            // do paging params
            var pageparams = "";
            for (var item in options.paging) {
                pageparams += item + "=" + options.paging[item] + "&";
            }
            
            // set facet params
            var urlfilters = "";
            for( fac in selected_terms){
                for(term in selected_terms[fac]){
                    urlfilters += "facets[" + fac + "][]="+selected_terms[fac][term]+"&";
                }
            }
            for (var item in options.facets) {
                urlfilters += "facetsarray[]=" + options.facets[item]['field'] +"&";
            }
            for (var item in options.entities) {
                urlfilters += "entities[]=" + options.entities[item] +"&";
            }
            // build starting URL
            var theurl = options.search_url + urlparams + pageparams + urlfilters + options.query_parameter + "=";
            // add default query values
            // build the query, starting with default values
            var query = "";
            /*for (var item in options.predefined_filters) {
                query += item + ":" + options.predefined_filters[item] + " AND ";
            }*/
            /*$('.facetsearch_filterselected',obj).each(function() {
                query += $(this).attr('rel') + ':"' + 
                    $(this).attr('href') + '" AND ';
            });*/
            // add any freetext filter
            if ($('#facetsearch_freetext').val() != "") {
                query += $('#facetsearch_freetext').val();
            }
            query = query.replace(/ AND $/,"");
             
            theurl += query;
            
            searchparams.paging = options.paging;
            searchparams.selected_terms = selected_terms;
            searchparams.facets = options.facets;
            searchparams.entities = options.entities;
            searchparams.query = query;
            //console.log(searchparams)
            //console.log(encodeURI($.toJSON(searchparams)))
            return options.search_url+"searchparams="+encodeURI($.toJSON(searchparams))
        //return theurl;
        }

        // execute a search
        var dosearch = function() {
            fmap.gmap('closeInfoWindow');
            if ( options.search_index == "elasticsearch" ) {
                $.ajax({
                    type: "get",
                    url: options.search_url,
                    data: {
                        source: elasticsearchquery()
                    },
                    // processData: false,
                    dataType: "jsonp",
                    success: showresults
                });
            } else if (options.search_index == "solrsearch" ){
                $.ajax( {
                    type: "get", 
                    url: solrsearchquery(), 
                    dataType:"jsonp", 
                    jsonp:"json.wrf", 
                    success: function(data) {
                        showresults(data)
                    }
                } );
            }else{
                $.ajax( {
                    type: "get", 
                    url: httpsearchquery(), 
                    dataType:"json", 
                    success: function(data) {
                        showresults(data)
                    }
                } );
            
            }
        }

        // trigger a search when a filter choice is clicked
        var clickfilterchoice = function(event) {
            event.preventDefault();
			
            if(selected_terms.hasOwnProperty($(this).data("entity")) && $.inArray($(this).data("field"), selected_terms[$(this).data("entity")]) > -1 && $.inArray($(this).data("value"), selected_terms[$(this).data("entity")][$(this).data("field")]) > -1){
                return;
            }
            if(!selected_terms.hasOwnProperty($(this).data("entity"))){
                selected_terms[$(this).data("entity")] = new Object();
            }
            if(!selected_terms[$(this).data("entity")].hasOwnProperty($(this).data("field"))){
                selected_terms[$(this).data("entity")][$(this).data("field")] = new Array();
            }
			
            selected_terms[$(this).data("entity")][$(this).data("field")].push($(this).data("value"));
			
            var newobj = '<a class="facetsearch_filterselected facetsearch_clear ' + 
            'btn btn-info" data-value="' + $(this).data("value") + 
            '" alt="remove" title="remove"' +
            ' href="#" data-entity="' + $(this).data("entity") + '" data-field="' + $(this).data("field") + '">' +
            $(this).html().replace(/\(.*\)/,'') + ' <i class="icon-remove"></i></a>';
            $('#facetsearch_selectedfilters').append(newobj);
            $('.facetsearch_filterselected').unbind('click',clearfilter);
            $('.facetsearch_filterselected').bind('click',clearfilter);
            options.paging.from = 0
            dosearch();
        }

        // clear a filter when clear button is pressed, and re-do the search
        var clearfilter = function(event) {
            event.preventDefault();
            if(selected_terms.hasOwnProperty($(this).data("entity")) && $.inArray($(this).data("field"), selected_terms[$(this).data("entity")]) > -1 && $.inArray($(this).data("value"), selected_terms[$(this).data("entity")][$(this).data("field")]) > -1){
                selected_terms[$(this).data("entity")][$(this).data("field")].splice(selected_terms[$(this).data("entity")][$(this).data("field")].indexOf($(this).data("value")),1);
            }

            $(this).remove();
            dosearch();
        }

        var clearfacetfilters = function(event) {
            event.preventDefault();
			
            $('.facetsearch_filterselected[data-entity="'+$(this).data("entity")+'"][data-field="'+$(this).data("field")+'"]').remove();
						
            if(selected_terms[$(this).data("entity")].hasOwnProperty($(this).data("field"))){
                delete selected_terms[$(this).data("entity")][$(this).data("field")];
            }

            //$(this).remove();
            dosearch();
        }
		
        var clearallfilters =function(event) {
            event.preventDefault();
						
            $('.facetsearch_filterselected').remove();
            selected_terms = new Array();
            $('#facetsearch_freetext').val("");
            
            dosearch();
        }
        // do search options
        var fixmatch = function(event) {
            event.preventDefault();
            if ( $(this).attr('id') == "facetsearch_partial_match" ) {
                var newvals = $('#facetsearch_freetext').val().replace(/"/gi,'').replace(/\*/gi,'').replace(/\~/gi,'').split(' ');
                var newstring = "";
                for (item in newvals) {
                    if (newvals[item].length > 0 && newvals[item] != ' ') {
                        if (newvals[item] == 'OR' || newvals[item] == 'AND') {
                            newstring += newvals[item] + ' ';
                        } else {
                            newstring += '*' + newvals[item] + '* ';
                        }
                    }
                }
                $('#facetsearch_freetext').val(newstring);
            } else if ( $(this).attr('id') == "facetsearch_fuzzy_match" ) {
                var newvals = $('#facetsearch_freetext').val().replace(/"/gi,'').replace(/\*/gi,'').replace(/\~/gi,'').split(' ');
                var newstring = "";
                for (item in newvals) {
                    if (newvals[item].length > 0 && newvals[item] != ' ') {
                        if (newvals[item] == 'OR' || newvals[item] == 'AND') {
                            newstring += newvals[item] + ' ';
                        } else {
                            newstring += newvals[item] + '~ ';
                        }
                    }
                }
                $('#facetsearch_freetext').val(newstring);
            } else if ( $(this).attr('id') == "facetsearch_exact_match" ) {
                var newvals = $('#facetsearch_freetext').val().replace(/"/gi,'').replace(/\*/gi,'').replace(/\~/gi,'').split(' ');
                var newstring = "";
                for (item in newvals) {
                    if (newvals[item].length > 0 && newvals[item] != ' ') {
                        if (newvals[item] == 'OR' || newvals[item] == 'AND') {
                            newstring += newvals[item] + ' ';
                        } else {
                            newstring += '"' + newvals[item] + '" ';
                        }
                    }
                }
                $.trim(newstring,' ');
                $('#facetsearch_freetext').val(newstring);
            } else if ( $(this).attr('id') == "facetsearch_match_all" ) {
                $('#facetsearch_freetext').val($.trim($('#facetsearch_freetext').val().replace(/ OR /gi,' ')));
                $('#facetsearch_freetext').val($('#facetsearch_freetext').val().replace(/ /gi,' AND '));
            } else if ( $(this).attr('id') == "facetsearch_match_any" ) {
                $('#facetsearch_freetext').val($.trim($('#facetsearch_freetext').val().replace(/ AND /gi,' ')));
                $('#facetsearch_freetext').val($('#facetsearch_freetext').val().replace(/ /gi,' OR '));
            }
            $('#facetsearch_freetext').focus().trigger('keyup');
        }


        // adjust how many results are shown
        var howmany = function(event) {
            event.preventDefault()
            var newhowmany = prompt('Currently displaying ' + options.paging.size + 
                ' results per page. How many would you like instead?')
            if (newhowmany) {
                options.paging.size = parseInt(newhowmany)
                options.paging.from = 0
                $('#facetsearch_howmany').html('results per page (' + options.paging.size + ')')
                dosearch()
            }
        }
		
        var initmap = function() { 
		
		        
		
        // This URL won't work on your localhost, so you need to change it
        // see http://en.wikipedia.org/wiki/Same_origin_policy
        /*$.getJSON( 'http://jquery-ui-map.googlecode.com/svn/trunk/demos/json/demo.json', function(data) { 
					$.each( data.markers, function(i, marker) {
						$('#map_canvas').gmap('addMarker', { 
							'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
							'bounds': true 
						}).click(function() {
							$('#map_canvas').gmap('openInfoWindow', { 'content': marker.content }, this);
						});
					});
				});*/
        }

        // the facet view object to be appended to the page
        var thefacetsearch = ' \
           <div id="facetsearch"> \
             <div class="row-fluid"> \
               <div class="span3"> \
                 <div class="box"><h4 class="box-header round-top">Filters</h4> \
							<div class="box-container-toggle"><div class="box-content"> \
							<div id="facetsearch_filters" class="box"></div> \
               </div></div></div></div> \
               <div class="span9"> \
						<div style="clear:both;" id="facetsearch_selectedfilters" ></div> \
						<div id="mapcontainer" class="box">\
							<h4 class="box-header round-top">Map <a class="box-btn" title="toggle"><i class="icon-minus"></i></a></h4> \
							<div class="box-container-toggle"> \
							<div id="mapcanvas"></div> \
						</div> </div> \
						<div id="detailscontainer" class="box"> \
							<h4 class="box-header round-top">Results <a class="box-btn" title="toggle"><i class="icon-minus"></i></a></h4> \
							<div class="box-container-toggle"><div class="box-content"> \
							<table class="table table-striped" id="facetsearch_results"></table> \
							<div id="facetsearch_metadata"></div> \
						</div> </div></div>\
			   </div> \
             </div> \
           </div> \
           ';

        function widgetToggle(e) {
            // Make sure the bottom of the box has rounded corners
            e.parent().toggleClass("round-all");
            e.parent().toggleClass("round-top");

            // replace plus for minus icon or the other way around
            if(e.html() == "<i class=\"icon-plus\"></i>") {
                e.html("<i class=\"icon-minus\"></i>");
            } else {
                e.html("<i class=\"icon-plus\"></i>");
            }

            // close or open box
            e.parent().next(".box-container-toggle").toggleClass("box-container-closed");

            // store closed boxes in cookie
            var closedBoxes = [];
            var i = 0;
            $(".box-container-closed").each(function()
            {
                closedBoxes[i] = $(this).parent(".box").attr("id");
                i++;
            });
		
            //Prevent the browser jump to the link anchor
            return false;

        }
        // ===============================================
        // now create the plugin on the page
        return this.each(function() {
            // get this object
            obj = $(this);

            // append the facetsearch object to this object
            $(obj).append(thefacetsearch);
			
			
            fmap = $('#mapcanvas').gmap().bind('init', initmap);
            fmarkerclusterer =  new MarkerClusterer(fmap.gmap('get', 'map'), fmap.gmap('get', 'markers'));

            // append the filters to the facetsearch object
            buildfilters();
			
            $('.box-btn').click(function() {
                var e = $(this);
                //var p = b.next('a');
                // Control functionality
                switch(e.attr('title').toLowerCase()) {
                    case 'config':
                        widgetConfig(b, p);
                        break;

                    case 'toggle':
                        widgetToggle(e);
                        break;

                    case 'close':
                        widgetClose(e);
                        break;
                }
            });
		
            if (options.description) {
                $('#facetsearch_filters').append('<div><h3>Meta</h3>' + options.description + '</div>')
            }
			
            // setup search option triggers
            $('#facetsearch_partial_match').bind('click',fixmatch)
            $('#facetsearch_exact_match').bind('click',fixmatch)
            $('#facetsearch_fuzzy_match').bind('click',fixmatch)
            $('#facetsearch_match_any').bind('click',fixmatch)
            $('#facetsearch_match_all').bind('click',fixmatch)
            $('#facetsearch_howmany').bind('click',howmany)

            // resize the searchbar
            var thewidth = $('#facetsearch_searchbar').parent().parent().width()
            //$('#facetsearch_searchbar').css('width',thewidth - 50 + 'px')
            //$('#facetsearch_freetext').css('width', thewidth - 78 + 'px')

			
            $('#facetsearch_freetext',obj).bindWithDelay('keyup',dosearch,options.freetext_submit_delay);

            // trigger the search once on load, to get all results
            dosearch();

        }); // end of the function  


    };
})(jQuery);


