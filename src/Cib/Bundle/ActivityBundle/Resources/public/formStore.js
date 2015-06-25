/**
 * Created by cedric on 07/07/14.
 */
// Récupère le div qui contient la collection de tags
var collectionHolder = $('ul.tpe');
// ajoute un lien « add a tag »
var $addStoreLink = $('<div class="row"><div class="container"><div class="col-md-offset-1 col-md-2"><a href="#" class="add_tpe_link btn btn-info">Ajouter un tpe</a></div></div></div>');
var $newLinkLi = $('<span></span>').append($addStoreLink);
var formCityStore = $("#cib_bundle_activitybundle_store_storeCity");
var formZipCodeStore = $("#cib_bundle_activitybundle_store_storeZipCode");
//var formCityDebtorContainer = $("#cib_bundle_customerbundle_client_bankAccount_debtorCity");
//var formCityCreditorContainer = $("#cib_bundle_customerbundle_client_bankAccount_creditorCity");
//var formZipCodeDebtorContainer = $("#cib_bundle_customerbundle_client_bankAccount_debtorZipCode");
//var formZipCodeCreditorContainer = $("#cib_bundle_customerbundle_client_bankAccount_creditorZipCode");

jQuery(document).ready(function() {
    // ajoute l'ancre « ajouter un tag » et li à la balise ul
    collectionHolder.append($newLinkLi);
    collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    $addStoreLink.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
        addTpeForm(collectionHolder, $newLinkLi);
    });

    $('#toggleTpe').click(function(){
        $('.toggle').toggle();
    });
});

function addTpeForm(collectionHolder, $newLinkLi) {
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
    var $removeFormA = $('<br><a href="#">Supprimer ce tpe</a><div><br/></div>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // supprime l'élément li pour le formulaire de tag
        $tagFormLi.remove();
    });
}

formZipCodeStore.keyup(function(){
    $.getCityFromZipCode(this,formCityStore);
});
