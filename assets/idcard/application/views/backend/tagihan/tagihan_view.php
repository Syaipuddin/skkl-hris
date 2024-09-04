<?php 
	// Charles
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
	<h1>Form Cetak Nota Tagihan <br />ID Card Online</h1>
	<ol class="breadcrumb">
	  <li class="active"><a href="<?php echo base_url() ?>">Home</a></li>
	</ol>


<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'id_search');
		echo form_open($action, $attributes);
			?>


  <div class="form-group">
    <label class="col-sm-3 control-label">Mulai dari</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="start_period" name="start_period" value="<?php if (isset($start_period)) echo $start_period; ?>" minlength="3" placeholder="Periode" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Sampai dengan</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="end_period" name="end_period" value="<?php if (isset($end_period)) echo $end_period; ?>" minlength="3" placeholder="Periode" required>
    </div>
  </div>
 <?php 
  if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE)
  {
  ?>
<div class="form-group">
  <label class="col-sm-3 control-label">Pers Admin</label>
    <div class="col-sm-6">
        <select class="form-control" id="slc_persadmin" name="slc_persadmin">
			<?php
                foreach($pers_admin_list as $row)
                {
					if(isset($slc_persadmin) and $row['persadmin']==$slc_persadmin){
                        echo '<option selected="selected" value="'.$row['persadmin'].'">'.$row['persadmin'].' | '.$row['keterangan'].'</option>';
					}else{
                        echo '<option value='.$row['persadmin'].'>'.$row['persadmin'].' | '.$row['keterangan'].'</option>';
					}
                }
			?>
        </select>
    </div>
</div>
<?php } ?>
<div class="form-group">
  <label class="col-sm-3 control-label">Status</label>
	<div class="col-sm-6">
		<select class="form-control" id="slc_status" name="slc_status" >
			<option value="" <?php if (isset($slc_status)) {if($slc_status == "") echo 'selected';} ?> >All</option>
			<option value="0" <?php if (isset($slc_status)) {if($slc_status == "0") echo 'selected';} ?> >Nota tagihan belum dicetak</option>
			<option value="1" <?php if (isset($slc_status)) {if($slc_status == "1") echo 'selected';} ?> >Nota tagihan sudah dicetak</option>
		</select>
	</div>
</div>

<div class="form-group">
  <label class="col-sm-3 control-label">Request Status</label>
    <div class="col-sm-6">
		<select class="form-control" id="slc_reqstatus" name="slc_reqstatus">
			<option value="" <?php if (isset($slc_reqstatus)) {if($slc_reqstatus == "") echo 'selected';} ?> >All</option>
			<option value="1" <?php if (isset($slc_reqstatus)) {if($slc_reqstatus == "1") echo 'selected';} ?> >New ID</option>
			<option value="2" <?php if (isset($slc_reqstatus)) {if($slc_reqstatus == "2") echo 'selected';} ?> >Extend ID</option>
			<option value="3" <?php if (isset($slc_reqstatus)) {if($slc_reqstatus == "3") echo 'selected';} ?> >Lost ID</option>
		</select>
	</div>
</div>

<div class="form-group">
    <div class="col-sm-offset-5 col-sm-2">
		<button type="submit" class="btn btn-primary">Search</button>
    </div>
</div>
<?php

echo form_close(); ?>
		 	
	<div id="div1"></div>
	<?php if(isset($total_card) && isset($total_card_kasir) && isset($total_card_selected_persadmin) && isset($total_card_selected_persadmin_kasir) && $this->session->userdata('nama_persadmin') != NULL){ ?>
	<div class="panel panel-default">
		<table style="text-align:center;width:100%;" class="table table-bordered">
			<tr style="background-color:blue;color:white;">
				<td width="35%" style="font-weight:bold;">Pers Admin</td>
				<td width="15%" style="font-weight:bold;">Jumlah Kartu</td>
				<td width="20%" style="font-weight:bold;">Biaya per Kartu</td>
				<td width="30%" style="font-weight:bold;">Total</td>
			</tr>
			<tr>
				<td>All KG</td>
				<td><?php if(!isset($total_card['row'])){ echo '0'; } else{echo $total_card['row'];} ?></td>
				<td><?php if(!isset($total_card['nominal_invoice'])){ echo '0'; } else{echo number_format($total_card['nominal_invoice'], 0, ",", ".");} ?></td>
				<td><?php if(!isset($total_card['jumlah'])){ echo '0'; } else{echo number_format($total_card['jumlah'], 0, ",", ".");} ?></td>
			</tr>
			<tr>
				<td>All KG (kasir)</td>
				<td><?php if(!isset($total_card_kasir['row'])){ echo '0'; } else{echo $total_card_kasir['row'];} ?></td>
				<td><?php if(!isset($total_card_kasir['nominal_invoice'])){ echo '0'; } else{echo number_format($total_card_kasir['nominal_invoice'], 0, ",", ".");} ?></td>
				<td><?php if(!isset($total_card_kasir['jumlah'])){ echo '0'; } else{echo number_format($total_card_kasir['jumlah'], 0, ",", ".");} ?></td>
			</tr>
			<tr>
				<td><?php if($this->session->userdata('nama_persadmin') != NULL){ echo $this->session->userdata('nama_persadmin'); } else{echo '-';} ?></td>
				<td><?php if(!isset($total_card_selected_persadmin['row'])){ echo '0'; } else{echo $total_card_selected_persadmin['row'];} ?></td>
				<td><?php if(!isset($total_card_selected_persadmin['nominal_invoice'])){ echo '0'; } else{echo number_format($total_card_selected_persadmin['nominal_invoice'], 0, ",", ".");} ?></td>
				<td><?php if(!isset($total_card_selected_persadmin['jumlah'])){ echo '0'; } else{echo number_format($total_card_selected_persadmin['jumlah'], 0, ",", ".");} ?></td>
			</tr>
			<tr>
				<td><?php if($this->session->userdata('nama_persadmin') != NULL){ echo $this->session->userdata('nama_persadmin').' (kasir)'; } else{echo '-';} ?></td>
				<td><?php if(!isset($total_card_selected_persadmin_kasir['row'])){ echo '0'; } else{echo $total_card_selected_persadmin_kasir['row'];} ?></td>
				<td><?php if(!isset($total_card_selected_persadmin_kasir['nominal_invoice'])){ echo '0'; } else{echo number_format($total_card_selected_persadmin_kasir['nominal_invoice'], 0, ",", ".");} ?></td>
				<td><?php if(!isset($total_card_selected_persadmin_kasir['jumlah'])){ echo '0'; } else{echo number_format($total_card_selected_persadmin_kasir['jumlah'], 0, ",", ".");} ?></td>
			</tr>
		</table>
	</div>
	<?php }
	
	if (isset($pers_admin) && isset($rp) && isset($terbilang)){ ?>
	<hr />
	<div style="text-align:right;">
		<div id="kotak_kiri" style="float:right;margin-left:20px;">
			<button id="print" class="btn btn-success">Print</button> 
		</div>
		<div id="kotak_kanan" style="float:right;display:none;">
			<a style="margin-right:10px;" href="../tagihanpdf/create_pdf/" ><img src="<?php echo base_url(); ?>/application/libraries/tcpdf/examples/images/idcard/pdf.png" />PDF</a>
			<a href="../tagihan/create_excel/<?php echo $slc_persadmin.'/'.$start_period.'/'.$end_period.'/'.$slc_status.'/'.$slc_reqstatus; ?>" ><img width="36" height="36" src="<?php echo base_url(); ?>/application/libraries/tcpdf/examples/images/idcard/excel.jpg" />EXCEL</a>
			
		</div>
		<div style="clear:both;">
			
	</div>
	</div>
	<hr />
	<?php } ?>
		<div class="table-responsive">
		  <table class="table table-hover">
		  	<thead>
		        <tr>
		          <th>NIK</th>
		          <th>Nama</th>
		          <th>Unit</th>
		          <th>Request</th>
		          <th>Tgl Cetak Kartu</th>
				  <th>Status Invoice</th>
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
						    default:
						        $status_nama = '<span class="label">Nothing</span>';
						        break;
						}

						echo '<tr>';			
						echo '<td>'.$row['nik'].'</td>';
						echo '<td>'.$row['nama'].'</td>';
						echo '<td>'.$row['unit'].'</td>';
						//echo '<td>'.$row['persadmin'].'</td>';

						echo '<td>'.$status_nama.'</td>';
						if(empty($row['tgl_cetak']))
						{
							echo '<td>Belum di Cetak</td>';
						}else{
							$tgl_cetak = date_create($row['tgl_cetak']);
							echo '<td>'.date_format($tgl_cetak, "d M Y").'</td>';
						}
						switch ($row['status_invoice']) {
						    case 0:
						        $status_invoice = '<span class="label label-warning">Belum Cetak</span>';
						        break;
						    case 1:
						        $status_invoice = '<span class="label label-primary">Sudah Cetak</span>';
						        break;
						}
						echo '<td>'.$status_invoice.'</td>';
						echo '</tr>';
				}

				?>
      		</tbody>
		  </table>
		  <div class="pagination"><?php if(!empty($links)){ echo $links; } ?></div>
		</div>
	</div>

	
