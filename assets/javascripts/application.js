jQuery(document).ready(function() {
    // Load existing
    var itemTemplate = [
        '<li class="item-list-item" data-id="{item.id}" style="margin-bottom: 10px;">',
            '<div class="row">',
                '<div class="col-md-2">',
                    'REORDER',
                '</div>',
                '<div class="col-md-8">',
                    '<input class="form-control item-input" type="text" value="{item.text}" />',
                '</div>',
                '<div class="col-md-2">',
                    '<button class="btn btn-block btn-danger remove-button" type="button">x</button>',
                '</div>',
            '</div>',
        '</li>',
    ].join('');

    function loadItems() {
        jQuery.ajax({
            method: 'GET',
            url: 'items',
            success: function(response) {
                var items = response.items;

                jQuery('#items-list').text(''); // Clear the items list first

                if (items.length) {
                    for (var i = 0; i < items.length; i++) {
                        var item = items[i];

                        var thisItemTemplate = itemTemplate
                            .replace('{item.id}', item.id)
                            .replace('{item.text}', item.text)
                        ;

                        jQuery(thisItemTemplate)
                            .appendTo('#items-list')
                        ;
                    }

                    attachRemoveButtonClickEvent();
                    attachEditItemEvent();
                }
            }
        });
    }
    loadItems();

    // Save new
    jQuery('#new-item-button').on('click', function() {
        var text = jQuery('#new-item-input').val();

        if (! text) {
            alert('The text MUST not be empty!');

            return;
        }

        jQuery.ajax({
            method: 'POST',
            url: 'items',
            data: 'text='+text,
            success: function(response) {
                jQuery('#new-item-input').val('');

                loadItems();
            }
        });

        return;
    });

    // Remove
    // Because the items are loaded dynamically, we need to attach the click event after the new items are added to the DOM
    function attachRemoveButtonClickEvent() {
        jQuery('.remove-button').on('click', function() {
            var id = jQuery(this).closest('.item-list-item').attr('data-id');

            jQuery.ajax({
                method: 'DELETE',
                url: 'items/'+id,
                success: function(response) {
                    loadItems();
                }
            });
        });
    }

    // Edit
    // The same as with the remove event
    function attachEditItemEvent() {
        var saveItem = debounce(function() {
            var id = jQuery(this).closest('.item-list-item').attr('data-id');
            var text = jQuery(this).val();
            var order = 999; // To-Do

            jQuery.ajax({
                method: 'PUT',
                url: 'items/'+id,
                data: 'text='+text+'&order='+order,
                success: function(response) {
                    console.log('Saved');
                }
            });
        }, 500);

        jQuery('.item-input').on('input', saveItem);
    }

    // Helpers
    // Debounce
    // https://davidwalsh.name/javascript-debounce-function
    function debounce(func, wait, immediate) {
    	var timeout;
    	return function() {
    		var context = this, args = arguments;
    		var later = function() {
    			timeout = null;
    			if (!immediate) func.apply(context, args);
    		};
    		var callNow = immediate && !timeout;
    		clearTimeout(timeout);
    		timeout = setTimeout(later, wait);
    		if (callNow) func.apply(context, args);
    	};
    }
});
