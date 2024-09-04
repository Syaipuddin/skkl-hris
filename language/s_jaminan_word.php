<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Surat Jaminan Rumah Sakit','Nama', 'Tanggal Pengajuan', 'Tanggal Rawat', 
					'Rumah Sakit', 'Kelas', 'Ruangan', 'Email Rumah Sakit',
					'*CATATAN : Kelas dan ruangan tidak wajib diisi',
					'*CATATAN : Diisi jika ingin langsung di email ke Rumah Sakit',
					'Nama Pasien',
					'Alasan', 'Sakit', 'Melahirkan', 'Kirim',																			//RequestForm (0-14)

				  'Mohon pilih alasan sesuai dengan jenis kelamin pasien tertanggung',													//RequestFormErrMsg (15)

				  '', 'Pengajuan surat jaminan anda akan segera diproses', 'Tutup',														//RequestFormPopUp (16-18)

				  'Status Pengajuan Surat Jaminan',
				  'Belum divalidasi', 'Diterima', 'Ditolak',																			//RequestFormStatus (19-22)

				  'Cari', 'Keterangan',																									//History (23-24)

				  'Validasi Surat Jaminan Rumah Sakit', 'Pengajuan Surat Jaminan Rumah Sakit', 'Status Validasi',						//ApprovalStep1 (25-27)

				  'Validasi Pengajuan Surat Jaminan Rumah Sakit', 'Terima', 'Tolak',													//ApprovalStep2 (28-30)

				  'Validasi Surat Jaminan Rumah Sakit - Status Pending', 'Tanggal Validasi', 'Posisi', 'Tanda Tangan', 'Cap Perusahaan',//ApprovalStep3 (31-35)

				  'Validasi anda telah terkirim',																						//ApprovalPopUp (36)

				  'Mohon pilih nama yang menyetujui',																					//ErrMsg (37)

				  'Kop Surat',																											//MasterData (38)
				  'Mohon lampirkan ',																									// 39

				  'NIK telah ada di dalam database. Mohon gunakan NIK yang lain.',														// 40

				  'Mohon masukkan tanda tangan dan cap perusahaan',																		// 41

				  'Tidak',																												// 42

				  'Konfirmasi Pengiriman',																								// 43
				  'Apakah anda yakin ingin mengirim data?',																				// 44

				  'RS. (Nama Rumah Sakit) - Contoh : RS. Siloam',																		// 45

				  'Tanggal Ditolak',																									// 46

				  'Kantor',																												// 47

				  'Alamat Kantor',																										// 48

				  'No. Telp.',																											// 49

				  'Extension',																											// 50

				  'Fax.',																												// 51

				  'Tanda Tangan dan Cap Perusahaan',																					// 52

				  'Detail Kantor',																										// 53

				  'Lokasi Ruangan',																										// 54

				  'Data anda telah terkirim',																							// 55

				  'Pengajuan validasi telah ditolak',																					// 56
				  'Apakah anda yakin ingin menonaktifkan user ini?',
				  			  // 57	
				  'Iya',
				  			  // 58
				  'Konfirmasi non-aktif user',
				  			  // 59	
				  'Persetujuan',
				  			  // 60
				  'Saya mengerti maksud dan tujuan Surat Jaminan Rumah Sakit, serta setuju dan sepakat untuk menyerahkan hasil resume medis kepada pihak Perusahaan.',
				  			  // 61		
				  'Nama Organisasi',																							// 62
				  'Master Data sudah dinonaktifkan'
				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Hospital Guarantee Letter','Name', 'Request Date', 'Date&nbsp;of&nbsp;Hospital&nbsp;Admission', 
					'Hospital Name', 'Suite', 'Room', 'Hospital Email',
					'*NOTE : Class and Room are not required',
					'*NOTE : Used for sending email to Hospital automaticly',
					'Patient Name',
					'Reason', 'Illness', 'Maternity', 'Submit',																			//RequestForm (0-14)
				  "Please choose the reason correctly according to patient's gender",													//RequestFormErrMsg (15)

				  'Form Submission', 'Your request has been submitted',	'Close',														//RequestFormPopUp (16-18)

				  'Request Form Status', 
				  'Pending', 'Approved', 'Rejected',																					//RequestFormStatus (19-22)

				  'Search', 'Description',																								//History (23-24)

				  'Approval Hospital Guarantee Letter', 'Request Hospital Guarantee Letter', 'Approval',								//ApprovalStep1 (25-27)

				  'Hospital&nbsp;Guarantee&nbsp;Letter Request&nbsp;Approval',	'Approve', 'Reject',									//ApprovalStep2 (28-30)

				  'Hospital&nbsp;Guarantee&nbsp;Letter Pending&nbsp;Approval', 'Approval Date', 'Position', 'Signature', 'Stample',		//ApprovalStep3 (31-35)

				  'Your approval has been submitted',																					//ApprovalPopUp (36)

				  'Please select the approver name',																					//ErrMsg (37)

				  'Header',																												//MasterData (38)

				  'Please insert the ',																									// 39

				  'NIK has already exist in database. Please use another NIK.',															// 40

				  'Please insert signature and stample',																				// 41

				  'No',																												// 42

				  'Submit Confirmation',																								// 43
				  'Are you sure want to submit the following details?',																	// 44

				  'RS. (Hospital Name) - Example : RS. Siloam',																			// 45

				  'Rejection Date',																										// 46

				  'Office',																												// 47

				  'Office Address',																										// 48

				  'Telp.',																												// 49

				  'Extention',																											// 50

				  'Fax.',																												// 51

				  'Signature and Stamp',																								// 52

				  'Office Details',																										// 53

				  'Room Location',																										// 54

				  'Your data has been submitted',																						// 55

				  'The request has been rejected',																						// 56
				  'Are you sure want to deactivate this user?',
				  			  // 57
				  'Yes',
				  			  // 58
				  'Deactivation Confirmation',
				  			  // 59
				  'Disclaimer',
				  			  // 60
				  'I understood the aim and purpose of the Hospital Guarantee Letter that I ask for, and as the consequence I agreed to submit my medical resume to Kompas Gramedia.',	
				  			  // 61		
				  'Organization Name',																						// 62
				  'Master Data has been deactivated'
				 );
	}
?>