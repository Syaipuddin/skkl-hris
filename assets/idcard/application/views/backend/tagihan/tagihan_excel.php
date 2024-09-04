<?php
$tanggal = $this->session->userdata('tanggal');

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Nota Tagihan Detail ".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table class="table table-hover">
	<thead>
		<tr>
		  <th>NIK</th>
		  <th>Nama</th>
		  <th>Unit</th>
		  <th>Request</th>
		  <th>Tgl Cetak Kartu</th>
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

				echo '<td>'.$status_nama.'</td>';
				if(empty($row['tgl_cetak']))
				{
					echo '<td>Belum di Cetak</td>';
				}else{
					$tgl_cetak = date_create($row['tgl_cetak']);
					echo '<td>'.date_format($tgl_cetak, "d M Y").'</td>';
				}
				echo '</tr>';
		}

		?>
	</tbody>
</table>