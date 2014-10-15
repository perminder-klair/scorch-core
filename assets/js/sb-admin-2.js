$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    })
});

//Pages Admin Area
$(function() {
    var parentListing = [];

    var getChildern = function (parent) {

        var children = [];
        var childOrder = 1;

        parent.find('ul.children').children('li.item').each(function(){
            children.push({
                id : $(this).attr('data-item'),
                listingOrder : childOrder++,
                children: getChildern($(this))
            });
        });

        return children;

    };

    var scanSortableList = function () {
        var listingOrder = 1;

        $('ul.sortable.list.parent').children('li.item').each(function(){
            parentListing.push({
                id : $(this).attr('data-item'),
                listingOrder : listingOrder++,
                children: getChildern($(this))
            });
        });

        console.log(parentListing);
    };
    scanSortableList();

    $('.sortable').sortable({
        handle: '.sort-box'
    }).bind('sortupdate', function(e, ui) {
        console.log('updating pages...');
        parentListing = []; //empty first then run again
        scanSortableList();

        //do ajax request
        $.post( "reorder", {data:JSON.stringify(parentListing)}, function() {
            console.log( "reordering done" );
        });
    });

    $('#pages-admin').metisMenu();

    $( document ).ready(function() {
        var pageListSelector = $('.widget-tasks-assigned ul li');
        var updateExpandIcon = function (selected) {
            if (selected.hasClass('active')) {
                selected.find('.icon-expand').first().removeClass('fa-chevron-right').addClass('fa-chevron-down');
            } else {
                selected.find('.icon-expand').first().removeClass('fa-chevron-down').addClass('fa-chevron-right');
            }
        };
        pageListSelector.each(function () {
            updateExpandIcon($(this));
        });
        pageListSelector.on('click', function () {
            updateExpandIcon($(this));
        })
    });
});
//Pages Admin Area