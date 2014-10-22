/**
 * Created by cedric on 22/10/2014.
 */

$.getCityFromZipCode = function(field,autocompleteField){

    var suggestions = [];
    if(field.value.length > 2)
    {
        var data = field.value;

        if(data.length < 5)
        {
            for(var i = data.length;i < 5;i++)
            {
                data = data+"0";
            }
        }

        $.ajax({
            url: "http://api.zippopotam.us/fr/" + data,
            cache: false,
            dataType: "json",
            type: "GET",
            success: function(result, success) {
                while(suggestions.length > 0){
                    suggestions.pop();
                }
                for ( ii in result['places']){
                    suggestions.push(result['places'][ii]['place name']);
                }
                autocompleteField.autocomplete({
                    source: suggestions,
                    minLength: 0
                }).on('focus', function() { $(this).keydown(); });;

            }
        });
    }
};