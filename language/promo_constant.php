<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){

		define("title", 'Promo Barang Barter Karyawan');
		define("unit", 'Unit Usaha');	
		define("nama_barang", 'Nama Barang');	
		define("qty", 'Jumlah');	
		define("harga_normal", 'Harga Normal');
		define("harga_emp", 'Harga Karyawan');
		define("exp_date", 'Catatan');		
		define("desc", 'Deskripsi');	
		define("cp", 'Hubungi kami');	
		define("detail", 'Detail Promo Barang Barter');	
		define("category", 'Kategori');
		define("call_me",'Hubungi kami untuk harga');	
		define("notes",'Notes');	

	}
	else{
		
		define("title", 'Promo Items Barter Employees');
		define("unit", 'Business Unit');
		define("nama_barang", 'Name of items');	
		define("qty", 'Quantity');	
		define("harga_normal", 'Normal Price');	
		define("harga_emp", 'Employees Price');
		define("exp_date", 'Notes');		
		define("desc", 'Description');	
		define("cp", 'Contact Person');	
		define("detail", 'Detail Promo Barang Barter');
		define("category", 'Category');	
		define("call_me",'Call me for Price');
		define("notes",'Notes');	

	}
	
?>
