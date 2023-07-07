console.log('Hello world');

jQuery(document).ready(function($) {
    // AJAX callback for retrieving books
    function getBooks() {
        jQuery.ajax({
            url: ajax_object.ajax_url, // AJAX endpoint
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'get_books', // AJAX action
            },
            success: function(response) {
                console.log(response); // Display the JSON response in the console
                // Handle the response data as needed
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('Error:', errorThrown);
            }
        });
    }

    // Call the getBooks function
    getBooks();
});
