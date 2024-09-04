<!-- //////*disini bagian yang view  edit bikin baru //////  //// -->
<?php 
	if (isset($notif_text)!='' AND $notif_type!='')
	{
		echo '<br>';
		echo '<div class="alert alert-block '.$notif_type.'" role="alert">';
		echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
';
		if(isset($notif_title) and $notif_title!=''){
			echo '<h4>'.$notif_title.'</h4>';
		}
		echo $notif_text;
		echo '</div>';
	}
?>

<div id="body_content">
	<h1>Welcome to ID Card Online!!! </h1><!-- ///*originalnya cuma ad 1 '!' -->
	
	<ol class="breadcrumb">
	  <li ><a href="<?php echo base_url() ?>">Home</a></li>
	  <li class="active"><a href="<?php echo base_url() ?>"><?=$title_id_card?></a></li>
	</ol>
	<div>
	
	<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'id_form_user');
		echo form_open_multipart($action, $attributes);
	$button = '<button type="button" class="btn btn-default" id="search_user" data-toggle="modal" data-target="#myModal">Search User</button>';
	if (isset($old->id_cardonline))
	{
		echo form_hidden('hidden_id_cardonline',$old->id_cardonline);
		echo form_hidden('hidden_path_photo',$old->path_photo);
		echo form_hidden('hidden_pers_admin',$old->persadmin);
		echo form_hidden('hidden_sap',$old->sap);
		echo form_hidden('hidden_status',$old->status);
		$button ='';
	}

?>	

	<!-- Modal -->
	<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	</div>

		<div class="row">
			<div class="col-xs-6">
				<form class="form-horizontal">
				  <div class="form-group">
				    <div class="col-sm-12 text-right">
				      <?php echo $button; ?>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="col-sm-2 control-label">NIK</label>
				    <div class="col-sm-10">
					<?php if($this->session->userdata('persadmin')!='JP01')
						{ ?>
				      <input type="text" class="form-control" id="nik" placeholder="nik" name="nik" minlength="6" maxlength="6" value="<?php echo isset($old->nik)?$old->nik:''?>" required>
					<?php }else{ ?>
					<input type="text" class="form-control" id="nik" placeholder="nik" name="nik" minlength="5" value="<?php echo isset($old->nik)?$old->nik:''?>" required>
					<?php } ?>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="col-sm-2 control-label">Nama</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="nama" name="nama" placeholder="nama" value="<?php echo isset($old->nama)?$old->nama:''?>" required>
				    </div>
				  </div>

				  <div class="form-group">
				    <label class="col-sm-2 control-label">Unit</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="unit" name="unit" placeholder="unit" value="<?php echo isset($old->unit)?$old->unit:''?>" required>
				    </div>
				  </div>


			<!-- 	///////////////////////////////////////////////// -->
			 <div class="form-group">
				    <label class="col-sm-2 control-label">Title</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="title" name="title" placeholder="title" value="<?php echo isset($old->title)?$old->title:''?>" >
				    </div>
				  </div>
			<!-- 	///////////////////////////////////////////////// -->

				 <!--  ////// *pemilihan logo pers admin -->
				

				   <div class="form-group">
				    <label class="col-sm-2 control-label">Pilih logo</label>
				    <div class="col-sm-10">
				        <select class="form-control" id="slc_logo" name="slc_logo"> <!-- ////////belum diganti -->
			           <?php 

			            foreach($pers as $row)
			            { 
			              		echo '<option value="'.$row['Organization_name'].'">'.$row['Organization_name'].'</option>';
			              		
			            }
			            ?>
			            </select>
				    </div>
				  </div>
				  <!-- ///// *end pemilihan logo pers admin -->

				  <div class="form-group">
				    <label class="col-sm-2 control-label">Warna</label>
				    <div class="col-sm-10">
				        <select class="form-control" id="slc_warna" name="slc_warna">
			            <?php 

			            foreach($type_warna as $row)
			            { 
			            	if(isset($old->id_warna) and $row->id_warna==$old->id_warna){
			              		echo '<option  selected="selected" value="'.$row->warna_type.'">'.$row->warna_text.'</option>';
			              	}else{
			              		echo '<option  selected='.'putih'.' value="'.$row->warna_type.'">'.$row->warna_text.'</option>';
			              		//echo '<option  selected='' value="'.$row->warna_type.'">'.$row->warna_text.'</option>';
			              	}
			            }
			            ?>
			            </select>
				    </div>
				  </div>

				  <?php
				  	if($status==1 or $status==2)
				  	{
				  	?>
					  <div class="form-group">
					    <label class="col-sm-2 control-label">Berlaku</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="date_expired" name="date_expired" value="<?php echo isset($old->berlaku)?$old->berlaku:''?>" required>
				<!--	      <font style="font-weight:bold;color:red">contoh isian : Berlaku s/d 12 Desember 2010</font>-->
					    </div>
					  </div>
				  <?php } ?>

				  <div class="form-group">
				    <label for="exampleInputFile" class="col-sm-2 control-label">NIK Barcode </label>
				    <div class="col-sm-10" id="img_barcode">
				    <?php
				    	if(isset($old->barcode)) 
				    	{
				    		
				    		echo '<img src="'.base_url().'barcode/generate/'.$old->barcode.'">';
				    	}
				    ?>
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="exampleInputFile" class="col-sm-2 control-label">Existing Image</label>
				    <div class="col-sm-10" id="img_photo">
				    <?php
				    	$message ='';
				    	if(isset($old->path_photo)) 
				    	{
				    		if (strpos($old->path_photo,'P:') === 0)
				    		{
							$foto_nik = strtoupper(str_replace('P:', '', $old->path_photo));
				    				echo '<td><a href="'.PATH_FOTO_SISDM.$foto_nik.'" class="fancybox-nonrefresh"><img src="'.PATH_FOTO_SISDM.strtoupper($foto_nik).'" width="139px" height="159px" /></a></td>';

				    				$message = PATH_FOTO_SISDM.strtoupper($foto_nik);
								
				    		}
				    		else
				    		{
				    			echo '<a href="'.base_url().$old->path_photo.'" class="fancybox-nonrefresh"><img src="'.base_url().$old->path_photo.'" width="139px" height="159px"  id="imgSrc"></a>';		
				    			
				    			$message =base_url().$old->path_photo;
								
				    		}
				    		
					    }
					    elseif(isset($old->foto)) 
					    {
					    	$foto_nik_2 = str_replace('P:', '', $old->foto);
								echo '<td><a href="'.PATH_FOTO_SISDM.$foto_nik_2.'" class="fancybox-nonrefresh"><img src="'.PATH_FOTO_SISDM.strtoupper($foto_nik_2).'" width="139px" height="159px" /></a></td>';
								
								$message = PATH_FOTO_SISDM.strtoupper($foto_nik_2);
								
					    }

				    ?>
				    </div>
				  </div>

				  <script type="text/javascript">
				  	
				  </script>

				  <div class="form-group">
				    <label for="exampleInputFile" class="col-sm-2 control-label">File input</label>
				    <div class="col-sm-10">
				    	<?php 
				    		echo form_upload('id_image',isset($image_path)?$image_path:'', 'id="id_image" class="input-small"'); 
				    		echo '<font style="font-weight:bold;color:red">(ukuran 2227 X 1704 pixel dan maximum size harus 1MB)</font> <img id="id_card_img" src="'.base_url().'assets/images/no_image.jpg" alt="image" width="139px" height="159px" />';	
				    		?>

				    
				    </div>
				  </div>

				  <div class="form-group">
				    <div  class="col-sm-2">
				    </div>
				    <div class="col-sm-10">
				    	<button type="submit" class="btn btn-primary">Save</button>
				    		<button type="button" class="btn btn-info"  id="btn__preview" name="btn__preview">Preview</button><!-- ///* buat tampilin -->
				    </div>
				  </div>

							
				  
				</form>
			</div>

			<div class="modal fade" id="imageReload" tabindex="-1" role="dialog" aria-hidden="true"> 

			</div>

			<?php  
				if($this->session->userdata('nik')=='001535'){
			?>	
				<!-- *************************EDIT************************* -->
			<div class="col-xs-6" float="left">
				<div class="form-group" style="margin-top: 15px">
				    <select class="form-control" name="imagicktype" id="imagicktype">
				        <option value="none">--Choose to Edit--</option>
				        <option value="transparent">Transparent</option>
				        <option value="level">Level Image</option>
				    </select>
				    <!--
				    <input type="text" style="display: none; margin-top: 15px" class="form-control" id="lowT" name="lowT" placeholder="Low Threshold" value="">
				    <input type="text" style="display: none; margin-top: 15px" class="form-control" id="highT" name="highT" placeholder="High Threshold" value="">
					-->
					
					<output style="display: none; margin-top: 15px;" id="amount" name="amount" for="lowT">1000</output>
					<input type="range" style="display: none; margin-top: 15px;" class="form-control" id="lowT" name="lowT" placeholder="Low Threshold" min="100" max="10000" value="1000" oninput="amount.value=lowT.value"/>
					
					<output style="display: none; margin-top: 15px;" id="amount2" name="amount2" for="highT">50000</output>
					<input type="range" style="display: none; margin-top: 15px;" class="form-control" id="highT" name="highT" placeholder="High Threshold" min="30000" max="65500" value="50000" oninput="amount2.value=highT.value"/>

					<output style="display: none; margin-top: 15px;" id="amount3" name="amount3" for="rangeT">2000</output>
					<input type="range" style="display: none; margin-top: 15px;" class="form-control" id="rangeT" name="rangeT" placeholder="Low Threshold" min="10" max="40000" value="2000" oninput="amount3.value=rangeT.value"/>
					
					
<!--
					<output style="display: none; margin-top: 15px;" id="amountx" name="amountx" for="rangeX">2000</output>
					<input type="range" style="display: none; margin-top: 15px;" class="form-control" id="rangeX" name="rangeX" placeholder="Range X" min="10" max="40000" value="2000" oninput="amountx.value=rangeX.value">
-->				
				    <img id="imgAfter" style="display: none; margin-top: 95px" src="" width="139px" height="159px">
				    
				    <!-- <button type="button" style="display: none; margin-top: 15px; width: 100px; height: 32px;" id="btnUpdate">Update</button> -->
				    <button type="button" id="modalid" class="btn btn-primary" data-toggle="modal" data-target="#myModala" style="display: none; margin-top: 30px; width: 100px; height: 32px;">Update</button>

				</div>
			</div>

			
			<!-- *************************EDIT************************* -->
			<?php	
				}
			?>

			

			<?php
			echo form_close();
			?>
  			<div class="col-xs-6">
  				<!-- <h4> Preview </h4> -->
  			</div>
  			
		</div>

		<div id="myModala" class="modal fade" role="dialog">
  						<div class="modal-dialog modal-sm">
    					<!-- Modal content-->
    						<div class="modal-content" >
      							<div class="modal-header">
        							<button style="margin-bottom: 5px;" type="button" class="close" data-dismiss="modal">&times;</button>
        							<h4 class="modal-title"></h4>
      							</div>
      							<div class="modal-body">
        							<p>Are you sure you want to Update this picture?</p>
      							</div>
      							<div class="modal-footer">
      								<button type="button" class="btn btn-secondary" data-dismiss="modal" style="color: white; background-color: red; float: right;">No</button>
      								<button type="button" class="btn btn-primary" data-dismiss="modal" id="btnUpdate" style="float: right; margin-right: 30px;">Yes</button>
        							
        							
      							</div>
    						</div>
  						</div>
			</div>
	</div>


<script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>


<script type="text/javascript">

	//$('#rangeMagick').validate();

	$(function(){
		$('#btnTest').click(function(e){
			e.preventDefault();
			var data = document.getElementById("imgSrc").src;
			var low = document.getElementById("lowT").value;
			var high = document.getElementById("highT").value;
          //  console.log("<?php  echo base_url(); ?>");
            $.ajax({
                type: "POST",
                url: "https://10.10.55.25/idcard/idcard/edit_image",
                data: {name: data, low: low, high: high},
                success: function(msg){
      	 		//alert(msg);
      	 		/*document.getElementById("imgAfter").style.display = "none";
      	 
    			document.getElementById("imgAfter").style.display = "block";*/
    			$("#imgAfter").attr('src',"https://10.10.55.25/idcard/./assets/images/foto/after/test.png"+'?' + new Date().getTime());
    			document.getElementById("imgAfter").style.display = "block";
    			}
            });
		});
	});


	$(function(){

		$('#imagicktype').change(function(){
			var choose = document.getElementById('imagicktype');
			var slect = choose.options[choose.selectedIndex].value;
			//console.log(slect);
			if (slect=="level") {
				document.getElementById("amount").style.display = "block";
				document.getElementById("amount2").style.display = "block";
				document.getElementById("lowT").style.display = "block";
				document.getElementById("highT").style.display = "block";

				document.getElementById("amount3").style.display = "none";
				document.getElementById("rangeT").style.display = "none";
				//document.getElementById("btnTest").style.display = "block";
			}else if (slect=="transparent") {
				document.getElementById("amount").style.display = "none";
				document.getElementById("amount2").style.display = "none";
				document.getElementById("lowT").style.display = "none";
				document.getElementById("highT").style.display = "none";

				document.getElementById("amount3").style.display = "block";
				document.getElementById("rangeT").style.display = "block";
				
				//document.getElementById("btnTest").style.display = "block";
			}else{
				document.getElementById("amount").style.display = "none";
				document.getElementById("amount2").style.display = "none";
				document.getElementById("lowT").style.display = "none";
				document.getElementById("highT").style.display = "none";
				//document.getElementById("btnTest").style.display = "none";

				document.getElementById("amount3").style.display = "none";
				document.getElementById("rangeT").style.display = "none";

				document.getElementById("imgAfter").style.display = "none";
			}

			 if(slect!="none"){
			 	var data = document.getElementById("imgSrc").src;
				var low = document.getElementById("lowT").value;
				var high = document.getElementById("highT").value;
				var r = document.getElementById("rangeT").value;
				//var extImg = document.getElementById("id_card_img").src;

	        	//console.log("<?php  echo base_url(); ?>");
	            $.ajax({
	                type: "POST",
	                url: "https://10.10.55.25/idcard/idcard/edit_image",
	                data: {name: data, low: low, high: high, slect: slect,r:r},
	                success: function(msg){
	      	 		//alert(msg);
	      	 	
	      	 		//document.getElementById("imgAfter").src = "https://10.10.55.25/idcard/./assets/images/foto/after/test.png";
	    		
	    			$("#imgAfter").attr('src',"https://10.10.55.25/idcard/./assets/images/foto/after/test.png"+'?' + new Date().getTime());
	    			document.getElementById("imgAfter").style.display = "block";
	    			document.getElementById("btnUpdate").style.display = "block";
	    			document.getElementById("modalid").style.display = "block";
	    			}
         	  	});    
			 }
		});

		$('#lowT').change(function(){
			var choose = document.getElementById('imagicktype');
			var slect = choose.options[choose.selectedIndex].value;

			var data = document.getElementById("imgSrc").src;
			var low = document.getElementById("lowT").value;
			var high = document.getElementById("highT").value;
			var r = document.getElementById("rangeT").value;
			//var extImg = document.getElementById("id_card_img").src;
        	//console.log("<?php  echo base_url(); ?>");
            $.ajax({
                type: "POST",
                url: "https://10.10.55.25/idcard/idcard/edit_image",
                data: {name: data, low: low, high: high, slect: slect,r:r},
                success: function(msg){
      	 		//alert(msg);
      	 		/*document.getElementById("imgAfter").style.display = "none";
      	 		document.getElementById("imgAfter").src = "";
      	 		document.getElementById("imgAfter").src = "https://10.10.55.25/idcard/./assets/images/foto/after/test.png";
    			document.getElementById("imgAfter").style.display = "block";*/
    			$("#imgAfter").attr('src',"https://10.10.55.25/idcard/./assets/images/foto/after/test.png"+'?' + new Date().getTime());
    			document.getElementById("imgAfter").style.display = "block";
    			}
           	});     
		});

		$('#highT').change(function(){
			var choose = document.getElementById('imagicktype');
			var slect = choose.options[choose.selectedIndex].value;

			var data = document.getElementById("imgSrc").src;
			var low = document.getElementById("lowT").value;
			var high = document.getElementById("highT").value;
			var r = document.getElementById("rangeT").value;
			var extImg = document.getElementById("id_card_img").src;
        	//console.log("<?php  echo base_url(); ?>");
            $.ajax({
                type: "POST",
                url: "https://10.10.55.25/idcard/idcard/edit_image",
                data: {name: data, low: low, high: high, slect: slect, r:r},
                success: function(msg){
      	 		//alert(msg);
      	 		/*document.getElementById("imgAfter").style.display = "none";
      	 		document.getElementById("imgAfter").src = "";
      	 		document.getElementById("imgAfter").src = "https://10.10.55.25/idcard/./assets/images/foto/after/test.png";
    			document.getElementById("imgAfter").style.display = "block";*/
    			$("#imgAfter").attr('src',"https://10.10.55.25/idcard/./assets/images/foto/after/test.png"+'?' + new Date().getTime());
    			document.getElementById("imgAfter").style.display = "block";
    			}
           	});     
		});

		$('#rangeT').change(function(){
			var choose = document.getElementById('imagicktype');
			var slect = choose.options[choose.selectedIndex].value;

			var data = document.getElementById("imgSrc").src;
			var low = document.getElementById("lowT").value;
			var high = document.getElementById("highT").value;
			var r = document.getElementById("rangeT").value;
			//var extImg = document.getElementById("id_card_img").src;
        	//console.log("<?php  echo base_url(); ?>");
            $.ajax({
                type: "POST",
                url: "https://10.10.55.25/idcard/idcard/edit_image",
                data: {name: data, low: low, high: high, slect: slect, r:r},
                success: function(msg){
               	//console.log(msg);
      	 		//alert(msg);
      	 		/*document.getElementById("imgAfter").style.display = "none";
      	 		document.getElementById("imgAfter").src = "";
      	 		document.getElementById("imgAfter").src = "https://10.10.55.25/idcard/./assets/images/foto/after/test.png";
    			document.getElementById("imgAfter").style.display = "block";*/
    			$("#imgAfter").attr('src',"https://10.10.55.25/idcard/./assets/images/foto/after/test.png"+'?' + new Date().getTime());
    			document.getElementById("imgAfter").style.display = "block";
    			}
           	});     
		});

		$('#btnUpdate').click(function(e){
			e.preventDefault();
			var nik = document.getElementById("imgSrc").src;
			var im = document.getElementById("imgAfter").src;
            $.ajax({
                type: "POST",
                url: "https://10.10.55.25/idcard/idcard/update_image",
                data: {nik:nik, im:im},
                success: function(msg){
      	 			//alert(msg);
      	 			$("#imgSrc").attr('src',"https://10.10.55.25/idcard/./assets/images/foto/"+msg+'?' + new Date().getTime());
    			}
            });  
          
		});
	});
</script>	
	
