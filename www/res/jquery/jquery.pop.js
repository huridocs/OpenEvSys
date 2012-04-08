function menuPop(element){
    this.element = element;
    this.menu = null;
    this.value = null;
    this.mode = null;

    this.createPopup= function(options, mode){
        this.mode = mode;
        var ul = $('<ul></ul>');
        for(var option in options){
            var a = $('<a>'+ options[option].label +'</a>').attr('data-value', options[option].value);
            var li = $('<li></li>').append(a);
            if(mode == 'multi'){
                var chk = $('<input type="checkbox" value="'+options[option].value+'" class="qb-multi-check" />');
                li.prepend(chk);
            }
            li.bind('click',this,function(e){
                if($(e.originalTarget).hasClass('qb-multi-check'))return;
                e.data.value = $(this).find('a').attr('data-value');
                e.data.element.text($(this).text());
                e.data.element.attr('data-value',e.data.value);
                var oc = jQuery.Event("onchange");
                e.data.element.trigger(oc);
            });
            ul.append(li);
        }
        var div = $("<div class='pop'></div>");
        div.append(ul);
        ul.wrap('<div class="pop_menu"></div>');
        this.element.before(div);
        this.menu = div;
    }

    this.showPopup = function(){
        this.menu.toggleClass("active");
        return false;
    }

    this.hidePopup = function(){
        this.menu.removeClass("active");;
    }

    $(document).click(function(e){
        if($(e.originalTarget).hasClass('qb-multi-check'))return;
        $('.pop').removeClass('active');
    });
}

(function($){
    $.fn.menupop = function(options) {
        var defaults = {
            options: ['Empty...'],
            mode  : 'single'
        };
        var options = $.extend(defaults, options);

        return this.each(function()
        {
            var obj = $(this);
            var mp = new menuPop(obj);
            mp.createPopup(options.options, options.mode);
            obj.click(function(){return mp.showPopup();});
        });

        function debug($obj) {
            if (window.console && window.console.log) {
                 ;//console.log();
            }
        };
    };
})(jQuery);
