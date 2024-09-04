
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
	  <li class="active"><a href="<?php echo base_url().'master_data/pers_admin/' ?>"><?=$title_user?></a></li>
	</ol>
	<div>
	
	<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'id_form_persadmin');
		echo form_open_multipart($action, $attributes);
		if (isset($old->id_persadmin))
		{
			echo form_hidden('hidden_id_persadmin',$old->id_persadmin);
		}

	?>	

		<div class="row">
			<div class="col-xs-6">
				<form class="form-horizontal">
				  <div class="form-group">
				    <label class="col-sm-2 control-label">PersAdmin</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="persadmin" placeholder="persadmin" name="persadmin" value="<?php echo isset($old->persadmin)?$old->persadmin:''?>" required>
				    </div>
				  </div>
				  <div class="form-group" id="div_pass">
				    <label class="col-sm-2 control-label">Nama Unit Non SAP</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="unit_non_sap" name="unit_non_sap" placeholder="Nama Unit Non SAP" value="<?php echo isset($old->nama_unit)?$old->nama_unit:''?>">
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

	
