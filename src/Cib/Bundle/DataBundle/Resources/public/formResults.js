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
var pagesContainer = $("#divPages");
var exportExcelContainer = $("#exportExcel");


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
    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url,null);
    getResultList();

    cardContainer.on('change',function(){
        if(this.value.length == 10)
            selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,this.value,clientContainer.value,storeContainer.value,url,null);
    });

    buttonReset.on('click',function(){
        monthContainer.val('');
        dateStartContainer.val('');
        dateStopContainer.val('');
        cardContainer.val('');
        clientContainer.val('');
        storeContainer.val('');

        selectResults(undefined,undefined,undefined,undefined,undefined,undefined,url,null);
    });

    cardContainer.keyup(function(){
        if(this.value.length >= 4)
            autoLoadCard(this.value,url);
    });

    clientContainer.keyup(function(){
        if(this.value.length >= 2)
            autoLoadClient(this.value,url);
    });

    dateStopContainer.on('change', function () {
        dateStartContainer.datepicker('option','maxDate',this.value);
        selectResults(monthContainer.value,dateStartContainer.value,this.value,cardContainer.value,clientContainer.value,storeContainer.value,url,null);
    });

    dateStartContainer.on('change',function(){
        dateStopContainer.datepicker('option','minDate',this.value);
        selectResults(monthContainer.value,this.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url,null);
    });

    monthContainer.on('change', function () {
        selectResults(this.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url,null);
    });

    storeContainer.on('change',function(){
        selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,this.value,url,null);
    });

    $(".deleteResult").on('click',function(){
        $.deleteResult(this.attr('id'));
    });

    resultContainer.on('click',function(){
        $('#inputResult').append('<input type="text" name="nameResult" placeholder="Entrez le nom de votre enregistrment" id="nameResult" style="width: 23%"><div class="row"><div class="col-xs-1 col-md-1"><button class="btn btn-success" id="nameValid">Valider</button></div><div class="col-xs-1 col-md-1"><button class="btn btn-danger" id="nameCancel">Annuler</button></div>');
        resultContainer.attr("disabled", "disabled");
        buttonReset.attr("disabled", "disabled");
        $("#modalWindow").attr("disabled","disabled");
        var buttonResultContainer = $('#nameValid');
        var buttonCancel = $('#nameCancel');
        var nameResultContainer = $('#nameResult');
        buttonResultContainer.on('click',function(){
            if(nameResultContainer.val())
            {
                saveResult(nameResultContainer.val())
            }
            else
                alert('Vous devez donner un nom à votre enregistrment');
        });
        buttonCancel.on('click',function(){
            buttonResultContainer.remove();
            buttonCancel.remove();
            nameResultContainer.remove();
            resultContainer.removeAttr("disabled");
            buttonReset.removeAttr("disabled");
            $("#modalWindow").removeAttr("disabled");
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
               suggestion.push(item.cardNumber);
            });
            cardContainer.autocomplete({
                source : suggestion,
                select : function(event, ui){
                    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,this.value,clientContainer.value,storeContainer.value,url,null);
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
                suggestion.push(item.clientName);
            });
            clientContainer.autocomplete({
                source : suggestion,
                select: function(event,ui){
                    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,this.value,storeContainer.value,url,null)
                }
            });
        }

    });
}

function selectResults(month,dateStart,dateStop,card,client,store,url,page){

    var totalCredit = 0;
    var totalDebit = 0;
    var totalPrime = 0;
    var totalVip = 0;
    var monthData;
    var start;
    var stop;
    var cardData;
    var clientData;
    var storeData;
    if(monthContainer.val())
        monthData = monthContainer.val();
    else
        monthData = 0;
    if(dateStartContainer.val())
        start = dateStartContainer.val();
    else
        start = 0;
    if(dateStopContainer.val())
        stop = dateStopContainer.val();
    else
        stop = 0;
    if(cardContainer.val())
        cardData = cardContainer.val();
    else
        cardData = 0;
    if(clientContainer.val())
        clientData = clientContainer.val();
    else
        clientData = 0;
    if(storeContainer.val())
        storeData = storeContainer.val();
    else
        storeData = 0;

    var dataToSend = 'month='+monthContainer.val()+'&start='+dateStartContainer.val()+'&stop='+dateStopContainer.val()+'&card='+cardContainer.val()+'&client='+clientContainer.val()+'&store='+storeContainer.val();
    if(page != null)
        dataToSend = dataToSend + '&page='+page;
    $.ajax({
        type : 'POST', // envoi des données en GET ou POST
        url : url , // url du fichier de traitement
        data : dataToSend + "&result=ok", // données à envoyer en  GET ou POST
        content: 'json',
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php

            $('#tabResultDebit').remove();
            $("#tabTotal").remove();

            tabResultContainer.append('<table id="tabResultDebit" class="table table-responsive"><tr class="titleTableLarge"><td colspan="7"><bold>TRANSACTIONS</bold></td></tr><tr class="titleTable"><td class="col-xs-1 col-md-1">DATE</td><td class="col-xs-1 col-md-1">NUMERO DE CARTE</td><td class="col-xs-1 col-md-1">VIP</td><td class="col-xs-1 col-md-1">PRIME</td><td class="col-xs-1 col-md-1">DEBIT</td><td class="col-xs-1 col-md-1">CREDIT</td><td class="col-xs-1 col-md-1">MAGASIN</td></tr></table>');
            var k = 0;

            var row = JSON.parse(data);
            //console.log(row);
            totalDebit = row.total.totalDebit;
            totalCredit = row.total.totalCredit;
            totalPrime = row.total.totalPrime;
            totalVip = row.total.totalVip;
            $.each(row.pagination.items, function(i, item) {
            console.log(item);
            var date = new Date(item.date_transaction);
                if(item.type_transaction == 'D')
                {
                    //totalDebit = totalDebit + parseFloat(item.amount_transaction);
                    var test = '<td class="col-md-1 col-xs-1">'+parseFloat(item.amount_transaction).toFixed(2)+' €</td><td class="col-md-1 col-xs-1"></td><td class="col-xs-1 col-md-1">'+item.store.store_name+'</td></tr>';
                }
                else
                {
                    //totalCredit = totalCredit + parseFloat(item.amount_transaction);
                    //totalPrime = totalPrime + parseFloat(item.prime_transaction);
                    var test = '<td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1">'+parseFloat(item.amount_transaction).toFixed(2)+' €</td><td class="col-xs-1 col-md-1">'+item.store.store_name+'</td></tr>';
                }
                if(item.is_vip_transaction == false)
                    item.is_vip_transaction = 'NON';
                else
                {
                    //totalVip = totalVip + parseFloat(item.amount_transaction);
                    item.is_vip_transaction = 'OUI';
                }


                $('#tabResultDebit').append('<tr class="row'+k+'"><td class="col-md-1 col-xs-1">'+date.getDate()+'/'+(date.getMonth() + 1) +'/'+date.getFullYear()+'</td><td class="col-md-1 col-xs-1">'+item.card.card_number+'</td><td class="col-md-1 col-xs-1">'+item.is_vip_transaction+'</td><td class="col-md-1 col-xs-1">'+parseFloat(item.prime_transaction).toFixed(2)+' €</td>'+test);

                if(k == 1)
                 k =0;
                else
                 k = k +1;
            });
                var range = row.pagination.page_range;
                var itemsPerPage = row.pagination.num_items_per_page;
                var totalItems = row.pagination.total_count;
                var currentPage = row.pagination.current_page_number;
                var totalPages = Math.round(Math.ceil(totalItems/itemsPerPage));
                $('.linkPages').remove();
                $.displayLinkPages(currentPage,totalPages,range,10);

                $.selectTotal(totalDebit,totalCredit,totalPrime,totalVip);
                $('#loaderOn').remove();

                exportExcelContainer.append('<center><a class="btn btn-primary" id="btnExportExcel" href="'+exportExcelContainer.attr('class')+'/'+monthData+'/'+start+'/'+stop+'/'+cardData+'/'+clientData+'/'+storeData+'">Exporter vers Excel</a></center>');
            },
        beforeSend : function() { // traitements JS à faire AVANT l'envoi
            $("#btnExportExcel").remove();
            tabResultContainer.after('<div class="row"><img src="'+srcLoader+'" id="loaderOn" class="col-md-offset-5 ">');
        }

    });
}

$.exportResult = function(data){
    $.ajax({
        type:'POST',
        url: exportExcelContainer.attr('class'),
        data: data,
        content: 'json',
        success : function(){
        }
    });
};

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
            buttonReset.removeAttr("disabled");
            $("#modalWindow").removeAttr("disabled");
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
                    $(".modal-body").append('<div id="linkResult"></div>');

                    $("#linkResult").append('<a href="#?month='+month+'&dateStart='+dateStart+'&dateStop='+dateStop+'&card='+card+'&store='+store+'&client='+client+'" onClick="refreshResult()">'+item.name+'</a><a id="delete'+item.result_id+'" href="#?id='+item.result_id+'" class="btn glyphicon glyphicon-remove-circle"></a><br>');
                    $("#delete"+item.result_id).on('click',function(){
                       deleteResult(item.result_id);
                    });
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
    var page;
    $("#modalList").modal('toggle');
    if($.urlParam('card') != 'null')
        cardContainer.val($.urlParam('card'));
    else
        cardContainer.val("");
    if($.urlParam('client') != 'null')
        clientContainer.val($.urlParam('client'));
    else
        clientContainer.val("");
    if($.urlParam('dateStart') != 'null')
    {
        var dateStart = new Date($.urlParam('dateStart'));
        dateStartContainer.val(dateStart.getDate()+'-'+(dateStart.getMonth()+1)+'-'+dateStart.getFullYear());
    }
    else
        dateStartContainer.val("");
    if($.urlParam('dateStop') != 'null')
    {
        var dateStop = new Date($.urlParam('dateStop'));
        dateStopContainer.val(dateStop.getDate()+'-'+(dateStop.getMonth()+1)+'-'+dateStop.getFullYear());
    }
    else
        dateStopContainer.val("");
    if($.urlParam('month') != 'null')
        monthContainer.val($.urlParam('month'));
    else
        monthContainer.val("");
    if($.urlParam('store') != 'null')
        storeContainer.val($.urlParam('store'));
    else
        storeContainer.val("");


    selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,clientContainer.value,storeContainer.value,url,page);
}

$.selectTotal = function(debit,credit,prime,vip){
    $("#divTotal").append('<table id="tabTotal" class="table table-responsive"><tr class="titleTable"><td class="col-md-1 col-xs-1">TOTAL</td><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1">VIP</td><td class="col-md-1 col-xs-1">PRIME</td><td class="col-md-1 col-xs-1">DEBIT</td><td class="col-md-1 col-xs-1">CREDIT</td><td class="col-xs-1 col-md-1">SOLDE</td></tr>');
    $("#tabTotal").append('<tr class="row0"><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1"></td><td class="col-md-1 col-xs-1">'+parseFloat(vip).toFixed(2)+' €</td><td class="col-md-1 col-xs-1">'+parseFloat(prime).toFixed(2)+' €</td><td class="col-md-1 col-xs-1">'+parseFloat(debit).toFixed(2)+' €</td><td class="col-md-1 col-xs-1">'+parseFloat(credit).toFixed(2)+' €</td><td class="col-md-1 col-xs-1">'+parseFloat(credit - debit).toFixed(2)+' €</td></tr></table>');
};

function deleteResult(id){
    $("#modalList").modal('toggle');
    //var id = $.urlParam('id');
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

$.displayLinkPages = function(page,totalPages,range,itemPerPage){

    var pages = 0;
    var pageCount = 0;

    if(totalPages < page)
        pages = page = totalPages;

    if(range > totalPages)
        range = totalPages;

    var delta = Math.ceil(range/2);

    if(page - delta > totalPages - range)
    {
        pageCount = _.range(totalPages - range + 1, totalPages + 1);
    }
    else
    {
        if(page-delta < 0)
            delta = page;

        var offset = page - delta;
        pageCount = _.range(offset+1,(offset+range)+1);
    }

    var proximity = Math.floor(range/2);

    var startPage = page-proximity;
    var stopPage =  page+proximity;

    if(startPage < 1){
        stopPage = Math.min(stopPage + (1 - startPage),totalPages);
        startPage = 1;
    }
    if (stopPage > totalPages) {
        startPage = Math.max(startPage - (stopPage - totalPages), 1);
        stopPage = totalPages;
    }

    //console.log(pageCount);
    $.each(pageCount,function(i,item){
        if(page == item)
            pagesContainer.append('<a href="#" id="page'+item+'" class="linkPages" style="text-decoration: underline">'+ item +'</a>&nbsp;');
        else
            pagesContainer.append('<a href="#" id="page'+item+'" class="linkPages">'+ item +'</a>&nbsp;');
        $("#page"+item).on('click',function(){
            selectResults(monthContainer.value,dateStartContainer.value,dateStopContainer.value,cardContainer.value,this.value,storeContainer.value,url,item)
        });
    });
};


