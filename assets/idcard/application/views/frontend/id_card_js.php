<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">

$(document).ready(function()
{
    $("#search_user").on("click", function(e)
    {
        $.ajax
        ({
            url:'<?=base_url();?>master_data/user_admin/search_user',
            type:'POST',
        })
        .done(function(html) 
            {
            $('#myModal').html(html);
            $('#myModal').modal('show');
            });
    
    });

        <?php 
    
        if($this->session->userdata('persadmin')!='JP01')
            {
        ?>
            $( "#id_form_user" ).validate(
            {
            rules:{
            nik:{
            required: true,
            minlength: 6
                }
                    }
            });

        <?php
            }
        else{ ?>
        $( "#id_form_user" ).validate({
          rules: {
            nik: {
              required: true,
              minlength: 5
            }
          }
        });
        <?php } ?>
    
   function readURL(input) 
   {
        if (input.files && input.files[0]) 
            {
                var reader = new FileReader();
                
                reader.onload = function (e) 
                    {
                        $('#id_card_img').attr('src', e.target.result);
                    }
                reader.readAsDataURL(input.files[0]);
            }
    }
    
    $("#id_image").change(function()
        {
        readURL(this);
        });

 $(function() 
        {
         $( "#date_expired" ).datepicker
            ({
             dateFormat: 'mm/dd/yy',
             changeMonth: true,
             changeYear: true
            });
        });

/*$('#nik').change(function(){
    var nik = $('#nik').val();
    var status = <?php echo $status ?>;
    $.ajax({
        type:"POST",
        url: "<?=base_url();?>home/get_data_nik",
        data: { nik : nik, status: status},
        dataType: 'json', 
        success: function(_data){
            $('#nama').val(_data.Nama);
            $('#unit').val(_data.Unit);
            var barcode_nik = '<img src=<?php echo base_url();?>barcode/generate/' + _data.NIK + '>';
            $('#img_barcode').empty();
            $('#img_barcode').append(barcode_nik);
        }

    })
*/
$('#nik').change(function()///////////pas masukin
    {
    ///alert("jalan loh");
    var nik = $('#nik').val();
    var status = '<?php echo $status ?>';
    var nik_length = nik.length;
    //console.log(nik_length);
    if(nik_length==6)
        {
        $.ajax  ({
                type:"POST",
                url: "<?=base_url();?>home/get_data_nik_sap",
                data: { nik : nik, status: status},
                dataType: 'json', 
                success: function(_data)
                    {
                    $('#nama').val(_data.NAMALENGKAP);
                    $('#unit').val(_data.UNIT);
                    $('#title').val('');////////////////////////////////////////////isi ini

                    var barcode_nik = '<img src=<?php echo base_url();?>barcode/generate/' + _data.NIK + '>';
                    $('#img_barcode').empty();
                    $('#img_barcode').append(barcode_nik);
                    }

                })

        // $.ajax  ({
        //         type:"POST",
        //         url: "<?=base_url();?>home/get_data_tanggal_berlaku",
        //         data: { nik : nik, status: status},
        //         dataType: 'json', 
        //         success: function(_data)
        //             {
        //           ///////////////////////////////////////gak jelas  
        //             var barcode_nik = '<img src=<?php echo base_url();?>barcode/generate/' + _data.NIK + '>';
        //             $('#img_barcode').empty();
        //             $('#img_barcode').append(barcode_nik);
        //             }

        //         })

        $.ajax  ({
                type:"POST",
                url: "<?=base_url();?>home/get_pers_admin_nik",
                data: { nik : nik},
                dataType: 'json', 
                success: function(_data)
                    {
                       //console.log(_data);
                        $appendDropdownLogo = '';
                        _data.forEach(function(item){
                            $appendDropdownLogo += '<option value="'+item['Organization_name']+'">'+item['Organization_name']+'</option>';
                        });

                        $('#slc_logo').html($appendDropdownLogo);


                    }
        })

        $.ajax({
            type:"POST",
            url: "<?=base_url();?>home/get_data_photo",
            data: { nik : nik, status: status},
            dataType: 'json', 
            success: function(_data)
                {
                    if(_data.foto!=null)
                    {
                            var photo = _data.foto.toUpperCase();
                    }
                   // var warna = _data.warna.toUpperCase();
                   var warna = _data.warna;
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
        }
    })
    $('#btn__preview').click(function(){
        var nik = $('#nik').val();
        var name = $('#nama').val();
        var logo = $('#slc_logo').val();
        var title = $('#title').val();///sebelah nik

        $form_data = $('#id_form_user').serialize();


        $url = "<?php echo base_url().'idcard/generate_Idcard';?>";

        alert('Sblm ajax');
        $.ajax({
            url: $url,
            type: 'POST',
            data:{
                nik:nik,
                name:name,
                logo:logo
            },
        })
        .done(function(html){
            $('#imageReload').html(html); 
            $('#imageReload').modal('show');

            
        });
        
    });

});


</script>
