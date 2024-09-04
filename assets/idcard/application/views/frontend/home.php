<?php 
	$notif_text = $this->session->flashdata('notif_text');
	$notif_type = $this->session->flashdata('notif_type');

	if (isset($notif_text)!='' AND $notif_type!='')
	{
		echo '<br><div class="alert alert-block '.$notif_type.'">';
		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
		if(isset($notif_title) and $notif_title!=''){
			echo '<h4>'.$notif_title.'</h4>';
		}
		echo $notif_text;
		echo '</div>';
	}
?>

<!-- UPDATE REJECT  -->
<script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
<script type="text/javascript">
		 function myFunction(nik, idtable) {
           	console.log(nik);
           	console.log(idtable);
           	document.getElementById("txtNIK").value = nik;
           	document.getElementById("txtIDCard").value = idtable;
         }
</script>

<!-- UPDATE REJECT  -->


<div id="body_content">
	<h1>Welcome to ID Card Online! </h1>
	<ol class="breadcrumb">
	  <li class="active"><a href="<?php echo base_url() ?>">Home</a></li>
	</ol>

	<div class="row">
		 <div class="col-xs-12 col-md-7">
			<?php
			$attributes = array('class' => 'form-inline', 'id' => 'id_search');
			echo form_open($action, $attributes);
			?>
			  <div class="form-group">
			    <label class="sr-only" for="exampleInputAmount">Search</label>
			    <div class="input-group">
			      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
			      <input type="text" class="form-control" id="txt_nama" name="txt_nama" minlength="3" placeholder="Search Nama">
			    </div>
			<?php 
			if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE)
                	{
			?>
				OR
				<div class="input-group">
					<select class="form-control" id="slc_persadmin" name="slc_persadmin">
                                    <?php

				    echo '<option value="">-- ALL --</option>';

                                    foreach($pers_admin_list as $row)
                                    {
                                                echo '<option value="'.$row->persadmin.'">'.$row->group_text.'</option>';
                                    }
                                    ?>
                                    </select>
			    	</div>
			<?php } ?>
			  </div>
			  <button type="submit" class="btn btn-primary">Search</button>
			<?php echo form_close(); ?>
		 </div>
		 <div class="col-xs-6 col-md-5">
		  <?php echo anchor($link['new_id_card'],'<i class="glyphicon glyphicon-plus"></i> New ID','class="btn btn-default"'); ?>
		  <?php echo anchor($link['extend_id_card'],'<i class="glyphicon glyphicon-repeat"></i> Extend ID','class="btn btn-default"'); ?>
		  <?php echo anchor($link['lost_id_card'],'<i class="glyphicon glyphicon-ban-circle"></i> Lost ID','class="btn btn-default"'); ?>
		  <?php echo anchor($link['broken_id_card'],'<i class="glyphicon glyphicon-scissors"></i> Broken ID','class="btn btn-default"'); ?>
		</div>
	</div>
	
	

	<div>
		<form method="post" id="formReject" name="formReject" action="https://10.10.55.25/idcard/home/reject_action">
		<div class="table-responsive">
		  <table class="table table-hover">
		  	<thead>
		        <tr>
		          <th>Request Date</th>
		          <th>NIK</th>
		          <th>Nama</th>
		          <th>Unit</th>
		<!--	  <th>HR NIK </th> -->
		          <th>Photo</th>
		          <th>Status</th>
		          <th>Action</th>
		          <?php 
		          if($this->session->userdata('nik')=='001535'){
		          	echo "<th>Reject</th>";
		      	} ?>
		        </tr>
		  	</thead>
      		<tbody>
      		<?php
				foreach ($request_data as $row) {
						
						switch ($row['status']) {
						    case 1:
						        $status_nama = '<span class="label label-default">New Request</span>';
						        break;
						    case 2:
						        $status_nama = '<span class="label label-info">Extend Request</span>';
						        break;
						    case 3:
						        $status_nama = '<span class="label label-warning">Lost Request</span>';
						        break;
						    case 4:
						        $status_nama = '<span class="label label-danger">Broken Request</span>';
						        break;
						    default:
						        $status_nama = '<span class="label">Nothing</span>';
						        break;
						}

						echo '<tr>';
						echo '<td>'.$row['tgl_request'].'</td>';				
						echo '<td>'.$row['nik'].'</td>';
						echo '<td>'.$row['nama'].'</td>';
						echo '<td>'.$row['unit'].'</td>';
					//	echo '<td>'.$row['hr_nik_request'].'</td>';

						
						// if(empty($row['path_photo']))
						// {
						// 	$foto_nik = str_replace('P:', '', $row['foto']);
						// 	echo '<td><a href="'.PATH_FOTO_SISDM.$foto_nik.'" class="fancybox-nonrefresh">
						// 	<img src="'.PATH_FOTO_SISDM.$foto_nik.'" width="100" height="116" /></a></td>';
						// }else
						// {
						// 	echo '<td><a href="'.base_url().$row['path_photo'].'" class="fancybox-nonrefresh">
						// 	<img src="'.base_url().$row['path_photo'].'" width="100" height="116" /></a></td>';
						// }

						
							if (strpos($row['path_photo'],':') == false) {
					    			echo '<td><a href="'.base_url().$row['path_photo'].'" class="fancybox-nonrefresh">
								<img src="'.base_url().$row['path_photo'].'" width="100" height="116" /></a></td>';
				    		}
				    		else
				    		{
							
				    			$foto_nik_path = strtoupper(str_replace('P:', '', $row['path_photo']));
							$foto_nik = strtoupper(str_replace("\\","/", $foto_nik_path));
								echo '<td><a href="'.PATH_FOTO_SISDM.$foto_nik.'" class="fancybox-nonrefresh">
								<img src="'.PATH_FOTO_SISDM.$foto_nik.'" width="100" height="116" /></a></td>';
				    			
				    		}

						
				    	//action yang akan ditambah
						echo '<td>'.$status_nama.'</td><td>';
						echo anchor($link['edit_new_idcard'].$row['id_cardonline'],'<i class="glyphicon glyphicon-pencil"></i>','class="btn fancybox" title="Edit"').' </td><td>';

						//REJECT BUTTON UNTUK MAS SONY

						if($this->session->userdata('nik')=='001535' && $row['hr_nik_request']!= null &&  $row['hr_nik_request']!="" ){
						/*echo anchor($link['reject_idcard'].$row['id_cardonline'],'<i class="glyphicon glyphicon-remove"></i>','class="btn fancybox" title="Reject"').' </td>';*/
							echo "<button type='button' onclick='myFunction(".'"'.$row['nik'].'"'.",".$row['id_cardonline'].")' data-toggle='modal' data-target='#myModala' data-backdrop='static' data-keyboard='false' class='btn' ><i class='glyphicon glyphicon-remove'></i></button>";
						}
						echo '</tr>';

				}

				?>
				<input type="hidden" name="txtNIK" id="txtNIK" />
				<input type="hidden" name="txtIDCard" id="txtIDCard" />
      		</tbody>
		  </table>
		  <div class="pagination"><?php if(!empty($links)){ echo $links; } ?></div>
		</div>
		</form>
		<div id="myModala" class="modal fade" role="dialog">
  						<div class="modal-dialog" style="width: 350px;">
    					<!-- Modal content-->
    						<div class="modal-content" >
      							<div class="modal-header">
        							<!-- <button style="margin-bottom: 5px;" type="button" class="close" data-dismiss="modal">&times;</button> -->
        							<h4 class="modal-title">Idcard Request Rejection</h4>
      							</div>
      							<div class="modal-body">
        							<p>Are you sure you want to reject this request?</p>
        							<!-- <input type="text" name="txtComment" id="txtComment"> -->
        							<textarea name="txtComment" id="txtComment" cols="40" rows="5" placeholder="Please give the reason for your rejection!"></textarea>
      							</div>

      							<div class="modal-footer">
      								<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnRejctNo" style="color: white; background-color: red; float: right; margin-right: 15px;">No</button>
      								<button type="button" class="btn btn-primary" data-dismiss="modal" id="btnReject" style="float: right; margin-right: 30px;">Yes</button>
        							
        							
      							</div>
    						</div>
  						</div>
			</div>

	</div>

<script type="text/javascript">
        $('#btnReject').click(function(){
            //$('#formReject').submit();
            var nik = document.getElementById("txtNIK").value;
           	var idTable = document.getElementById("txtIDCard").value;
           	var comment = document.getElementById("txtComment").value;
            $.ajax({
                type: "POST",
                url: "https://10.10.55.25/idcard/home/reject_action",
                data: {nik:nik, idTable:idTable, comment:comment},
                success: function(msg){
      	 			alert(msg);
      	 			
    			}
            });      
        });
        $('#btnRejctNo').click(function(){
        	document.getElementById("txtComment").value="";
        });
        
</script>

	
	
