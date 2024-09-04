<?php
	$pageTitle =''; //setting title html
	include "template/top2.php";
	/*if ($menuFlag['admin'][7]==0){
		echo "<script>window.location='home.php'</script>";
	}*/ //bagian untuk mengecek hak akses halaman jika dibutuhkan
	//element array pertama digunakan untuk tipe user (admin, view, special)
	// element kedua digunakan untuk nomor modul
	// element ketiga (khusus "special") digunakan untuk nomor SpecialID (admin role)
	// isi array hanya 1 atau 0
	$sql= "select * from ms_module"; //Jika dibutuhkan untuk menquery isi table
	$rows = odbc_exec($conn, $sql);

?>
	<div class="row">
		
		<div class="span12">
			<h2>Title Halaman</h2>
			<a href="template_Popup.php?mode=new&keepThis=true&TB_iframe=true&height=300&width=650" class="thickbox btn btn-primary pull-right ">Add Something</a>
		</div>
		<div class="span12">			
			<table id="tb_view" class="table table table-striped table-bordered table-condensed">
			<thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><?php
			while ($row = odbc_fetch_object($rows)){
				echo '<tr>';
				echo '<td>'.$row->ModuleID.'</td>';
				echo '<td>'.$row->isActive.'</td>';
				echo '</tr>';
			}
			?>
			</tbody>
		</table>
		</div>
	</div>
<?php
	include "template/bottom2.php";

?>
<script type="text/javascript" charset="utf-8">
//	untuk menaktifkan datatable
	$(document).ready(function() { 
	  $('#tb_view').dataTable({
	  	"bDestroy":true,
	  	"sPaginationType": "full_numbers"
	  });
	});
	</script>