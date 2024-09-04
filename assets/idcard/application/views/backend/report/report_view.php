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
<div id="body_content">
	<h1>Report ID Card Online</h1>
	<ol class="breadcrumb">
	  <li class="active"><a href="<?php echo base_url() ?>">Home</a></li>
	</ol>


<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'id_search');
		echo form_open($action, $attributes);
			?>

  <div class="form-group">
    <label class="col-sm-2 control-label">Nama</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="txt_nama" name="txt_nama" minlength="3" placeholder="Search Nama">
    </div>
  </div>
<div class="form-group">
    <label class="col-sm-2 control-label">Periode Cetak</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="txt_period_start" name="txt_period_start" minlength="3" placeholder="Periode" required>
    </div> 
    <div class="col-sm-2">
      <input type="text" class="form-control" id="txt_period_end" name="txt_period_end" minlength="3" placeholder="Periode" required>
    </div>
  </div>
 <?php 
  if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE)
  {
  ?>
<div class="form-group">
  <label for="inputPassword3" class="col-sm-2 control-label">Persadmin</label>
    <div class="col-sm-5">
        <select class="form-control" id="slc_persadmin" name="slc_persadmin">
                <option value="">ALL</option>'
        <?php
                foreach($pers_admin_list as $row)
                {
                   if(isset($old->persadmin) and $row->persadmin==$old->persadmin){
                        echo '<option  selected="selected" value="'.$row->persadmin.'">'.$row->persadmin.'</option>';
                   }else{
                        echo '<option value="'.$row->persadmin.'">'.$row->persadmin.'</option>';
                   }
                 }
           ?>
         </select>
    </div>
  </div>
<?php } ?>
  <div class="form-group">
  <label for="inputPassword3" class="col-sm-2 control-label">Status</label>
    <div class="col-sm-10">
    <?php 
    	echo form_dropdown('id_cetak', $option_status, '','id="id_cetak" class="dropdown_box1"'); 
    ?>
	</div>
  </div>

 <div class="form-group">
  <label for="inputPassword3" class="col-sm-2 control-label">Request Status</label>
    <div class="col-sm-10">
    <?php 
    	echo form_dropdown('id_status_card', $option_status_card, '','id="id_status_card" class="dropdown_box1"'); 
    ?>
	</div>
  </div>


  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Search</button>
		
    </div>
  </div>
<?php echo form_close(); ?>
		 	
			
	
	
		<div class="table-responsive">
		  <table class="table table-hover">
		  	<thead>
		        <tr>
		          <th>Request Date</th>
		          <th>NIK</th>
		          <th>Nama</th>
		          <th>Unit</th>
          		  <th>PersAdmin</th>
		          <th>Photo</th>
		          <th>Status</th>
		          <th>Tanggal Cetak</th>
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
						echo '<td>'.$row['persadmin'].'</td>';

						
							if (strpos($row['path_photo'],':') == false) {
				    			echo '<td><a href="'.base_url().$row['path_photo'].'" class="fancybox-nonrefresh">
							<img src="'.base_url().$row['path_photo'].'" width="50" height="56" /></a></td>';	
				    		}
				    		else
				    		{
				    			$foto_nik = str_replace('P:', '', $row['foto']);
								echo '<td><a href="'.PATH_FOTO_SISDM.strtoupper($foto_nik).'" class="fancybox-nonrefresh"><img src="'.PATH_FOTO_SISDM.strtoupper($foto_nik).'" width="50" height="56" /></a></td>';
				    			
				    		}

							
						

						echo '<td>'.$status_nama.'</td>';
						if(empty($row['tgl_cetak']))
						{
							echo '<td>Belum di Cetak</td>';
						}else{
							echo '<td>'.$row['tgl_cetak'].'</td>';
						}
						echo '</tr>';
				}

				?>
      		</tbody>
		  </table>
		  <div class="pagination"><?php if(!empty($links)){ echo $links; } ?></div>
		</div>
	</div>

	
