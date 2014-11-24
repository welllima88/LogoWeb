/**
 * Created by cedric on 29/10/2014.
 */
var storeContainer = $("#cib_bundle_databundle_enclose_store");
var dateStopEncloseContainer = $("#cib_bundle_databundle_enclose_dateStopEnclose");
var encloseContainer = $("#tableEnclose");
var encloseTotalContainer = $("#tableTotal");
var url = encloseContainer.attr('class');
var rowResult = $("#resultId");
var mySelect = storeContainer.SumoSelect({placeholder:'Séléctionner magasin(s)'});
var modalContainer = $("#displayModal");
$(document).ready(function(){

    var count = 0;
    //mySelect = storeContainer.SumoSelect({placeholder:'Séléctionner magasin(s)'});
    //console.log(mySelect);
    storeContainer.find("option").each(function(){
       count = count + 1;
    });

    for(var i =0;i < count; i++)
        mySelect.sumo.selectItem(i);

    dateStopEncloseContainer.datepicker({
        dateFormat: 'dd-mm-yy',
        firstDay:0,
        dayNames: ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'],
        dayNamesMin: [ "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di" ],
        dayNamesShort: [ "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim" ],
        monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
        monthNamesShort: [ "Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec" ]
    });


    encloseContainer.append('<table id="resultId" class="table table-responsive"><tr class="titleTable"><td class="col-xs-1 col-md-1">CLOTURER</td><td class="col-xs-2 col-md-2">SITES</td><td class="col-xs-1 col-md-1">DEBIT</td><td class="col-xs-1 col-md-1">CREDIT</td><td class="col-xs-1 col-md-1">VIP</td><td class="col-xs-1 col-md-1">PRIME</td><td class="col-xs-1 col-md-1">SOLDES</td><td class="col-xs-1 col-md-1">HISTORIQUE</td><td class="col-xs-1 col-md-1">REEL</td><td class="col-xs-1 col-md-1">ANOMALIE</td></tr></table>');
    $.selectResults();
    encloseTotalContainer.append('<table id="totalId" class="table table-responsive"><tr class="titleTable"><td class="col-xs-1 col-md-1">CLOTURER</td><td class="col-xs-2 col-md-2"></td><td class="col-xs-1 col-md-1">TOTAL DEBIT</td><td class="col-xs-1 col-md-1">TOTAL CREDIT</td><td class="col-xs-1 col-md-1">TOTAL VIP</td><td class="col-xs-1 col-md-1">TOTAL PRIME</td><td class="col-xs-1 col-md-1">TOTAL SOLDES</td><td class="col-xs-1 col-md-1">TOTAL HISTORIQUE</td><td class="col-xs-1 col-md-1">TOTAL REEL</td><td class="col-xs-1 col-md-1"></td></tr></table>')
});

dateStopEncloseContainer.on('change',function(){
    $(".rowResult").remove();
    $(".rowTotal").remove();
    $.selectResults();
});

storeContainer.on('change', function () {
    $(".rowResult").remove();
    $(".rowTotal").remove();
    $.selectResults();
});

$.selectResults = function(){
    var date = dateStopEncloseContainer.val();
    var stores = storeContainer.val();
    var arrayStore = [];
    var count = 0;
    var totalDebit = 0;
    var totalCredit = 0;
    var totalVip = 0;
    var totalPrime = 0;
    var totalSolde = 0;
    var totalHistoric = 0;
    var totalReal = 0;
    var testo = 0;
    var link = "";
    $.each(stores,function(i,item){
       arrayStore.push(item);
        if(item != undefined && item != "")
        link = link + item +';';
    });
    $.ajax({
        type : 'POST',
        url : url,
        data : {arrayStore : arrayStore,date : date},
        content: 'json',
        success : function(data){

            $.each(JSON.parse(data),function(j,test){
                var debit = 0;
                var credit = 0;
                var vip = 0;
                var prime = 0;
                var balance = 0;
                var lastEnclose = 0;
                var real = 0;
                var idLastEnclose = null;
                var warningDebit = 0;
                var warningCredit = 0;
                var warningVip = 0;
                var warningPrime = 0;
                var warningBalance = 0;
                var isWarning = 0;

                console.log(test);
                if(test.amount_warning_debit != undefined)
                {
                    warningDebit = parseFloat(test.amount_warning_debit);
                    isWarning = 1;
                }

                if(test.amount_warning_credit != undefined)
                {
                    warningCredit = parseFloat(test.amount_warning_credit);
                    isWarning = 1;
                }

                if(test.amount_warning_vip != undefined)
                {
                    warningVip = parseFloat(test.amount_warning_vip);
                    isWarning = 1;
                }

                if(test.amount_warning_prime != undefined)
                {
                    warningPrime = parseFloat(test.amount_warning_prime);
                    isWarning = 1;
                }

                if(test.amount_warning_balance != undefined)
                {
                    warningBalance = parseFloat(test.amount_warning_balance);
                    isWarning = 1;
                }

                //alert(test.last_enclose);
                if(test.total_debit != undefined)
                    debit = parseFloat(test.total_debit);

                if(test.total_credit != undefined)
                    credit = parseFloat(test.total_credit);

                if(test.total_vip != undefined)
                    vip = parseFloat(test.total_vip);

                if(test.total_prime != undefined)
                    prime = parseFloat(test.total_prime);

                if(test.total_balance != undefined)
                    balance = parseFloat(test.total_balance);

                if(test.last_enclose != undefined)
                {
                    lastEnclose = parseFloat(test.last_enclose.total_balance);
                    idLastEnclose = test.last_enclose.enclose_id;
                }

                //console.log(debit);
                real = balance + lastEnclose;
                credit = credit - prime;
                balance = (credit + vip)-debit;
                vip = vip-prime;
                //console.log(debit);
                totalCredit = totalCredit + credit;
                totalDebit = totalDebit + debit;
                totalVip = totalVip + vip;
                totalPrime = totalPrime + prime;
                totalSolde = totalSolde + balance;
                totalHistoric = totalHistoric + lastEnclose;
                totalReal = totalReal + real;


                //$("#resultId").append('');
                if(isWarning == 1)
                {
                    $("#resultId").append('<tr class="row'+count+' rowResult"><td><button class="btn btn-primary btn-sm" id="'+test.store.store_id+'">CLOTURER</buttton> </td><td>'+test.store.store_name+'</td><td style="color: red">'+debit.toFixed(2)+' €</td><td style="color: cornflowerblue">'+credit.toFixed(2)+' €</td><td style="color: cornflowerblue">'+vip.toFixed(2)+' €</td><td style="color: cornflowerblue">'+prime.toFixed(2)+' €</td><td>'+balance.toFixed(2)+' €</td><td>'+lastEnclose.toFixed(2)+' €</td><td>'+real.toFixed(2)+' €</td><td><button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal'+test.store.store_id+'">ATTENTION</button> </td></tr>');
                    modalContainer.append('<div class="modal fade" id="modal'+test.store.store_id+'"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button><h4 class="modal-title">TRANSACTIONS ANTERIEURES A LA PRECEDENTE CLOTURE</h4></div><div class="modal-body"><table class="table table-bordered"><tr class="titleTable"><td class="col-xs-1 col-md-1">DEBIT</td><td class="col-xs-1 col-md-1">CREDIT</td><td class="col-xs-1 col-md-1">VIP</td><td class="col-xs-1 col-md-1"> PRIME</td><td class="col-xs-1 col-md-1">SOLDE</td></tr><tr class="row0"><td>'+warningDebit.toFixed(2)+' €</td><td>'+warningCredit.toFixed(2)+' €</td><td>'+warningVip.toFixed(2)+' €</td><td>'+warningPrime.toFixed(2)+' €</td><td>'+warningBalance.toFixed(2)+' €</td></tr></table></div></div></div></div>');

                }
                else
                    $("#resultId").append('<tr class="row'+count+' rowResult"><td><button class="btn btn-primary btn-sm" id="'+test.store.store_id+'">CLOTURER</buttton> </td><td>'+test.store.store_name+'</td><td style="color: red">'+debit.toFixed(2)+' €</td><td style="color: cornflowerblue">'+credit.toFixed(2)+' €</td><td style="color: cornflowerblue">'+vip.toFixed(2)+' €</td><td style="color: cornflowerblue">'+prime.toFixed(2)+' €</td><td>'+balance.toFixed(2)+' €</td><td>'+lastEnclose.toFixed(2)+' €</td><td>'+real.toFixed(2)+' €</td><td class="alert alert-success"> AUCUNE</td></tr>');
                $("#"+test.store.store_id).on('click', function(){
                    if(debit || credit || vip)
                    {
                        $( "#dialog" ).dialog({
                            dialogClass: "no-close",
                            modal: true,
                            buttons: [
                                {
                                    text: "OK",
                                    click: function() {
                                        $.encloseResults(test.store.store_id,debit,credit,vip,prime,balance,idLastEnclose,real,test.date_start_enclose,test.date_stop_enclose);
                                        $( this ).dialog( "close" );
                                    }
                                },
                                {
                                    text: "ANNULER",
                                    click: function(){
                                        $( this ).dialog( "close" );
                                    }
                                }

                            ]
                        });
                    }
                    else
                    {
                        $( "#dialogFail" ).dialog({
                            dialogClass: "no-close",
                            modal: true,
                            buttons: [
                                {
                                    text: "OK",
                                    click: function(){
                                        $( this ).dialog( "close" );
                                    }
                                }
                            ]
                        });
                    }

                });

                if(count == 1)
                    count = 0;
                else
                    count = count + 1;
            });
            totalCredit = totalCredit - totalPrime;
            totalSolde = (totalCredit + totalVip + totalPrime) - totalDebit;
            $("#totalId").append('<tr class="row0 rowTotal"><td><a href="#" class="btn btn-danger">CLOTURER</a></td><td></td><td style="color: red">'+totalDebit.toFixed(2)+' €</td><td style="color: #0088CC">'+totalCredit.toFixed(2)+' €</td><td style="color: #0088CC">'+totalVip.toFixed(2)+' €</td><td style="color: #0088CC">'+totalPrime.toFixed(2)+' €</td><td>'+totalSolde.toFixed(2)+' €</td><td>'+totalHistoric.toFixed(2)+' €</td><td>'+totalReal.toFixed(2)+' €</td><td></td></tr>')
            if(date == "")
                date=0;
            $("#linkExport").append('<a href="'+$("#linkExport").attr('class')+'/'+link+'/'+date+'" class="btn btn-primary">Exporter vers Excel</a>');
        }


    });

};

$.encloseResults = function(storeId,debit,credit,vip,prime,balance,historic,real,dateStart,dateStop){
    $.ajax({
        type : 'POST',
        url : url,
        data : {
            store : storeId,
            debit : debit,
            credit : credit,
            vip : vip,
            prime : prime,
            balance : balance,
            historic : historic,
            real : real,
            dateStart : dateStart,
            dateStop : dateStop,
            enclose : 'singleEnclose'
        },
        content : 'json',
        success : function(data){
            $(".rowResult").remove();
            $(".rowTotal").remove();
            $.selectResults();
        }
    });

};