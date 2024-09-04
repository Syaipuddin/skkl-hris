<script type="text/javascript">

$(document).ready(function(){

    $("#search_user").on("click", function(e)
    {
        $.ajax({
            url: '<?=base_url();?>master_data/user_admin/search_user',
            type: 'POST',
        })
        .done(function(html) {
            $('#myModal').html(html);
            $('#myModal').modal('show');
        });
    
    });


    
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
             dateFormat: 'mm/dd/yy',
             changeMonth: true,
             changeYear: true
         });
     });



// $('#nik').change(function(){
//     var nik = $('#nik').val();
//     var status = <?php echo $status ?>;
//     $.ajax({
//         type:"POST",
//         url: "<?=base_url();?>home/get_data_nik",
//         data: { nik : nik, status: status},
//         dataType: 'json', 
//         success: function(_data){
//             $('#nama').val(_data.Nama);
//             $('#unit').val(_data.Unit);
//             var barcode_nik = '<img src=<?php echo base_url();?>barcode/generate/' + _data.NIK + '>';
//             $('#img_barcode').empty();
//             $('#img_barcode').append(barcode_nik);
//         }

//     })

//     $.ajax({
//         type:"POST",
//         url: "<?=base_url();?>home/get_data_photo",
//         data: { nik : nik, status: status},
//         dataType: 'json', 
//         success: function(_data){
	
// 	if(_data.foto!=null)
// 	{
//             var photo = _data.foto.toUpperCase();
// 	}
// 	    var warna = _data.warna.toUpperCase();
// 	    $('#slc_warna').val(_data.warna);		
 
//             if(photo!=null)
//             {
//                 var change_path = photo.replace('P:','<?=PATH_FOTO_SISDM_JS?>');
//                 $('#img_photo').empty();
//                 $("#img_photo").append('<img id="foto" src='+ change_path +'  width="139px" height="159px">');
//             }
//             else
//             {
//                 $('#img_photo').empty();
//                 $("#img_photo").append('<img id="foto" src=<?=base_url();?>'+ _data.path_photo +'  width="139px" height="159px">');
//             }
//         }
//     })
// })

$('#nik').change(function(){
    var nik = $('#nik').val();
    var status = <?php echo $status ?>;
    $.ajax({
        type:"POST",
        url: "<?=base_url();?>home/get_data_nik_sap",
        data: { nik : nik, status: status},
        dataType: 'json', 
        success: function(_data){
            $('#nama').val(_data.NAMALENGKAP);
            $('#unit').val(_data.UNIT);
            var barcode_nik = '<img src=<?php echo base_url();?>barcode/generate/' + _data.NIK + '>';
            $('#img_barcode').empty();
            $('#img_barcode').append(barcode_nik);
        }

    })

    $.ajax({
        type:"POST",
        url: "<?=base_url();?>home/get_data_photo",
        data: { nik : nik, status: status},
        dataType: 'json', 
        success: function(_data){
    
    if(_data.foto!=null)
    {
            var photo = _data.foto.toUpperCase();
    }
        var warna = _data.warna.toUpperCase();
        $('#slc_warna').val(_data.warna);       
 
            if(photo!=null)
            {
                var change_path = photo.replace('P:','<?=PATH_FOTO_SISDM_JS?>');
                $('#img_photo').empty();
                $("#img_photo").append('<img id="foto" src='+ change_path +'  width="139px" height="159px">');
            }
            else
            {
                $('#img_photo').empty();
                $("#img_photo").append('<img id="foto" src=<?=base_url();?>'+ _data.path_photo +'  width="139px" height="159px">');
            }
        }
    })
})


});


</script>
