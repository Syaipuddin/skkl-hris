<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('DATA PESERTA','DATA PASANGAN', //1
				  'DATA ANAK 1', 'DATA ANAK 2', //3
				  'DATA ANAK 3', 'Pengisian data dibawah harus sesuai data Kartu Tanda Penduduk (KTP) / Kartu Keluarga (KK)', //5
				  'Data-data yang diisikan pada formulir ini hanya untuk keperluan pendaftaran BPJS Kesehatan tidak untuk keperluan perusahaan yang lain.', 'Apabila memerlukan informasi lebih lanjut terkait teknis pendaftaran BPJS Kesehatan, dapat menghubungi :',//7
				  'Pada hari kerja  :  Senin s/d Jumat, jam 08.00 - 17.00 WIB :', 'HR Unit masing-masing, Yaitu',//9
				  'DATA PESERTA', 'NO. KARTU KELUARGA',//11
				  'NO INDUK KEPENDUDUKAN', 'NAMA', //13
				  'P/I/S/A', 'TEMPAT LAHIR', //15
				  'TANGGAL LAHIR', 'JENIS KELAMIN', //17
				  'LAKI-LAKI', 'PEREMPUAN', //19
				  'STATUS KAWIN', 'BELUM KAWIN', //21
				  'KAWIN', 'CERAI', //23
				  'TELP/HP', 'EMAIL', //25
				  'ALAMAT', 'JALAN / KOMPLEK / DUSUN / NO. RUMAH', //27
				  'RT', 'RW', //29
				  'KODE POS', 'PROVINSI', //31
				  'LUAR NEGERI', 'DATI II', //33
				  '- PILIH -', 'KECAMATAN', //35
				  '- PILIH -', 'DESA', //37
				  'FASKSES PRIMER', 'Isikan kode Faskes Primer & Dokter Gigi ke dalam textbox yang sudah disediakan di bawah.', //39
				  'Cek Kode FASKES', 'FASKES PRIMER', //41
				  'DOKTER GIGI', 'UNIT KERJA', //43
				  '- PILIH -', 'NO. POKOK PEGAWAI', //45
				  'STATUS PEGAWAI', 'PEGAWAI TETAP', //47
				  'PEGAWAI KONTRAK', 'PEGAWAI PARUH WAKTU', //49
				  'KEWARGANEGARAAN', 'WNI', //51
				  'WNA', 'NO. ASURANSI LAIN', //53
				  'NAMA ASURANSI LAIN', 'ASURANSI LAINNYA', //55
				  'NPWP', 'NO. PASPOR', //57
				  'Karyawan wajib menonaktifkan kepesertaan BPJS Kesehatan tersebut', 'NO. BPJS KESEHATAN', //59
				  'LOKASI KERJA', ' - PILIH -', //61
				  'DATA PASANGAN', 'NO. INDUK KEPENDUDUKAN', //63
				  'Status Pengisian Form :', 'Karyawan dapat melakukan Update data', //65
				  'NAMA ', 'TEMPAT, TGL LAHIR', //67
				  'Isikan kode Faskes Primer & Dokter Gigi ke dalam textbox yang sudah disediakan di bawah. ', 'Cek Kode FASKES', //69
				  'FASKES PRIMER', 'DOKTER GIGI', //71
				  'NO. BPJS KESEHATAN', 'Data Pasangan', //73
				  'Data Anak 1', ' NO. INDUK KEPENDUDUKAN', //75
				  'Nama', 'TEMPAT, TGL LAHIR', //77
				  'Jenis Kelamin', 'PESERTA', //79
				  'FASKES PRIMER ISTRI/SUAMI', 'FASKES PRIMER ANAK PERTAMA', //81
				  'FASKES PRIMER', 'DOKTER GIGI', //83
				  'Isikan kode Faskes Primer & Dokter Gigi ke dalam textbox yang sudah disediakan di bawah.', 'Cek Kode FASKES', //85
				  'DATA ANAK 2', 'NO. INDUK KEPENDUDUKAN', //87
				  'NAME', 'NO. INDUK KEPENDUDUKAN', //89
				  'LAKI-LAKI', 'PEREMPUAN', //91
				  'LAKI-LAKI', 'PEREMPUAN', //93
				  'JENIS KELAMIN', 'FASKES PRIMER', //95
				  'FASKES PRIMER ANAK KEDUA', 'Isikan kode Faskes Primer & Dokter Gigi ke dalam textbox yang sudah disediakan di bawah.', //97
				  'Cek Kode FASKES', 'DOKTER GIGI', //99
				  'NO. BPJS KESEHATAN', 'DATA ANAK 3', //101
				  'NO. INDUK KEPENDUDUKAN', 'NAMA', //103
				  'TEMPAT, TENGGAL LAHIR', 'JENIS KELAMIN', //105
				  'LAKI-LAKI', 'PEREMPUAN', //107
				  'FASKES PRIMER ANAK KETIGA', 'Isikan kode Faskes Primer & Dokter Gigi ke dalam textbox yang sudah disediakan di bawah.', //109
				  'Cek Kode FASKES', 'FASKES PRIMER', //111
				  'DOKTER GIGI', 'NO. BPJS KESEHATAN', //113
				  'Ubah Data','Apakah anda yakin ingin mengubah data BPJS anda?',//115
				  'Silahkan tonton video untuk mengetahui cara mendapat kode faskes di web BPJS','Klik tombol untuk mengaktifkan form perubahan data karyawan',//117
				  'Klik tombol untuk mengaktifkan form perubahan data pasangan', 'Klik tombol untuk mengaktifkan form perubahan data anak ke 1', //119	
				  'Klik tombol untuk mengaktifkan form perubahan data anak ke 2', 'Klik tombol untuk mengaktifkan form perubahan data anak ke 3', //121
				  'Langkah-langkah untuk melihat kode faskes', '1. Kunjungi website Aplicares BPJS Kesehatan di alamat ', //123
				  '2. Klik Pencarian kemudian klik Wilayah','3. Pilih Provinsi, pilih Kabupaten/Kota,  dan pilih Jenis Faskes kemudian klik Cari Faskes', //125
				  'Jenis Faskes yang diperbolehkan : ', 'Faskes Primer : Puskesmas, Dokter Praktek Perorangan, Klinik Pratama, Klinik TNI, dan Klinik Polri.', //127
				  'Dokter Gigi : Dokter Gigi Praktik *Optional','4. Setelah menemukan Faskes yang kehendaki kemudian pilih Aksi', //129
				  ' 5. Catat kode faskes yang tertera, selanjutnya input  kode faskes ke kolom kode faskes yang ada HR Portal (kode saja)','Ya', //131
				  'Tidak','Isi nomor KK yang tertera pada KK', //133
				  'Pilih sesuai dengan status pernikahan','Isi sesuai dengan nomor kontak yang mudah dihubungi', //135
				   'Isi alamat surat Elektronik  (e-Mail) yang dimiliki dan aktif','Isi sesuai dengan alamat yang tertera pada KTP', //137
				   'Isi dengan no Rukun Tetangga (RT) yang tercatat pada KTP','Isi dengan no Rukun Warga (RW) yang tercatat pada KTP ', //139
				   'Isi sesuai dengan Kode Pos','Pilih sesuai dengan Provinsi', //141
				   'Isi dengan 16 digit No Induk Kependudukan yang tertera pada KTP atau KK','Isi sesuai dengan nama yang tertera di KTP', //143
				    'Pilih Peserta','Isi dengan nama Kota tempat kelahiran', //145
				    'Pilih sesuai dengan jenis kelamin','Telpon-Ext', //147
				    'Pendaftaraan BPJS Kesehatan', 'Belum Mengisi', //149
				    'Diterima CHR', 'Simpan Sementara', //151
				    'Proses di BPJS', 'Proses Berhasil (Kartu di HR Unit)', //153
				    'Tidak didaftarkan BPJS oleh KG', 'Pengumuman BPJS', //155
				    'Gelombang pendaftaran akan berakhir setiap tanggal 20 setiap bulannya', 'Karyawan dapat melewatkan (skip) sebanyak ', //157
				    'Karyawan wajib mengisi dan melengkapi data-data pendaftaran BPJS Kesehatan dibawah ini', 'DATA KELUARGA', //159
				    'istri/suami', 'NO. INDUK KEPENDUDUKAN ',//161
				    'TEMPAT TANGGAL LAHIR', 'ANAK PERTAMA ',//163
				    'TANGGAL LAHIR', 'ANAK KEDUA',//165
				    'ANAK KETIGA', 'Pilih SIMPAN SEMENTARA jika data belum lengkap',//167
				    'Pilih KIRIM KE CHR jika data sudah dilengkapi sesuai petunjuk', 'Dokter Gigi : Dokter Gigi Praktik *Optional',//169
				    'Kirim ke CHR', 'Isi dengan Tanggal, bulan dan tahun kelahiran',//171
				    'Isi Dengan 16 digit No Induk Kependudukan Yang tertera pada KTP atau KK', 'Isi nomor KK yang tertera pada KK',//173
				    'Wilayah Kab/Kota tempat kelahiran','Pilih sesuai dengan kewarganegaraan',//175
				    'Isi dengan nomor polis asuransi kesehatan (jika ada)','Pilih dengan nama asuransi lain (jika ada)', //177
				    'isi sesuai dengan NPWP','isi sesuai dengan nomor Paspor', //179
				    'Bagi karyawan yang sudah terdaftar dan tidak bermaksud memindahkan kepesertaan melalui Kompas Gramedia, wajib mengisi no BPJS kesehatan.','Pilih sesuai lokasi kerja anda berada', //181
				    'Pilih status pegawai sesuai dengan status kepegawaian','Isi sesuai unit kerja anda berada', //183
				    'Isi sesuai dengan Nomor Pokok Pegawai / Nomor Induk Karyawan','TELP/HP', //185

                                );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('DATA MEMBER','DATA PARTNER', //1
				  'DATA CHILDREN 1', 'DATA CHILDREN 2', //3
				  'DATA CHILDREN 3', 'Fulfillment data below must consistent with the Identity Card (KTP) / Family Card (KK)', //5
				  'The data which is filled in this form is just for BPJS Kesehatan it is not for another company necessity', 'If needs information furthermore related BPJS Kesehatan registration technical, please contact :', //7
				  'On Workdays from 08.00 Until 17.00 Western Indonesia Time : ', 'Each HR Unit : ',//9
				  'DATA MEMBER', 'FAMILY CARD NUMBER',//11
				  'ID CARD NUMBER', 'NAME', //13
				  'P/I/S/A', 'BIRTH PLACE', //15
				  'BIRTH DATE', 'GENDER', //17
				  'MALE', 'FEMALE', //19
				  'MARITAL STATUS', 'SINGLE', //21
				  'MARRIED', 'DIVORCE', //23
				  'TELP/CELL', 'EMAIL', //25
				  'ADDRESS', 'STREET / HOUSING ESTATE / VILLAGE / HOUSE NUMBER', //27
				  'RT', 'RW', //29
				  'POSTAL CODE', 'PROVINCE', //31
				  'OVERSEAS', 'REGENCY/CITY', //33
				  '- SELECT -', 'DISTRICT', //35
				  '- SELECT -', 'VILLAGE', //37
				  'HEALTH FACILITY PRIMARY', 'Fill in Primary Health Facility and Dentist code into the textbox which has provided below.', //39
				  'Check Health Facility Code', 'PRIMARY FASKES', //41
				  'DENTIST', 'WORK UNIT', //43
				  '- SELECT -', 'Employee ID Number', //45
				  'EMPLOYEE STATUS', 'PERMANENT EMPLOYEE', //47
				  'CONTRACT EMPLOYEE', 'PART TIME EMPLOYEE', //49
				  'CITIZENSHIP', 'INDONESIAN', //51
				  'FOREIGNER', 'OTHER INSURANCE NUMBER', //53
				  'OTHER INSURANCE', 'OTHER INSURANCE', //55
				  'TAX IDENTIFICATION NUMBER', 'PASSPORT NUMBER', //57
				  'Employee must deactivate that BPJS Kesehatan membership', 'BPJS KESEHATAN NUMBER', //59
				  'WORK LOCATION', '- SELECT -', //61
				  'DATA PARTNER', 'ID CARD NUMBER', //63
				  'Form Fill Status : ', 'Employee Can Update Data', //65
				  'NAME ', 'BIRTH PLACE, BIRTH DATE', //67
				  'Fill in Primary Health Facility and Dentist code into the textbox which has provided below.', 'Check Health Facility Code', //69
				  'PRIMARY FASKES', 'DENTIST', //71
				  'BPJS KESEHATAN NUMBER ', 'DATA PARTNER', //73
				  'CHILDREN DATA 1', 'ID CARD NUMBER', //75
				  'NAME', 'BIRTH PLACE, BIRTH DATE', //77
				  'GENDER', 'MEMBER', //79
				  'HEALTH FACILITY PRIMARY HUSBAND/WIFE', 'HEALTH FACILITY PRIMARY FOR FIRST CHILDREN', //81
				  'HEALTH FACILITY PRIMARY', 'DENTIST', //83
				  'Fill in Primary Health Facility and Dentist code into the textbox which has provided below.', 'Check Health Facility Code', //85
				  'CHILDREN DATA 2', 'ID CARD NUMBER', //87
				  'NAME', 'BIRTH PLACE, BIRTH DATE', //89
				  'MALE', 'FEMALE', //91
				  'MALE', 'FEMALE', //93
				  'GENDER', 'HEALTH FACILITY PRIMARY', //95
				  'HEALTH FACILITY PRIMARY SECOND CHILDREN', 'Fill in Primary Health Facility and Dentist code into the textbox which has provided below.', //97
				  'Check Health Facility Code', 'DENTIST', //99
				  'BPJS KESEHATAN NUMBER', 'CHILDREN DATA 3', //101
				  'ID CARD NUMBER', 'NAME', //103
				  'BIRTH PLACE, BIRTH DATE', 'GENDER', //105
				  'MALE', 'FEMALE', //107
				  'HEALTH FACILITY PRIMARY THIRD CHILDREN', 'Fill in Primary Health Facility and Dentist code into the textbox which has provided below.', //109
				  'Check Health Facility Code', 'HEALTH FACILITY PRIMARY', //111
				  'DENTIST', 'BPJS KESEHATAN NUMBER', //113
				  'Update Data','Are you sure you want to change your BPJS data?',//115
				  'Please watch this video to know how to get Health Facility code on web BPJS','
				  Click the button to activate the employee data change form',//117
				  'Click the button to activate the partner data change form', 'Click the button to activate the first child data change form', //119
				  'Click the button to activate the second children data change form', 'Click the button to activate the third child data change form', //121
				  'Step by step to check Health Facility Code', '1. Visit website Aplicares BPJS on this address ', //123
				  '2. Click pencarian and then click Wilayah','3. Choose Provinsi, choose Kabupaten/Kota, and choose Jenis Faskes and then click Cari Faskes', //125
				  '. Type of Health Facility which is allowed : ', 'Health Facility Primary : Puskesmas, Dokter Praktek Perorangan, Klinik Pratama, Klinik TNI, and Klinik Polri.', //127
				  'Dentist : Dentist *Optional','4. After found Health Facility that choosen and then choose Aksi', //129
				  ' 5. Keep Health Facility Code that appear, next input  Health Facility Code to column Health Facility Code that show on HR Portal (just code)','Yes', //131
				  'No','Fill KK number that listed on KK', //133
				  'Choose marriage status','Fill contact number that easy to contacted', //135
				  'Fill E-mail address that owned and active','Fill the valid address', //137
				  'Fill with house number (RT) that listed on ID','Fill with house number (RW) that listed on ID ', //139
				  'Fill with Postal Code','Fill with province', //141
				  'Fill with 16 digit Social Security Number that listed on ID','Name that listed on ID', //143
				  'Choose Member','Fill with name of city of birth', //145
				  'Choose gender','Telephone-Ext', //147
				  'BPJS Kesehatan Register', 'Not Filled Yet', //149
				  'Received by CHR', 'SAVE TEMPORARY', //151
				  'Process in BPJS', 'Process Success (Card in HR Unit)', //153
				  'BPJS is not registered by KG', 'BPJS Announcement', //155
				  'Phase of register will be end in every date 20 each month', 'Employee can skip as mush as ', //157
				  'Employee must fill and complete data of BPJS Kesehatan register below', 'DATA FAMILY', //159
				  'WIFE/HUSBAND', 'ID CARD NUMBER',//161
				  'BIRTH PLACE, BIRTH DATE', 'FIRST CHILD',//163
				  'BIRTH DATE', 'SECOND CHILD',//165
				  'THIRD CHILD', 'Select SAVE TEMPORARY if the data is not complete',//167
				  'Select SEND TO CHR if the data already equipped according to the instructions.' , 'Dentist: Optional Practical Dentist *Optional' ,//169
				  'SEND TO CHR', 'Fill with date, month and year of birth',//171
				  'Fill with 16 digit id card number (NIK) which listed in KTP or KK ', 'Fill in the Family card number (No. KK) which listed on the Family Card (KK)',//173
				  'Region city of birth','Choose the citizenship',//175
				  'Fill with polis health asurance number (if have)','Fill with other asurance name (if have)', //177
				  'fill with NPWP','fill with Paspor Number', //179
				  'For employee that registered and did not meant to transfer member using Kompas Gramedia, must fill BPJS Kesehatan number.','Choose your current work location', //181
				  'Choose employee status that match with status employee ','Fill that match as unit your current work location', //183
				  'Fill with your employee number(NIK)','PHONE NUMBER', //185



              );
	}
	
?>