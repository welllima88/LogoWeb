/**
 * Created by cedric on 15/01/2015.
 */

var storeContainer = $("#cib_bundle_databundle_telecollecte_store");
var mySelect = storeContainer.SumoSelect({
    placeholder:'Séléctionner magasin(s)',
    captionFormat: '{0} Séléctionné(s)'
});
var dateContainer = $("#cib_bundle_databundle_telecollecte_date");
var tabResultContainer = $("#tabResult");


$(document).ready(function() {
    var count = 0;
    //
    //mySelect = storeContainer.SumoSelect({placeholder:'Séléctionner magasin(s)'});
    //
    storeContainer.find("option").each(function(i,item){
        count = count + 1;
    });

    for(var i =0;i < count; i++)
        mySelect.sumo.selectItem(i);

    dateContainer.datepicker({
        dateFormat: 'dd-mm-yy',
        firstDay:0,
        dayNames: ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'],
        dayNamesMin: [ "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di" ],
        dayNamesShort: [ "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim" ],
        monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
        monthNamesShort: [ "Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec" ]
    });

    //$.selectTelecollecte();
    $.initTabResult();
    dateContainer.on('change',function(){
        $.initTabResult();
    });

    storeContainer.on('change',function(){
        $.initTabResult();
    });
});

$.selectTelecollecte = function(storeId,date,valueContainer){


    $.ajax({
        type : 'POST',
        url : $('form').prop('action'),
        data : {initRow : storeId},
        content: 'json',
        success: function(data){
            $.each(JSON.parse(data),function(j,test){
                if(j == 'store_name')
                    valueContainer.append('<td>'+test+'</td>');
            });
        },
        complete: function(){
            $.each(date, function (i, itemDate) {
                valueContainer.append('<td id="' + storeId + itemDate + '"></td>');
                $.fillCell($("#"+storeId+itemDate),storeId,itemDate);
            })
        }
    });

/*else
{
    $.ajax({
        type : 'POST',
        url : $('form').prop('action'),
        data : {store : storeId,date : date},
        content: 'json',
        success: function(data){
            $.each(JSON.parse(data),function(i,item){
                if(i == 'status' && item == 'true')
                    valueContainer.append('<td class="alert alert-success" id="' + storeId + date + '"></td>');
                else
                    valueContainer.append('<td class="alert alert-danger" id="' + storeId + date + '"></td>');

                if(i == 'path' && item)
                    $("#"+storeId + date).append('<a href="'+item+'">OK</a>');
                else
                    $("#"+storeId + date).append('<a href="#">KO</a>');
            });


        }
    })
}*/

};

$.fillCell = function(container,storeId,date){
    $.ajax({
        type : 'POST',
        url : $('form').prop('action'),
        data : {store : storeId,date : date},
        content: 'json',
        success: function(data){
            $.each(JSON.parse(data),function(i,item){
                //console.log(data);
                if(i == 'status' && item == 'true')
                    container.attr('class','alert alert-success');

                else if(i == 'status' && item == 'false'){
                    container.attr('class','alert alert-danger');
                    container.append('KO');
                }


                if(i == 'path')
                    container.append('<a href="'+item+'">OK</a>');
            });
        }
    })
};

$.initTabResult = function(){
    var stores = storeContainer.val();
    var arrayStore = [];
    var arrayDate = [];
    var date = null;
    if(dateContainer.val())
        date = new Date(dateContainer.val());
    else
        date = new Date();

    tabResultContainer.append(
        '<tr>' +
            '<td></td>' +
            '<td id="0000" class="date">'+(date.getDate()-5) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0001" class="date">'+(date.getDate()-4) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0002" class="date">'+(date.getDate()-3) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0003" class="date">'+(date.getDate()-2) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0004" class="date">'+(date.getDate()-1) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0005" class="date">'+(date.getDate()) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0006" class="date">'+(date.getDate()+1) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0007" class="date">'+(date.getDate()+2) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0008" class="date">'+(date.getDate()+3) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0009" class="date">'+(date.getDate()+4) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
            '<td id="0010" class="date">'+(date.getDate()+5) +'-'+(date.getMonth() + 1) +'-'+date.getFullYear()+'</td>' +
        '</tr>');
    var row = 0;
    var rowString = "";
    var column = 0;
    var columnString = 0;
    var dateColumn;
    var count = storeContainer.length;
    $.each($(".date"),function(i,item){
        arrayDate.push(item.innerHTML);
    });
    //console.log(arrayDate);
    //console.log(count);
    $.each(stores,function(i,item){
        tabResultContainer.append('<tr id="'+item+'"></tr>');
        $.selectTelecollecte(item,arrayDate,$("#"+item));
        //rowString = row.toString();
        //if(rowString.length == 1)
        //    rowString = '0'+rowString;
        ////console.log(rowString);
        //for(column = 0;column <= 10;column++){
        //    columnString = column.toString();
        //    if(columnString.length == 1)
        //        columnString = '0'+columnString;
        //    dateColumn = $("#"+rowString+columnString).text();
        //    //console.log(dateColumn);
        //
        //
        //    $.selectTelecollecte(item,dateColumn,$("#"+item),column);
        //}
        //row++;
        //$.selectTelecollecte(item,)
    });
};

$.initRow = function (storeId,valueContainer) {

    $.ajax({
        type : 'POST',
        url : $('form').prop('action'),
        data : {initRow : storeId},
        content: 'json',
        success: function(data){
            $.each(JSON.parse(data),function(j,test){
                if(j == 'store_name')
                    valueContainer.append('<td>'+test+'</td>');
            });


        }
    })

};