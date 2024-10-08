<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
		define("header_title", 'Reimbursement Kesehatan - Rawat Jalan');
		define("header_title_hrs", 'Reimbursement Kesehatan - List Antrian Pengajuan');
		define("nik", 'NIK');
		define("nama", 'Nama');
		define("jumlah_ganti", 'Total Estimasi Jumlah Pergantian');
		define("info_jumlah_ganti", '( * Total biaya penggantian yang tercantum merupakan perhitungan estimasi dan dapat berbeda dengan jumlah yang akan ditransfer ke karyawan )');
		define("info_required", "( ** Wajib. Mohon isi kolom yang diwajibkan! Jika kolom memang kosong (tidak dibutuhkan), harap isi dengan Rp. 0,00 )");
		define("info_ongkos_transfer", '( ** Ongkos transfer tidak termasuk diterimakan )');
		define("sisa_pagu", 'Sisa Pagu');
		define("tgl_kwt", 'Tanggal Kwitansi');
		define("pasien", 'Pasien');
		define("attachment", 'Upload Dokumen');
		define("category", 'Pilih kategori sesuai jenis pemeriksaan yang dilakukan');
		define("diagnosa", 'Diagnosa Penyakit');
		define("biaya_dokter", 'Biaya Dokter / Klinik');
		define("jenis_dokter", 'Jenis Dokter / Klinik');
		define("dokter_spesialis", 'Dokter Spesialis');
		define("biaya_obat", 'Biaya Obat');
		define("obat", 'Obat');
		define("biaya_admin", 'Biaya Administrasi');
		define("estimasi_ganti", 'Total Biaya Penggantian');
		define("biaya_total", 'Total Biaya Kuitansi');
		define("biaya_ganti", 'Pagu Sudah Dipakai');
		define("pagu", 'Pagu');
		define("ongkos_transfer", 'Ongkos Transfer');
		define("header_title_mst_obat", 'Reimbursement Medis - Master Obat');
		define('total_dokter', '5');
		define('title_master_obat', 'Master Obat');
		define('nama_obat', 'Nama Obat');
		define('keterangan_obat', 'Keterangan Obat');
		define('status_obat', 'Status Obat');
		define('kategori_1', 'Dokter Umum/spesialist, Klinik, UGD, Bidan, dan Obat');
		define('kategori_1_val', 1);
		define('kategori_2', 'Dokter Mata, Gigi, Kacamata/Softlens dan Obat <br><span style="color:blue;">( Penggantian lensa dan frame tidak mengurangi pagu )</span>');
		define('kategori_2_val', 2);
		define('kategori_3', 'Diagnostik, Alat Bantu, dan Obat');
		define('kategori_3_val', 3);
		define('kategori_4', 'Imunisasi');
		define('kategori_4_val', 4);
		define('jumlah_dokter', 'Jumlah Dokter/Klinik');
		define('total_dokter_pilih', 3);
		define('kacamata', 'Kaca Mata');
		define('frame', 'Frame');
		define('lensa', 'Lensa / Softlens');
		define('biaya_pemeriksaan_awal', 'Biaya Pemeriksaan Awal Mata');
		define('right_eye', 'Mata Kanan');
		define('left_eye', 'Mata Kiri');
		define('panoramic', 'Panoramic');
		define('nama_imunisasi', 'Nama Imunisasi');
		define('jumlah_imunisasi', 'Jumlah Imunisasi');
		define('total_jumlah_imunisasi', 3);
		define('imunisasi', 'Imunisasi');
		define('biaya_imunisasi', 'Biaya Imunisasi');
		define('jenis_imunisasi', 'Jenis Imunisasi');
		define('hitung_penggantian', 'Hitung Estimasi Penggantian Sebelum Submit');
		define('bplan_obat', 1005);
		define('bplan_spesialis', 1002);
		define('bplan_imunisasi', 1006);
		define('bplan_ugd', 1016);
		define('bplan_klinik', 1014);
		define('barea_34', 34);
		define('barea_A1', 'A1');
		define('bplan_gigi', 2001);
		define('bplan_tindakan', 1020);
		define('bplan_frame', 5020);
		define('bplan_lensa', 5098);
		define('bplan_pem_awal', 1056);
		define('bplan_fisioterapi', 1029);
		define('bplan_laboratorium', 1004);
		define('bplan_radiologi', 1016);
		define('bplan_alat_bantu', 1010);
		define('bplan_biaya_admin', 1055);
		define('bplan_ongkos_transfer', 7002);
		define('sub_plan_panoramic', '0017');
		define('biaya_tindakan', 'Biaya Tindakan');
		define('alert_biaya_ganti_sisa_rawat', 'Pengajuan telah melewati sisa pagu.');
		define('alert_biaya_ganti_sisa_rawat_2', 'Jumlah nominal penggantian : Rp. ');
		define('alert_in_process', 'Mohon maaf, sisa pagu anda telah habis.');
		define('alert_tanggal_kwitansi', 'Tanggal Kwitansi sudah lewat dari 3 bulan takwim.');
		define('alert_tanggal_kwitansi_2', 'Tanggal Kwitansi tidak boleh lebih dari tanggal hari ini.');/*
		define('biaya_gigi', 150000);*/
		define('biaya_gigi', 250000);
		define('overclm', 3);
		define('stats', 'C');
		define('currc', 'IDR');
		define('ssh_user', 'hris');
		define('ssh_pass', 'hris2010');
		define('informasi', 'Persetujuan');
		define('isi_informasi', 'Dengan ini, saya menyatakan bahwa data yang dicantumkan adalah sesuai dengan bukti dokumen yang asli dan benar. Segala sesuatu yang ditimbulkan dari kelalaian dan/ atau kesengajaan terhadap penyimpangan tersebut dikenakan sanksi oleh Perusahaan dan tidak melepaskan Perusahaan untuk melakukan proses hukum lebih lanjut.');
		define('medrem_status', 'Medical Reimbursement Request Status');
		define('date_request', 'Tanggal Request');
		define('status', 'Status Proses');
		define('medrem_status_detail', 'Detail Status Pengajuan Medical Reimbursement ');
		define("bukti_dokumen", 'Bukti Dokumen');
		define('biaya_lab', 'Biaya Laboratorium');
		define('jenis_lab', 'Jenis Laboratorium');
		define('biaya_radiologi', 'Biaya Radiologi');
		define('jenis_radiologi', 'Jenis Radiologi');
		define('biaya_alat_bantu', 'Biaya Alat Bantu Medis');
		define('jenis_alat_bantu', 'Jenis Alat Bantu');
		define('biaya_fisioterapi', 'Biaya Fisioterapi');
		define('alert_pagu_habis', 'Mohon maaf sisa pagu rawat jalan anda sudah habis.');
		define('ketentuan_attach', '* Hanya bisa file *.jpg dan ukuran maksimal 1 file tidak lebih dari 500kb.<br>* Ukuran maksimal total file tidak lebih dari 1mb.<br>** Link untuk compress gambar, jika gambar lebih besar dari 500kb:');
		define('pilih_tanggal', '-- pilih tanggal --');
		define('pilih_pasien', '-- pilih pasien --');
		define('diterima', 'Diterima');
		define('ditolak', 'Ditolak');
		define('in_progress', 'Dalam Proses');
		define('download_label', 'Klik tombol di bawah untuk men-download');
		define('cara_download', "<h5 style='color: white;'>Cara download video: </h5><br><ol><li type='1' style='text-align: left;'>Klik tombol download di bawah</li><li type='1' style='text-align: left;'>Tunggu hingga otomatis pindah ke halaman download</li><li type='1' style='text-align: left;'>Klik tombol download di ujung kanan atas</li><img src='img/medrem/tutorial.jpg' style='width: 500px;'></img><li type='1' style='text-align: left;'>Pilih 'Direct download'</li><li type='1' style='text-align: left;'>Tunggu sampai download selesai</li></ol>");
		define('diagnosa_txt', '*Penentuan jenis penyakit oleh dokter berdasar gejala yang ada.');
		define('tindakan_txt', '*Biaya tindakan medis oleh dokter/paramedis (misal : jahit luka, membersihkan luka, insisi, dll).');
		define('tindakan_txt_2', '*Biaya tindakan medis oleh dokter mata/paramedis (misal : insisi, retinometri, refraksi, dll).');
		define('mata_kiri_txt', "*Ukuran lensa untuk mata kiri plus dan atau minus.");
		define('mata_kanan_txt', "*Ukuran lensa untuk mata kanan plus dan atau minus.");
		define('lab_txt', "*Biaya pemeriksaan laboratorium (misal : hematologi, serologi, urilanisa, dll).");
		define('radiologi_txt', "*Biaya pemeriksaan radiologi (misal : rongent, usg, ct scan, mri, dll).");
		define('fisioterapi_txt', "*Biaya tindakan fisioterapi (misal : tens, swd, mwd, dll).");
		define('alat_bantu_txt', "*Biaya pembelian alat bantu (misal : korset, kruk, pace maker, alat bantu dengar, dll).");
		define('kategori_1_txt', "*Untuk pengajuan pemeriksaan dokter umum/dokter spesialis, klinik/rumah sakit, bidan, serta pembelian obat di apotek atau rumah sakit.");
		define('kategori_2_txt', "*Untuk pengajuan pemeriksaan dokter mata, dokter gigi, pembelian kacamata/softlens, serta pembelian obat di apotek atau rumah sakit");
		define('kategori_3_txt', "*Untuk pengajuan pemeriksaan dokter umum/dokter spesialis, diagnostik (misal : laboratorium, radiologi mis. rongent, usg, ct scan, dll), pembelian alat bantu medis (misal : korset, kruk, dll), serta pembelian obat di apotek atau rumah sakit");
		define('kategori_4_txt', "*Untuk pengajuan pemeriksaan dokter umum/dokter spesialis,tindakan imunisasi (misal : dpt, polio, bcg, dll) serta pembelian obat di apotek atau rumah sakit");
		define('waktu', 'Waktu Input');

	}
	else{
		define("header_title", 'Medical Reimbursement - Outpatient');
		define("header_title_hrs", 'Medical Reimbursement - Queue List Submission');
		define("nik", 'NIK');
		define("nama", 'Name');
		define("jumlah_ganti", 'Estimation Total Reimbursement');
		define("info_jumlah_ganti", '( * Total cost of replacement listed are estimations and may be different with estimations amount to be transferred )');
		define("info_required", "( ** Required. Please fill the required field. If you do not need to fill the field, please just fill Rp. 0,00 )");
		define("info_ongkos_transfer", '( ** Transfer fee does not include receipt )');
		define("sisa_pagu", 'Pagu Balance');
		define("tgl_kwt", 'Receipt Date');
		define("pasien", 'Patient');
		define("attachment", 'Attachment File');
		define("category", 'Select a category according to the type of audit performed');
		define("diagnosa", 'Medical Diagnosis');
		define("biaya_dokter", 'Doctor Fee / Clinic');
		define("jenis_dokter", 'Doctor Type / Clinic');
		define("dokter_spesialis", 'Specialist Doctor');
		define("biaya_obat", 'Drugs Cost');
		define("obat", 'Drugs');
		define("biaya_admin", 'Administration Fee');
		define("estimasi_ganti", 'Total Cost of Replacement');
		define("biaya_total", 'Total Cost of Receipt');
		define("biaya_ganti", 'Pagu Balance Already Use');
		define("pagu", 'Pagu');
		define("ongkos_transfer", 'Transfer Fee');
		define("header_title_mst_obat", 'Medical Reimbursement - Medicine Master');
		define('total_dokter', '5');
		define('title_master_obat', 'Medicine Master');
		define('nama_obat', 'Medicine Name');
		define('keterangan_obat', 'Medicine Notes');
		define('status_obat', 'Medicine Status');
		define('kategori_1', 'General Practitoner/Medical Specialist, Clinic, Emergency, Tocologist, and Medicine');
		define('kategori_1_val', 1);
		define('kategori_2', 'Ophthalmologist, Dentist, Glasses/Softlens and Medicine <br><span style="color:blue;">( Replacement lenses and frames does not reduce pagu )</span>');
		define('kategori_2_val', 2);
		define('kategori_3', 'Diagnostic, Medical Tools and Medicine');
		define('kategori_3_val', 3);
		define('kategori_4', 'Immunization');
		define('kategori_4_val', 4);
		define('jumlah_dokter', 'Number of Doctors/Clinic');
		define('total_dokter_pilih', 3);
		define('kacamata', 'Glasses');
		define('frame', 'Frame');
		define('lensa', 'Lensa / Softlens');
		define('biaya_pemeriksaan_awal', 'Preliminary Eyes Examination Fee');
		define('right_eye', 'Right Eye');
		define('left_eye', 'Left Eye');
		define('panoramic', 'Panoramic');
		define('nama_imunisasi', 'Immunization Name');
		define('jumlah_imunisasi', 'Number of Immunization');
		define('total_jumlah_imunisasi', 3);
		define('imunisasi', 'Immunization');
		define('biaya_imunisasi', 'Immunization Cost');
		define('jenis_imunisasi', 'Immunization Type');
		define('hitung_penggantian', 'Calculate Estimated Replacement Before Submited');
		define('bplan_obat', 1005);
		define('bplan_spesialis', 1002);
		define('bplan_imunisasi', 1006);
		define('bplan_ugd', 1016);
		define('bplan_klinik', 1014);
		define('barea_34', 34);
		define('barea_A1', 'A1');
		define('bplan_gigi', 2001);
		define('bplan_tindakan', 1020);
		define('bplan_frame', 5020);
		define('bplan_lensa', 5098);
		define('bplan_pem_awal', 1056);
		define('bplan_fisioterapi', 1029);
		define('bplan_laboratorium', 1004);
		define('bplan_radiologi', 1016);
		define('bplan_alat_bantu', 1010);
		define('bplan_biaya_admin', 1055);
		define('bplan_ongkos_transfer', 7002);
		define('sub_plan_panoramic', '0017');
		define('biaya_tindakan', 'Medical Treatment Fee');
		define('alert_biaya_ganti_sisa_rawat', "Your request value has over the pagu balance.");
		define('alert_biaya_ganti_sisa_rawat_2', "Cost Reimbursement : Rp. ");
		define('alert_in_process', 'Sorry, your Pagu Balance has already empty.');
		define('alert_tanggal_kwitansi', 'Receipt date is past 3 months takwim.');
		define('alert_tanggal_kwitansi_2', 'Receipt date cannot over today.');
		/*define('biaya_gigi', 150000);*/
		define('biaya_gigi', 250000);
		define('overclm', 3);
		define('stats', 'C');
		define('currc', 'IDR');
		define('ssh_user', 'hris');
		define('ssh_pass', 'hris2010');
		define('informasi', 'Disclaimer');
		define('isi_informasi', "I hereby declare that the data listed is consistent with the original document's true proof. Anything resulting from the negligence and / or willfulness of such deviation shall be subject to sanction by the Company and / or not releasing the Company for further legal process.");
		define('date_request', 'Request Date');
		define('status', 'Process Status');
		define('medrem_status_detail', 'Medical Reimbursement Request Status Detail');
		define("bukti_dokumen", 'Document Evidence');
		define('biaya_lab', 'Laboratorium Fee');
		define('jenis_lab', 'Laboratorium Type');
		define('biaya_radiologi', 'Radiologi Fee');
		define('jenis_radiologi', 'Radiologi Type');
		define('biaya_alat_bantu', 'Medical Tools Fee');
		define('jenis_alat_bantu', 'Medical Tools Type');
		define('biaya_fisioterapi', 'Fisioteraphy Fee');
		define('alert_pagu_habis', 'We are sorry rest of pagu balance outpatient has been used up.');
		define('ketentuan_attach', '* Only jpg files and maximum size of 1 file permitted 500kb.<br>* Maximum size of total file permitted 1mb.<br>** Link for online image compression, if image size over 500kb:');
		define('pilih_tanggal', '-- pick date --');
		define('pilih_pasien', '-- select patient --');
		define('diterima', 'Approved');
		define('ditolak', 'Rejected');
		define('in_progress', 'In Process');
		define('download_label', 'Click the button below to download ');
		define('cara_download', "<h5 style='color: white;'>How to download video: </h5><br><ol><li type='1' style='text-align: left;'>Click the button below</li><li type='1' style='text-align: left;'>Wait until download page openned</li><li type='1' style='text-align: left;'>Click 'Download' on the right corner</li><img src='img/medrem/tutorial.jpg' style='width: 500px;'></img><li type='1' style='text-align: left;'>Choose 'Direct download'</li><li type='1' style='text-align: left;'>Wait until download finished</li></ol>");
		define('diagnosa_txt', '*Penentuan jenis penyakit oleh dokter berdasar gejala yang ada.');
		define('tindakan_txt', '*Biaya tindakan medis oleh dokter/paramedis (misal : jahit luka, membersihkan luka, insisi, dll).');
		define('tindakan_txt_2', '*Biaya tindakan medis oleh dokter mata/paramedis (misal : insisi, retinometri, refraksi, dll).');
		define('mata_kiri_txt', "*Ukuran lensa untuk mata kiri plus dan atau minus.");
		define('mata_kanan_txt', "*Ukuran lensa untuk mata kanan plus dan atau minus.");
		define('lab_txt', "*Biaya pemeriksaan laboratorium (misal : hematologi, serologi, urilanisa, dll).");
		define('radiologi_txt', "*Biaya pemeriksaan radiologi (misal : rongent, usg, ct scan, mri, dll).");
		define('fisioterapi_txt', "*Biaya tindakan fisioterapi (misal : tens, swd, mwd, dll).");
		define('alat_bantu_txt', "*Biaya pembelian alat bantu (misal : korset, kruk, pace maker, alat bantu dengar, dll).");
		define('kategori_1_txt', "*Untuk pengajuan pemeriksaan dokter umum/dokter spesialis, klinik/rumah sakit, bidan, serta pembelian obat di apotek atau rumah sakit.");
		define('kategori_2_txt', "*Untuk pengajuan pemeriksaan dokter mata, dokter gigi, pembelian kacamata/softlens, serta pembelian obat di apotek atau rumah sakit");
		define('kategori_3_txt', "*Untuk pengajuan pemeriksaan dokter umum/dokter spesialis, diagnostik (misal : laboratorium, radiologi mis. rongent, usg, ct scan, dll), pembelian alat bantu medis (misal : korset, kruk, dll), serta pembelian obat di apotek atau rumah sakit");
		define('kategori_4_txt', "*Untuk pengajuan pemeriksaan dokter umum/dokter spesialis,tindakan imunisasi (misal : dpt, polio, bcg, dll) serta pembelian obat di apotek atau rumah sakit");
		define('waktu', 'Time Input');
	}





?>
