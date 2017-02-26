$(document).ready(function (){
    $( function() {

        $( "#search" ).autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: "search/query",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response( data.tag_name );
                    }
                } );
            },
            minLength: 2,
        } );
    } );
});