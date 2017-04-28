/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$.fn.serializeObject = function () {
    var o = {};
    var obj={};
    var loc=[];
    var quien=[];
    var dquien=[];
    var dque=[];
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            if(this.name=="_id"){
                if(this.value!==""){
                    var id={};
                    id["$oid"]=this.value || '';
                    o[this.name]=id;
                } 
            }else if(this.name.substring(0, 3)=="chk"){
                o[this.name] = "1";
            }else if(   this.name=="local"){
                loc[this.value]=obj;
                o['loc']=loc;
                obj={};
            }else if(   this.name=="dquienes"){
                dquien[this.value]=obj;
                o['dquien']=dquien;
                obj={};
            }else if(   this.name=="quienes"){
                quien[this.value]=obj;
                o['quien']=quien;
                obj={};
            } else if(   this.name=="dques"){
                dque[this.value]=obj;
                o['dque']=dque;
                obj={};
            }else if(   this.name.substring(0, 4)=="loc-"
                || this.name.substring(0, 6)=="quien-"
                || this.name.substring(0, 7)=="dquien-"
                || this.name.substring(0, 5)=="dque-"){
                obj[this.name]= this.value || '';
            }else if(   this.name=="date"){
                o[this.name]={"$date":new Date.parseExact(this.value,"d/M/yyyy").toISOString()};
            
            }else if(this.name.substring(0, 7)=="create_"){
                    if(this.value ==""){
                        if(this.name=="create_date"){
                            o[this.name]={"$date":new Date().toISOString()};
                        }else if(this.name=="create_user"){
                            o[this.name]= $('#user')[0].value || '';
                        }
                    }
                    else
                    {
                        if(this.name=="create_date"){
                            o[this.name]={"$date":this.value};
                        }
                        else{
                            o[this.name] = this.value || ''; 
                        }
                    }
                }
            else{
                o[this.name] = this.value || '';
            }
                    
        }
    });
    o["update_date"]={"$date":new Date().toISOString()}
    o["update_user"]= $('#user')[0].value;
    return o;
};

loadForm = function (data) {
    $.each(data[0], function (key, value) {
        if(key=="_id"){
            $('#Form').find('input[name="' + key + '"]').val(value.$oid)
        }else if(key.substring(0, 3)=="chk"){
            $('#Form').find('input[name="' + key + '"]').prop('checked', true);
        }else if(key=="loc" || key=="quien" || key=="dquien" || key=="dque"){
            $.each(value, function(k,val){
                var tr = $('#Form').find('input.' + key + '[value="'+k+'"]').parent();
                tr.children('td').children('select').each(function(){
                    this.value = val[this.name];
                });
                tr.children('td').children('input.text').each(function(){
                    this.value = val[this.name];
                });
            });
        }else if(key=="date"){
            $('#Form').find('input[name="' + key + '"]').val(parseISOString(value.$date).toString("dd/MM/yyyy"))
        }else if(key=="create_date"){
            $('#Form').find('input[name="' + key + '"]').val(value.$date);
        }else{
            $('#Form').find('input[name="' + key + '"]').val(value)
        };
        $('#Form').find('select[name="' + key + '"]').val(value);
        $('#Form').find('textarea[name="' + key + '"]').val(value);
        
    });
};
 
$('#Modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var identifier = button.data('identifier');
    var collection = button.data('collection');// Extract info from data-* attributes
    var action = button.data('action');
    
    if(action == "view"){
        $('#btn-submit').hide();
    } else if (action == "modify"){
        $('#btn-submit').text("Validar").show();
    // $('#btn-submit');
    } else if (action == "delete"){
        $('#btn-submit').text("Borrar").show();
    }   
        
    $('#Form').data('action',action);
    var query ={};
    query["_id"]={
        "$oid":identifier
    };
    getDocuments(collection, query, 0, 1, null, loadForm);
    
    if(collection == "piezas"){
            getDocuments("Informations", {"pid":identifier}, 0, 1000, null, function(data){
            $.each(data, function (key, value) {
                $('#fichas').append('<div>'+value.title+'</div>');
            });
        });
    }
});

closeModal = function (data){
    $('#Modal').modal('hide'); 
    $('#Form')[0].reset();
    $('input[name="_id"]').val(null);
    $('input[name="create_date"]').val(null);
    $('input[name="create_user"]').val(null);
    $('#labels').html("");
    $('#ambito').trigger('change');
        
    loadgrid();
    
};
      
$('#ambito').change(function(event){
    var val = this.value;
    
    switch(val){
        case "I":
            $('.internacional').show();
            $('.nacional').hide();
            $('.local').hide();
            break;
        case "N":
            $('.internacional').hide();
            $('.nacional').show();
            $('.local').hide();
            break;
        case "L":
            $('.internacional').hide();
            $('.nacional').hide();
            $('.local').show();
            break;
    }
})

 
getLabels = function(){
    $.ajax({
        url: 'https://api.mlab.com/api/1/databases/ocitv/collections/Informations?f={"tags":1}&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T',
        type: "GET",
        contentType: "application/json",
        success: function(data){
            $.each(data, function(key, val){
                var tg = data[key].tags;
                $.each(tg,function(k, v){
                    if($.inArray(v, availableTags) < 0){
                        availableTags.push(v);
                    }
                });
            });
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}

