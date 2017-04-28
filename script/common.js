

$.fn.hour = function(){
    for (i = 0; i < 24; i++) {
        if(i<10){
            $(this).append('<option value=' + i + '>0' + i + '</option>');
        } else{
            $(this).append('<option value=' + i + '>' + i + '</option>');
        }
    }
};
$('.hour').hour();


$.fn.minute = function(){
    for (i = 0; i < 60; i++) {
        if(i<10){
            $(this).append('<option value=' + i + '>0' + i + '</option>');
        } else{
            $(this).append('<option value=' + i + '>' + i + '</option>');
        }
    }
};
$('.minute').minute();


$.fn.rating = function(){
    for (i = 1; i < 6; i++) {
        $(this).append('<option value=' + i + '>' + i + '</option>');
    }
};
$('.rating').rating();

$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });

$.fn.serializeSearchObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            
          if(this.value !==""){
              o[this.name] = this.value || '';       
          }
        }
    });
            
    return o;
};
        
postObject = function (collection, object, callback) {
 
    $.ajax({
        url: "https://api.mlab.com/api/1/databases/ocitv/collections/"+collection+"?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
        data: JSON.stringify(object),
        type: "POST",
        contentType: "application/json",
        success: callback,
        error: function (xhr, status, error) {
            alertm("Un error ha ocurrido, no ha sido posible guardar los datos","alert-danger");
        }
    });
};

countDocuments = function (collection, query, callback) {

    $.ajax({
        url: "https://api.mlab.com/api/1/databases/ocitv/collections/" + collection + "?c=true&q=" + JSON.stringify(query) + "&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
        type: "GET",
        contentType: "application/json",
        success: callback,
        error: function (xhr, status, error) {
            alertm(error);
        }
    });
};

getDocuments = function (collection, query, skip, limit, order, callback) {

    $.ajax({
        url: "https://api.mlab.com/api/1/databases/ocitv/collections/" + collection + "?sk=" + skip + "&l=" + limit + "&q=" + JSON.stringify(query) + "&s=" + JSON.stringify(order) + "&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
        type: "GET",
        contentType: "application/json",
        success: callback,
        error: function (xhr, status, error) {
            alertm(error);
        }
    });
};

deleteDocument = function (collection, id, callback){

    $.ajax({
        url: "https://api.mlab.com/api/1/databases/ocitv/collections/" + collection + "/" + id + "?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
        type: "DELETE",
        async: true,
        timeout: 300000,
        success: callback,
        error: function (xhr, status, error) {
            alertm("Un error ha ocurrido, no ha sido posible eliminar los datos","alert-danger");
        }
    });
};

alertm = function(message, alertclass){
    $('#message').empty();
    var elem = '<div class="alert '+alertclass+' alert-dismissible" role="alert" style="margin-top:10px;">';
    elem = elem + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    elem = elem + message + '</div>';
    $('#message').append(elem);
};

alertmm = function(message, alertclass){
    $('#messageModal').empty();
    var elem = '<div class="alert '+alertclass+' alert-dismissible" role="alert" style="margin-top:10px;">';
    elem = elem + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    elem = elem + message + '</div>';
    $('#messageModal').append(elem);
};

clean = function(obj) {
  for (var propName in obj) { 
    if (obj[propName] === null || obj[propName] === undefined || obj[propName] === "") {
      delete obj[propName];
    }
  }
};

// not used in tareas.js

var numPages;

execute = function(){   
    var json = $('#Form').serializeObject();
    var action = $('#Form').data('action');
    var collection = $('#Form').data('collection');
    
    if(action == "modify"){
        postObject(collection,json, function(){
            closeModal();
            alertm("Los datos han sido actualizados","alert-success");
        });
    } else if(action == "delete"){
        deleteDocument(collection, json._id.$oid, function(){
            closeModal();
            alertm("Los datos han sido borrados","alert-success");
        });
    }
    
    
};

loadCombo = function(comboName){
    $('select[name="' + comboName + '"]').html("");
    $('select[name="' + comboName + '"]').append('<option value=""></option>');
    $.each(combos[comboName], function(key, value){
        $('select[name="' + comboName + '"]').append('<option value=' + key + '>' + value + '</option>');
    })
};

loadComboNoNull = function(comboName){
    $('select[name="' + comboName + '"]').html("");
    $.each(combos[comboName], function(key, value){
        $('select[name="' + comboName + '"]').append('<option value=' + key + '>' + value + '</option>');
    })
};

loadCombobyClass = function(comboName){
    $('.'+comboName).html("");
    $.each(combos[comboName], function(key, value){
        $('.'+comboName).append('<option value=' + key + '>' + value + '</option>');
    })
};

getComboValue= function(combo, key){
    return combos[combo][key];
};

loadgrid = function(){
    getDocuments(collection, squery, page, limit, sorder,fillgrid);
    setPage();
} 

setPage = function(){
    $('#pagination').empty();
    countDocuments(collection,squery,function(data){
        numPages = Math.ceil(data/limit);
        for(var i=0;i<numPages;i++){ 
            var j =i+1;
            if(page == i){
                $('#pagination').append('<li class="active"><a href="#">'+ j +'</a></li>');
            }else{
                $('#pagination').append('<li><a href="#">'+ j +'</a></li>');
            }
        }
        $('ul.pagination > li').click(function(event){
            page= this.innerText-1;
            loadgrid();
        })
    });
}

parseISOString = function(s) {
  var b = s.split(/\D+/);
  return new Date(Date.UTC(b[0], --b[1], b[2], b[3], b[4], b[5], b[6]));
}

search = function(){
    squery = $('#searchForm').serializeSearchObject();
    loadgrid();
}

isChecked = function (value){
    if(value==1){
        return "checked";
    }
    
}

