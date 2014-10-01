
$(document).ready(function() {
    $.datepicker.setDefaults({
        dateFormat: 'DD dd-mm-yy',
        firstDay:0,
        dayNames: ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'],
        dayNamesMin: [ "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di" ],
        dayNamesShort: [ "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim" ],
        monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
        monthNamesShort: [ "Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec" ]
    });
    var cardContainer = $('#cib_bundle_databundle_results_card');
    var clientContainer = $('#cib_bundle_databundle_results_client');
    var dateStartContainer = $('#cib_bundle_databundle_results_dateStart');
    var dateStopContainer = $('#cib_bundle_databundle_results_dateStop');
    var monthContainer = $('#cib_bundle_databundle_results_month');
    var storeContainer = $('#cib_bundle_databundle_results_store');
    var url = $("form").attr('action');

    dateStartContainer.datepicker();
    dateStopContainer.datepicker();
    cardContainer.attr({maxLength:10});
    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url);


    cardContainer.on('change',function(){

    });

    cardContainer.keyup(function(){
        if(this.value.length >= 4)
            autoLoadCard(this.value,url);
        if(this.value.length == 10)
            selectResults();
    });

    clientContainer.keyup(function(){
        if(this.value.length >= 2)
        {
            autoLoadClient(this.value,url);
            selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,this.value,storeContainer.value,url);
        }

    });

    dateStopContainer.on('change', function () {
        dateStartContainer.datepicker('option','maxDate',this.value);
        selectResults(monthContainer.value,dateStartContainer.value,this.value,cardContainer.value,clientContainer.value,storeContainer.value,url);
    });

    dateStartContainer.on('change',function(){
        dateStopContainer.datepicker('option','minDate',this.value);
        selectResults(monthContainer.value,this.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url);
    });

    monthContainer.on('change', function () {
        selectResults(this.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url);
    });

    storeContainer.on('change',function(){
        selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,this.value,url);
    })
});




function autoLoadCard(val,url){
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : 'searchCard='+val, // données à envoyer en  GET ou POST
        content: 'json',
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
            //console.log($.parseJSON(data));
            var suggestion = [];
            $.each($.parseJSON(data), function(i, item) {
                console.log(item);
               suggestion.push(item.cardNumber);
            });
            $('#cib_bundle_databundle_results_card').autocomplete({
                source : suggestion
            });
        }
    });
}

function autoLoadClient(val,url){
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : 'searchClient='+val, // données à envoyer en  GET ou POST
        content: 'json',
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
            //console.log($.parseJSON(data));
            var suggestion = [];
            $.each($.parseJSON(data), function(i, item) {
                console.log(item);
                suggestion.push(item.clientName);
            });
            $('#cib_bundle_databundle_results_client').autocomplete({
                source : suggestion
            });
        }
    });
}

function selectResults(month,dateStart,dateStop,card,client,store,url){

    if(month == undefined)
        month = '';
    if(dateStart == undefined)
        dateStart = '';
    if(dateStop == undefined)
        dateStop = '';
    if(card == undefined)
        card = '';
    if(client == undefined)
        client = '';
    if(store == undefined)
        store ='';


var data;

    data = 'month='+month+'&start='+dateStart+'&stop='+dateStop+'&card='+card+'&client='+client+'&store='+store;
    var resultContainer = $("#divResult");
    var srcLoader = resultContainer.attr("class");
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : data + "&result=ok", // données à envoyer en  GET ou POST
        content: 'json',
        //beforeSend: function(){
        //    resultContainer.after('<img src="{{ asset('' }}">');
        //},
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php

            $('#tabResultDebit').remove();
            $('#tabResultCredit').remove();

            resultContainer.append('<table id="tabResultDebit" class="table table-bordered table-responsive"><tr><td colspan="6">Débit</td></tr><tr><td>Date transaction</td><td>Numéro de carte</td><td>Pme</td><td>VIP</td><td>Prime</td><td>Montant</td></tr></table>');
            resultContainer.append('<table id="tabResultCredit" class="table table-bordered table-responsive"><tr><td colspan="6">Crédit</td></tr><tr><td>Date transaction</td><td>Pme</td><td>VIP</td><td>Prime</td><td>Montant</td></tr></table>');
            //$("#divResult").append('<table id="tabResult"></table>');
            $.each(JSON.parse(data), function(i, item) {

                if(item.type_transaction == 'D')
                    $("#tabResultDebit").append('<tr><td>'+item.date_transaction+'</td><td>'+item.card.card_number+'</td><td>'+item.pme_transaction+'</td><td>'+item.is_vip_transaction+'</td><td>'+item.prime_transaction+'</td><td>'+item.amount_transaction+'</td></tr>');
                else
                    $("#tabResultCredit").append('<tr><td>'+item.date_transaction+'</td><td>'+item.card.card_number+'</td><td>'+item.pme_transaction+'</td><td>'+item.is_vip_transaction+'</td><td>'+item.prime_transaction+'</td><td>'+item.amount_transaction+'</td></tr>');
                    });

                $('#loaderOn').remove();
            },
        beforeSend : function() { // traitements JS à faire AVANT l'envoi
            resultContainer.after('<div class="row"><img src="'+srcLoader+'" id="loaderOn" class="col-md-offset-5 ">');
        }

    });





}