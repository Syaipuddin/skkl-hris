
<?php 
$notif_text=$this->session->flashdata('notif_text');
$notif_type=$this->session->flashdata('notif_type');
if (isset($notif_text)!='' AND $notif_type!='')
{
		echo '<br>';
		echo '<div class="alert '.$notif_type.'" role="alert">';
		echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		if(isset($notif_title) and $notif_title!=''){
			echo '<h4>'.$notif_title.'</h4>';
		}
		echo $notif_text;
		echo '</div>';
}
?>

<div id="body_content">
	<h1>Welcome to ID Card Online!</h1>
	<ol class="breadcrumb">
	  <li ><a href="<?php echo base_url() ?>">Home</a></li>
	  <li class="active"><a href="<?php echo base_url().'master_data/user_admin/' ?>"><?=$title_user?></a></li>
	</ol>
	<div>
	
	<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'id_form_user');
		echo form_open_multipart($action, $attributes);
		if (isset($old->id_user))
		{
			echo form_hidden('hidden_id_user',$old->id_user);
		}

	?>	

		<div class="row">
			<div class="col-xs-6">
				<form class="form-horizontal">
				  <div class="form-group">
				    <label class="col-sm-2 control-label">NIK</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="nik" placeholder="nik" name="nik" value="<?php echo isset($old->nik)?$old->nik:''?>" required>
				    </div>
				  </div>
				  <div class="form-group" id="div_pass">
				    <label class="col-sm-2 control-label">Password</label>
				    <div class="col-sm-10">
				      <input type="password" class="form-control" id="password" name="password" placeholder="password" value="<?php echo isset($old->password)?$old->password:''?>">
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
				  
				  <div class="form-group">
				    <label class="col-sm-2 control-label">Group</label>
				    <div class="col-sm-10">
				    <select name="group_id" id="group_id">
						<?php
							foreach ($group_list as $row) {	
								if(isset($old->id_group) and $row->id_group==$old->id_group){
									echo '<option selected="selected" value="'.$row->id_group.'">'.$row->role.'</option>';
								}else{
									echo '<option value="'.$row->id_group.'">'.$row->role.'</option>';
								}
							}
						?>
					</select>

				    </div>
				  </div>

				  <?php 
				  if($act=='add')
				  {
				  	$disabled='';
				  	
				  	?>
				  <div class="form-group" id="persadmin_div">
				    <label for="exampleInputFile" class="col-sm-2 control-label">PersAdmin</label>
				    <div class="col-sm-10">
				    	<select name="ddn_persadmin" id="ddn_persadmin">
						<?php
							foreach ($persadmin_list as $row) {	
								if(isset($old->persadmin) and $row->persadmin==$old->persadmin){
									echo '<option selected="selected" value="'.$row->persadmin.'">'.$row->persadmin.'</option>';
								}else{
									echo '<option value="'.$row->persadmin.'">'.$row->persadmin.'</option>';
								}
							}
						?>
					</select>
					<input type="text" class="form-control" id="persadmin" name="persadmin" placeholder="persadmin" value="<?php echo isset($old->persadmin)?$old->persadmin:''?>" readonly required>
				    </div>
				  </div>
				  <?php 
				  	}else
				  	{	
				  		?>
				  		<div class="form-group" >
				    <label for="exampleInputFile" class="col-sm-2 control-label">PersAdmin</label>
				    <div class="col-sm-10">
				    	<?php
				    	if($old->is_sap==0)
				  		{
				  			$disabled='';
						?>
				    	<select name="ddn_persadmin" id="ddn_persadmin">
						<?php
							foreach ($persadmin_list as $row) {	
								if(isset($old->persadmin) and $row->persadmin==$old->persadmin){
									echo '<option selected="selected" value="'.$row->persadmin.'">'.$row->persadmin.'</option>';
								}else{
									echo '<option value="'.$row->persadmin.'">'.$row->persadmin.'</option>';
								}
							}
						?>
						</select>
						<?php
						}else
				  		{
				  			$disabled='disabled';
				  		?>
						<input type="text" class="form-control" id="persadmin" name="persadmin" placeholder="persadmin" value="<?php echo isset($old->persadmin)?$old->persadmin:''?>" readonly required>
						<?php
						}
				  		?>
				    </div>
				  </div>
				  <?php	
					}
				  ?>
				  <div class="form-group">
				    <label for="exampleInputFile" class="col-sm-2 control-label">is SAP</label>
				    <div class="col-sm-10">
				    	<?php 
				    		echo form_dropdown('is_sap', $option_sap, OPT_SAP_VALUE,'id="is_sap" class="dropdown_box1"'.$disabled); 
				    	?>
				    	<input type="hidden" class="form-control" id="hidden_id_sap" name="hidden_id_sap" placeholder="id sap" value="<?php echo isset($old->is_sap)?$old->is_sap:''?>" required>
				    </div>
				  </div>


				  <div class="form-group">
				    <div  class="col-sm-2">
				    </div>
				    <div class="col-sm-10">
				    	<button type="submit" class="btn btn-primary">Save</button>
				    </div>
				  </div>
				  
				</form>
			</div>

			<?php
			echo form_close();
			?>
  			<div class="col-xs-6">
  				<!-- <h4> Preview </h4> -->
  			</div>
		</div>
	</div>

	