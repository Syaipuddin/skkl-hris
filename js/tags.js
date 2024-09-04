let _tag_input_suggestions_data = null;

/*
create a chainnable method for the script to
*/
$.fn.tagsValues = function (method /*, args*/) {
    //loop through all tags getting the attribute value
    var data=[];
    $(this).find(".data .tag .text").each(function (key,value) {
        let v=$(value).attr('_value');
        data.push(v);
    })

    return data;
};


/*
Handle click of the input area
 */
$('.tags-input').click(function () {
    $(this).find('input').focus();
});

/*
handle the click of close button on the tags
 */

$(document).on("click", ".tags-input .data .tag .close", function() {
    // whatever you do to delete this row
    
    let id = $(this).parents().eq(2).find('.autocomplete-items').data('type');
    let data_input = $('#data'+id+'-array').val();
    data_input = data_input.split(',');
    let data_parent = $(this).parent().text();
    data_parent = data_parent.substr(0, data_parent.length-1);
    for (var i = 0; i < data_input.length; i++) {
        if (data_input[i] == data_parent) {
            data_input.splice(i, 1);
        }
    }
    $('#data'+id+'-array').val(data_input);
    $(this).parent().remove();
})

/*
Handle the click of one suggestion
*/

$(document).on("click", ".tags-input .autocomplete-items div", function() {
    let index=$(this).index();
    let data=_tag_input_suggestions_data[index];
    // console.log($(this).parent());
    let id = $(this).parent().data('type');
    let data_holder = $(this).parents().eq(4).find('#data'+id);
    _add_input_tag(data_holder,data.id,data.name, id);
    $('.tags-input .autocomplete-items').html('');

})

/*
detect enter on the input
 */
/*$(".tags-input input").on( "keydown", function(event) {
    if(event.which == 13){
        let data = $(this).val()
        let id = $(this).data('type');
        if(data!="")_add_input_tag(this,data,data, id)
    }
});*/


$(".tags-input input").on( "focusout", function(event) {
    $(this).val("")
    var that = this;
    setTimeout(function(){ $(that).parents().eq(2).find('.autocomplete .autocomplete-items').html(""); }, 500);
});


function _add_input_tag(el,data,text, id=''){
    var data_array = new Array();
    let template="<span class=\"tag\"><span class=\"text\" _value='"+data+"'>"+text+"</span><span class=\"close\">&times;</span></span>\n";
    if (id != '') {
        //get data already add
        data_input = $('#data'+id+'-array').val();
        if (data_input != null && data_input != '')
        {
            data_input = data_input.split(',');
            for (var i = 0; i < data_input.length; i++) {
                data_array.push(data_input[i]);
            }
        }

        //push new data
        data_array.push(text);

        //check if data reach maximum length
        if (id == '1' || id == '2')
        {
            if (data_array.length <= 10)
            {
                status_length = 1;
            }
            else{
                status_length = 0;
            }
        }
        else if (id == '3')
        {
            if (data_array.length <= 5)
            {
                status_length = 1;
            }
            else{
                status_length = 0;
            }
        }

        if (status_length == 1)
        {
            $('#data'+id+'-array').val(data_array);
            $(el).parents().eq(2).find('#data'+id).append(template);
        }
        else{
            if (id == 1)
            {
                alert('Soft Skill must be less than or equal as 10');
            }
            else if (id == 2)
            {
                alert('Hard Skill must be less than or equal as 10');
            }
            else if (id == 3)
            {
                alert('Faculty / major must be less than or equal as 5');
            }
        }
    }
    else{
        $(el).parents().eq(2).find('.data').append(template);
    }
    $(el).val('')
}

$(".tags-input input").on( "keyup", function(event) {
    var query=$(this).val()

    if(event.which == 8) {
        if(query==""){
            console.log("Clearing suggestions");
            $('.tags-input .autocomplete-items').html('');
            return;
        }
    }

    $('.tags-input .autocomplete-items').html('');
    runSuggestions($(this),query)

});