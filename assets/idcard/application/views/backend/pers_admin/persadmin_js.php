<script type="text/javascript">

$(document).ready(function(){
    
   function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#id_card_img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#id_image").change(function(){
        readURL(this);
    });

$(function() {
        $( "#date_expired" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    });

$('#persadmin_div').hide();

$('#nik').change(function(){
    var nik = $('#nik').val();
    $.ajax({
        type:"POST",
        url: "<?=base_url();?>home/get_data_nik",
        data: { nik : nik},
        dataType: 'json', 
        success: function(_data){
            $('#persadmin_div').show();
            $('#ddn_persadmin').hide();
            $('#nama').val(_data.Nama);
            $('#unit').val(_data.Unit);
            $('#persadmin').val(_data.PersAdmin);
            $('#is_sap').prop('disabled','disabled');
            $("#is_sap option[value='1']").attr('selected','selected');
            //$('#div_pass').hide();
            $("#hidden_id_sap").val(1);
            
            if(_data.Nama == null)
            {
              ///  $('#div_pass').show();
                $('#ddn_persadmin').show();
                $('#persadmin').hide();
                $('#password').attr('required', 'required');
                $("#is_sap option[value='0']").attr('selected','selected');
                $("#group_id option[value='2']").attr('selected','selected');
                $("#hidden_id_sap").val(0);
            }
        }
    })
})


});


</script>