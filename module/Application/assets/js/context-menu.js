$.extend(App, {
    context: {
        applyContextMenu: function () {
            $('[data-type="context-menu"]').on('contextmenu', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/api/context/menu/' + $(this).data('user'),
                    dataType: 'jsonp',
                    jsonpCallback: 'App.context.contextMenuCallback'
                });
                $(this).unbind();
            });
        },
        contextMenuCallback: function(context) {
            var selector = '[data-type="context-menu"][data-user="'+context.userId+'"]';
            $.contextMenu({
                selector: selector,
                items: context.items
            });
            $(selector).contextMenu();
        }
    }
});

$(function() { App.context.applyContextMenu() });