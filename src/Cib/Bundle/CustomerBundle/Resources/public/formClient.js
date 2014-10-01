/**
 * Created by cedric on 07/07/14.
 */
// Récupère le div qui contient la collection de tags
var collectionHolder = $('ul.card');
// ajoute un lien « add a tag »
var $addCardLink = $('<div class="row"><div class="container"><div class="col-md-offset-3 col-md-2"><a href="#" class="add_card_link btn btn-info">Ajouter une carte</a></div></div></div>');
var $newLinkLi = $('<span></span>').append($addCardLink);
var formCityDebtorContainer = $("#cib_bundle_customerbundle_client_bankAccount_debtorCity");
var formCityCreditorContainer = $("#cib_bundle_customerbundle_client_bankAccount_creditorCity");
var formZipCodeDebtorContainer = $("#cib_bundle_customerbundle_client_bankAccount_debtorZipCode");
var formZipCodeCreditorContainer = $("#cib_bundle_customerbundle_client_bankAccount_creditorZipCode");

jQuery(document).ready(function() {
    // ajoute l'ancre « ajouter un tag » et li à la balise ul
    collectionHolder.append($newLinkLi);
    collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    $addCardLink.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
        addCardForm(collectionHolder, $newLinkLi);
    });

    $('#toggleCard').click(function(){
        $('.toggle').toggle();
    });
});

function addCardForm(collectionHolder, $newLinkLi) {
//        alert(collectionHolder);
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');
//        alert(prototype);
    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
//        alert(collectionHolder.children().length);
//        alert(newForm);
    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"
    var $newFormLi = $('<li style="list-style-type: none"></li>').append(newForm);

    $newLinkLi.before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}


function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#">Supprimer cette carte</a><div><br/></div>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // supprime l'élément li pour le formulaire de tag
        $tagFormLi.remove();
    });
}

formZipCodeDebtorContainer.keyup(function(){
    var suggestions = [];
    if(this.value.length > 2)
    {
        var data = this.value;
        var debtorCountry = $("#cib_bundle_customerbundle_client_bankAccount_debtorCountry").val();

        if(data.length < 5)
        {
            for(var i = data.length;i < 5;i++)
            {
                data = data+"0";
            }
        }

        $.ajax({
            url: "http://api.zippopotam.us/"+debtorCountry+"/" + data,
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
                formCityDebtorContainer.autocomplete({
                    source: suggestions,
                    minLength: 0
                }).on('focus', function() { $(this).keydown(); });;

            }
        });
    }

});

formZipCodeCreditorContainer.keyup(function(){
    var suggestions = [];
    if(this.value.length > 2)
    {
        var data = this.value;
        var creditorCountry = $("#cib_bundle_customerbundle_client_bankAccount_creditorCountry").val();

        if(data.length < 5)
        {
            for(var i = data.length;i < 5;i++)
            {
                data = data+"0";
            }
        }

        $.ajax({
            url: "http://api.zippopotam.us/"+creditorCountry+"/" + data,
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
                formCityCreditorContainer.autocomplete({
                    source: suggestions,
                    minLength: 0
                }).on('focus', function() { $(this).keydown(); });;

            }
        });
    }

});
