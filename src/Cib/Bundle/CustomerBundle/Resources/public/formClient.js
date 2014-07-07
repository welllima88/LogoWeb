/**
 * Created by cedric on 07/07/14.
 */
// Récupère le div qui contient la collection de tags
var collectionHolder = $('ul.card');
// ajoute un lien « add a tag »
var $addCardLink = $('<a href="#" class="add_card_link">Ajouter une carte</a>');
var $newLinkLi = $('<span></span>').append($addCardLink);
//    var $removeLink = $('<li></li>').append($removeCardLink);

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