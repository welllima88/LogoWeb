var cardContainer = $('#cib_bundle_databundle_results_card');
var clientContainer = $('#cib_bundle_databundle_results_client');
var dateStartContainer = $('#cib_bundle_databundle_results_dateStart');
var dateStopContainer = $('#cib_bundle_databundle_results_dateStop');
var monthContainer = $('#cib_bundle_databundle_results_month');
var storeContainer = $('#cib_bundle_databundle_results_store');
var url = $("form").attr('action');
var resultContainer = $('#saveResultChoice');
var tabResultContainer = $("#divResult");
var srcLoader = tabResultContainer.attr("class");
var resultList = $("#resultList");
var resultModal = $("#resultModal");
var buttonReset = $("#resetResultChoice");


$(document).ready(function() {
    $.datepicker.setDefaults({
        dateFormat: 'dd-mm-yy',
        firstDay:0,
        dayNames: ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'],
        dayNamesMin: [ "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di" ],
        dayNamesShort: [ "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim" ],
        monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
        monthNamesShort: [ "Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec" ]
    });

    dateStartContainer.datepicker();
    dateStopContainer.datepicker();
    cardContainer.attr({maxLength:10});
    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url);
    getResultList();

    cardContainer.on('change',function(){

    });

    buttonReset.on('click',function(){
        selectResults(undefined,undefined,undefined,undefined,undefined,undefined,url);
    });
    cardContainer.keyup(function(){
        if(this.value.length >= 4)
            autoLoadCard(this.value,url);
        if(this.value.length == 10)
            selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,this.value,clientContainer.value,storeContainer.value,url);
    });

    clientContainer.keyup(function(){
        if(this.value.length >= 2)
            autoLoadClient(this.value,url);
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
    });

    $(".deleteResult").on('click',function(){
        alert('click!');
        $.deleteResult(this.attr('id'));
    });

    resultContainer.on('click',function(){
        $('#inputResult').append('<div class="row"><input type="text" name="nameResult" placeholder="Entrez le nom de votre enregistrment" id="nameResult"><span><button class="btn btn-success" id="nameValid">Valider</button></span><span><button class="btn btn-danger" id="nameCancel">Annuler</button> </span></div> ');
        resultContainer.attr("disabled", "disabled");
        var buttonResultContainer = $('#nameValid');
        var buttonCancel = $('#nameCancel');
        var nameResultContainer = $('#nameResult');
        buttonResultContainer.on('click',function(){
        if(nameResultContainer.val() != undefined)
        {
            saveResult(nameResultContainer.val())
        }
        });
        buttonCancel.on('click',function(){
           buttonResultContainer.remove();
           buttonCancel.remove();
           nameResultContainer.remove();
           resultContainer.removeAttr("disabled");
        });

    });


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
            cardContainer.autocomplete({
                source : suggestion,
                select : function(event, ui){
                    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,this.value,clientContainer.value,storeContainer.value,url);
                }
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
            clientContainer.autocomplete({
                source : suggestion,
                select: function(event,ui){
                    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,this.value,storeContainer.value,url)
                }
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

    var totalCredit = 0;
    var totalDebit = 0;
    var totalPrime = 0;

    var data = 'month='+month+'&start='+dateStart+'&stop='+dateStop+'&card='+card+'&client='+client+'&store='+store;
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : data + "&result=ok", // données à envoyer en  GET ou POST
        content: 'json',
        //beforeSend: function(){
        //    resultContainer.after('<img src="{{ asset('' }}">');
        //},
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php

            //var tabResult = $('#tabResultDebit');
            $('#tabResultDebit').remove();
            $("#tabTotal").remove();
            //$('#tabResultCredit').remove();

            tabResultContainer.append('<table id="tabResultDebit" class="table table-bordered table-responsive"><tr><td colspan="6"><bold>Transactions</bold></td></tr><tr><td>Date transaction</td><td>Numéro de carte</td><td>VIP</td><td>Prime</td><td>Débit</td><td>Crédit</td></tr></table>');
            $.each(JSON.parse(data), function(i, item) {
            var date = new Date(item.date_transaction);
                if(item.type_transaction == 'D')
                {
                    totalDebit = totalDebit + item.amount_transaction;
                    var test = '<td class="col-md-1 col-xs-1">'+item.amount_transaction+'</td><td class="col-md-1 col-xs-1"></td></tr>';
                }
                else
                {
                    totalCredit = totalCredit + item.amount_transaction;
                    totalPrime = totalPrime + item.prime_transaction;
                    var test = '<td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1">'+item.amount_transaction+'</td></tr>';
                }

                $('#tabResultDebit').append('<tr><td class="col-md-1 col-xs-1">'+date.getDate()+'/'+(date.getMonth() + 1) +'/'+date.getFullYear()+'</td><td class="col-md-1 col-xs-1">'+item.card.card_number+'</td><td class="col-md-1 col-xs-1">'+item.is_vip_transaction+'</td><td class="col-md-1 col-xs-1">'+item.prime_transaction+'</td>'+test);});
            $.selectTotal(totalDebit,totalCredit,totalPrime);
                $('#loaderOn').remove();
            },
        beforeSend : function() { // traitements JS à faire AVANT l'envoi
            tabResultContainer.after('<div class="row"><img src="'+srcLoader+'" id="loaderOn" class="col-md-offset-5 ">');
        }

    });
}

function saveResult(name)
{
    if(monthContainer.val() == undefined)
        monthContainer.val = '';
    if(dateStartContainer.val() == undefined)
        dateStartContainer.val = '';
    if(dateStopContainer.val() == undefined)
        dateStopContainer.val = '';
    if(cardContainer.val() == undefined)
        cardContainer.val = '';
    if(clientContainer.val() == undefined)
        clientContainer.val = '';
    if(storeContainer.val() == undefined)
        storeContainer.val ='';

    var data = 'month='+monthContainer.val()+'&start='+dateStartContainer.val()+'&stop='+dateStopContainer.val()+'&card='+cardContainer.val()+'&client='+clientContainer.val()+'&store='+storeContainer.val()+'&name='+name;
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : data + "&saveResult=ok", // données à envoyer en  GET ou POST
        content: 'json',
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
            alert('enregistrment effectué');
            $("#modalWindow").remove();
            $("#linkResult").remove();
            $('#nameValid').remove();
            $('#nameCancel').remove();
            $('#nameResult').remove();
            resultContainer.removeAttr("disabled");
            getResultList();
            $('#loaderOn').remove();
        },
        beforeSend : function() { // traitements JS à faire AVANT l'envoi
            resultContainer.after('<div class="row"><img src="'+srcLoader+'" id="loaderOn" class="col-md-offset-5 ">');
        }

    });
}

function getResultList()
{
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : "resultList=ok", // données à envoyer en  GET ou POST
        content: 'json',
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
            if(data)
            {
                var month;
                var dateStart;
                var dateStop;
                var card;
                var store;
                var client;
                resultList.append('<div class="row"><a class="btn btn-info" data-toggle="modal" data-target="#modalList" id="modalWindow">Préséléctions enregistrées</a></div>');
                resultModal.append('<div class="modal fade" id="modalList" tabindex="-1" role="dialog" aria-labelledby="list" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div></div>');
                $.each(JSON.parse(data), function(i, item){
                    console.log(item);
                    if(item.month)
                        month = item.month;
                    else
                        month = null;
                    if(item.date_start)
                        dateStart = item.date_start;
                    else
                        dateStart = null;
                    if(item.date_stop)
                        dateStop = item.date_stop;
                    else
                        dateStop = null;
                    if(item.card)
                        card = item.card.card_number;
                    else
                        card = null;
                    if(item.store)
                        store = item.store.store_id;
                    else
                        store = null;
                    if(item.client)
                        client = item.client.client_id;
                    else
                        client = null;
                    //var resultId = item.result_id;
                    $(".modal-body").append('<div id="linkResult"></div>');

                    $("#linkResult").append('<a href="#?month='+month+'&dateStart='+dateStart+'&dateStop='+dateStop+'&card='+card+'&store='+store+'&client='+client+'" onClick="refreshResult()">'+item.name+'</a><a href="#?id='+item.result_id+'" class="btn glyphicon glyphicon-remove-circle"  onclick="deleteResult()"></a><br>');
                    //$(".deleteResult").attr('id',item.result_id);
                });
            }
            $('#loaderOn').remove();
        },
        beforeSend : function() {
            resultContainer.after('<div class="row"><img src="'+srcLoader+'" id="loaderOn" class="col-md-offset-5 ">');
        }

    });
}


$.urlParam = function(name){
    var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
    return results[1] || 0;
};


function refreshResult()
{
    $("#modalList").modal('toggle');
    if($.urlParam('card') != 'null')
        cardContainer.value = $.urlParam('card');
    else
        cardContainer.value = "";
    if($.urlParam('client') != 'null')
        clientContainer.value = $.urlParam('client');
    else
        clientContainer.value = "";
    if($.urlParam('dateStart') != 'null')
    {
        var dateStart = new Date($.urlParam('dateStart'));
        dateStartContainer.value = dateStart.getDate()+'-'+(dateStart.getMonth()+1)+'-'+dateStart.getFullYear();
    }
    else
        dateStartContainer.value = "";
    if($.urlParam('dateStop') != 'null')
    {
        var dateStop = new Date($.urlParam('dateStop'));
        dateStopContainer.value = dateStop.getDate()+'-'+(dateStop.getMonth()+1)+'-'+dateStop.getFullYear();
    }
    else
        dateStopContainer.value = "";
    if($.urlParam('month') != 'null')
        monthContainer.value = $.urlParam('month');
    else
        monthContainer.value = "";
    if($.urlParam('store') != 'null')
        storeContainer.value = $.urlParam('store');
    else
        storeContainer.value = "";

    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url);
}

$.selectTotal = function(debit,credit,prime){
    $("#divTotal").append('<table id="tabTotal" class="table table-bordered table-responsive"><tr><td class="col-md-1 col-xs-1">Total</td><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1">Prime</td><td class="col-md-1 col-xs-1">Débit</td><td class="col-md-1 col-xs-1">Crédit</td><td>Solde</td></tr></table>');
    $("#tabTotal").append('<tr><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1">'+prime+'</td><td class="col-md-1 col-xs-1">'+debit+'</td><td class="col-md-1 col-xs-1">'+credit+'</td><td class="col-md-1 col-xs-1">'+(credit - debit)+'</td></tr>');
};

function deleteResult(){
    var id = $.urlParam('id');
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : "id="+id+"&deleteResult=ok", // données à envoyer en  GET ou POST
        content: 'json',
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
            alert('enregistrment supprimé');
            $("#modalWindow").remove();
            $("#linkResult").remove();
            $('#nameValid').remove();
            $('#nameCancel').remove();
            $('#nameResult').remove();
            resultContainer.removeAttr("disabled");
            getResultList();
            $('#loaderOn').remove();
        },
        beforeSend : function() { // traitements JS à faire AVANT l'envoi
            resultContainer.after('<div class="row"><img src="'+srcLoader+'" id="loaderOn" class="col-md-offset-5 ">');
        }

    });
};

