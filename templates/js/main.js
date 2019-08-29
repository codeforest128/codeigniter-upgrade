// ----------------------------------------------------------------------------
// RequireJS config


require.config({
    appDir: ".",
    baseUrl: "/ice-diary/templates/js",
    paths : {
        async: 'lib/async'
    }
});


// ----------------------------------------------------------------------------
// Init globals


require([
        'bootstrap/datepicker',
        'bootstrap/affix',
        'bootstrap/typeahead',
        'bootstrap/popover',
        'bootstrap/alert'
    ], function() {
        $('.datepicker').datepicker({
            'format': 'dd/mm/yyyy',
            'weekStart': 1
        });

        $('.typeahead').each(function(){
            var $this = $(this);
            $this.typeahead({
                source : function(query, process){
                    var field = $this.data('field')

                    return $.get('/bookings/auto_complete/' + field, { query: query }, function (data) {
                        process(data);
                    });
                },
                matcher: function (item) {
                    if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
                        return true;
                    }
                },
                sorter: function (items) {
                    return items.sort();
                },
                'items' : 5,
                'minLength' : 1
            });
        });

        $('.booked, .booked-provisional').each(function() {
           var $this = $(this);
           $this.popover({
               placement : 'left',
               trigger : 'hover',
               html : true,
               title : $('.title', $this).html(),
               content : $('.content', $this).html()
           });
        });

        $(".alert").alert()

        // Function to show and hide repeat options
        var $repeats = $('#repeats');

        if($repeats.length > 0) {
            var $repeats_by = $('#repeats_by');
            var $warning = $('.repeat-warning');
            var slide_speed = 150;

            // only hide repeats_by if form does not load on monthly e.g. page validation
            if($repeats.val() != 'monthly') {
                $repeats_by.hide();
            };
            if($repeats.val() == '') {
                $warning.hide();
            }
            $repeats.change(function() {
                if($repeats.val() == 'monthly') {
                    $repeats_by.slideToggle(slide_speed);
                }
                else if($repeats_by.is(':visible')) {
                    $repeats_by.slideToggle(slide_speed);
                }

                if($repeats.val() != '') {
                    $warning.slideDown(slide_speed);
                }
                else {
                    $warning.slideUp(slide_speed);
                }
            });
        }

        var $quickjump = ($('#calendar-quickjump'));

        if($quickjump.length > 0) {
            $('.btn', $quickjump).hide();
            $('select', $quickjump).change(function(){
               $('.btn', $quickjump).click();
            });
        }

        var $root = $('html, body');

        // smooth scroll to anchor
        $('a').click(function() {
            var href = $.attr(this, 'href');

            if(href == undefined ) {
                return false;
            }

            $root.animate({
                scrollTop: $(href).offset().top
            }, 250);

            return false;
        });
});
