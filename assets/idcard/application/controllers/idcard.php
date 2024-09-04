<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//////////////////controller idcard
class Idcard extends CI_Controller 
{

	private $url_add;

	function __construct()
	{
		parent::__construct();
		
		if(!$this->session->userdata('loginFlag'))
		{	
			redirect('backend');
		}
		$this->url_add_process = URL_ADD_NIK_IDCARD;
		$this->url_extend_process = URL_EXTEND_NIK_IDCARD;
		$this->url_lost_process = URL_LOST_NIK_IDCARD;
		$this->url_broken_process = URL_BROKEN_NIK_IDCARD;
		$this->load->model('idcard_model');
		$this->load->model('user_model');
		$this->load->helper('form');
	        $this->load->helper('url');
    		$this->load->library('saprfc');
		$this->load->model('user_bapi');
	}

	public function add_idcard()
	{
		
		$data['nama']= $this->session->userdata('nama');
		$data['type_warna']  = $this->idcard_model->get_warna();
		$data['title_id_card']= TITLE_NEW_ID;
		
		$data['action'] =  URL_ADD_NIK_IDCARD_EXE;
		$data['status'] = 1;
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js');
	}/////

	public function add_idcard_process()
	{
		$data['title_id_card']= TITLE_NEW_ID.'add_idcard_process';
		$data['type_warna']  = $this->idcard_model->get_warna();
			
		$data['action'] =  URL_ADD_NIK_IDCARD_EXE;
		$nik = $this->input->post('nik');
		$data['status'] = 1;	
		$nama = $this->input->post('nama');
		$unit = $this->input->post('unit');
		$warna = $this->input->post('slc_warna');
		$tgl_berlaku = $this->input->post('date_expired');
		$hr_nik = $this->session->userdata('nik');
		$tgl_request = date('m/d/Y h:i:s');//////////////////////////
		$status = 1; // status 1 (new id card), 2 (extend idcard), 3 (lost idcard), 4 (broken idcard)
		$hr_nik_persadmin = $this->session->userdata('persadmin');
		$is_sap_hr = $this->session->userdata('is_sap');
		$count_nik_idcard=$this->user_model->check_user_idcard($nik);
		$check_persadmin=$this->user_bapi->get_pers_admin_emp($nik,date('Ymd'))->PERSADMIN;
		$count_user =count($check_persadmin);

		if($count_nik_idcard==0)
		{
			if($count_user==0)
			{
				$persadmin ='';		
				$is_sap_karyawan =0;
			}
			else
			{
				#$persadmin=$this->user_model->get_data_user_sap($nik)->PersAdmin;
				$persadmin=$this->user_bapi->get_pers_admin_emp($nik,date('Ymd'))->PERSADMIN;
				$is_sap_karyawan=1;
			}
			
			if($hr_nik_persadmin==$persadmin)
			{
			
				/* IMAGE MANIPULATE */
				$image = $_FILES['id_image'];
				$imagename = $image['name'];
				$fileExt = strtolower(array_pop(explode(".", $imagename)));
				$uploaded_imagename =strtolower($nik.".".$fileExt);

				$folder_name = UPL_FOTO_PATH;////////ini itu apa???
				
				$config['upload_path'] = $folder_name;
				$config['allowed_types'] = UPL_ALLOW_TYPE;
				$config['max_size'] = UPL_MAX_SIZE;
				$config['max_width']  = UPL_MAX_WIDTH;
		    		$config['max_height']  = UPL_MAX_HEIGHT;
		    		$config['file_name']  = $uploaded_imagename;
				$this->load->library('upload', $config);
				$file_image_path = $folder_name.'/'.$uploaded_imagename;
				$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
				
				if($this->upload->do_upload("id_image")!=true)
				{
					$error = $this->upload->display_errors();
					$data['notif_text']=$error;
					$data['notif_type']='alert-danger';
				}
				else
				{
					$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 'berlaku'=>$tgl_berlaku, 
						'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request, 
						'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
						'path_photo' => $file_image_path, 'sap' => $is_sap_karyawan, 'warna'=>$warna,
						'photo_path_app' => $foto_path_app, 'flag_proses' => 1);
					$this->idcard_model->add_idcard($arr_new_idcard);
					$this->session->set_flashdata('notif_type','alert-success');
					$this->session->set_flashdata('notif_text','Success Add ID Card');
					redirect(URL_HOME_LIST);	
				}
			}else{

				if($hr_nik_persadmin!=$persadmin && $is_sap_hr!=$is_sap_karyawan)
				{
					$data['notif_text']='Karyawan tersebut bukan karyawan PT anda.';
					$data['notif_type']='alert-danger';
				}elseif($is_sap_hr==$is_sap_karyawan && $hr_nik_persadmin!=$persadmin)
				{	
					if($persadmin=='' && $is_sap_hr==$is_sap_karyawan)
					{
						/* IMAGE MANIPULATE */
						$image = $_FILES['id_image'];
						$imagename = $image['name'];
						$fileExt = strtolower(array_pop(explode(".", $imagename)));
						$uploaded_imagename =strtolower($nik.".".$fileExt);

						$folder_name = UPL_FOTO_PATH;
						$config['upload_path'] = $folder_name;
						$config['allowed_types'] = UPL_ALLOW_TYPE;
						$config['max_size'] = UPL_MAX_SIZE;
						$config['max_width']  = UPL_MAX_WIDTH;
			    			$config['max_height']  = UPL_MAX_HEIGHT;
			    			$config['file_name']  = $uploaded_imagename;
						$this->load->library('upload', $config);
						$file_image_path = $folder_name.'/'.$uploaded_imagename;
						$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
						
						if($this->upload->do_upload("id_image")!=true)
						{
							$error = $this->upload->display_errors();
							$data['notif_text']=$error;
							$data['notif_type']='alert-danger';
						}
						else
						{
							$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
								'berlaku'=>$tgl_berlaku, 'nama' => $nama, 'unit' => $unit, 
								'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
								'status' => $status, 'persadmin' => $hr_nik_persadmin, 
								'path_photo' => $file_image_path, 'sap' => $is_sap_karyawan, 'warna'=>$warna,
								'photo_path_app' => $foto_path_app, 'flag_proses' => 1);
							$this->idcard_model->add_idcard($arr_new_idcard);
							$this->session->set_flashdata('notif_type','alert-success');
							$this->session->set_flashdata('notif_text','Success Add ID Card');
							redirect(URL_HOME_LIST);	
						}
					}else
					{
						$data['notif_text']='Karyawan tersebut bukan karyawan PT anda.';
	                                	$data['notif_type']='alert-danger';	
					}	
				}
			}
		}else
		{
			$data['notif_text']='NIK tersebut bukan karyawan baru.';
			$data['notif_type']='alert-danger';	
		}

		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js');
	}


	public function edit_idcard($id)/////////////////////
	{
		$data['old']=$this->idcard_model->get_idcard_by_id($id);
		$data['pers']=$this->idcard_model->get_pers_admin_logos_by_id($id); 
		$data['type_warna']  = $this->idcard_model->get_warna();
		$status=$this->idcard_model->get_idcard_by_id($id)->status;

		$data['status']=$status;

		switch ($status) {
		    case 1:
		        $data['title_id_card']= TITLE_NEW_ID;
		        break;
		    case 2:
		        $data['title_id_card']= TITLE_EXTEND_ID;
		        break;
		    case 3:
		    	$data['title_id_card']= TITLE_LOST_ID;
		        break;
		    case 4:
		    	$data['title_id_card']= TITLE_BROKEN_ID;
		        break;
		    default:
		        $data['title_id_card']= 'NOTHING';
		        break;
		}


		$data['status']=$data['status'].'edit_idcard';
		$data['action'] =  URL_EDIT_NIK_IDCARD_EXE;
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js');
	}

	public function edit_image()
	{	
		//var_dump($_POST);

		$imagePath = "./assets/images/foto/before/test.png";
		$imagePath2 = "./assets/images/foto/after/test.png";


		/*$upImg = $_POST["nImg"];
		$tempImg = end(explode("/", $upImg));
		echo $tempImg;
		if($tempImg=="no_image.jpg"){
			$old_path = $_POST["name"];
		}else{
			$old_path = $upImg;
		}*/

		$old_path = $_POST["name"];
		$low = $_POST["low"];
		$high = $_POST["high"];
		$r = $_POST["r"];
		$slect = $_POST["slect"];
		if($low == "")
		{ 
			$low = 1000;
		}
		if($high == "")
		{
			$high = 50000;
		}
		$imagick = new Imagick($old_path);
		$imagick->resizeImage(0, 400, Imagick::FILTER_LANCZOS, 1);
		$imagick->setImageFormat("png");
   		$imagick->writeImage($imagePath);
   		/*echo $low+"  ";
   		echo $high;*/
   		//$imagick->autoLevelImage();
   		
   		if($slect=="level"){
    		$imagick->levelImage ($low, 1, $high);
    		$imagick->writeImage($imagePath2);
    	}
    	else if($slect=="transparent"){

    		$im = new Imagick($imagePath);
    		$wid = $im->getImageWidth();
   			$heg = $im->getImageHeight();

  			$color = $im->getImagePixelColor(0,0);

   			$im->transparentPaintImage($color, 0 ,$r, 0); 
   			$im->despeckleimage();
 
   			$canvas = new Imagick();
			$canvas->newPseudoImage(
			        $im->getImageWidth(),
			        $im->getImageHeight(),
			        "pattern:checkerboard"
  					);
			$canvas->setImageFormat("png");
			$canvas->compositeimage($im, \Imagick::COMPOSITE_ATOP, 0, 0);
			$imagick = $canvas;

    	}

    	$imagick->writeImage($imagePath2);

	}

	public function update_image(){
		$nikx = $_POST['nik'];
		$imx = $_POST['im'];
		$imagick = new Imagick($imx);
		$getLast = explode("/", $nikx); 
		//$photoName = end($getLast);
		//echo $photoName;
		$photoName=$nikx;
		$imagick->writeImage("./assets/images/foto/$photoName");//ini ditaro dimana['https://10.10.55.25/idcard/./assets/images/foto/after/test.png']
		//$nikxe = str_replace(' ', '', $nikx);
		//$imagick->writeImage("./assets/images/foto/".$nikxe.".jpg");
		//echo $nikxe;	
	}

	public function edit_idcard_process()
	{
		
		$data['title_id_card']= TITLE_NEW_ID.'edit_idcard_process';
		$data['action'] =  URL_EDIT_NIK_IDCARD_EXE;
		$nik = $this->input->post('nik');
		$nama = $this->input->post('nama');
		$unit = $this->input->post('unit');
		$tgl_berlaku = $this->input->post('date_expired');
		$hr_nik = $this->session->userdata('nik');
		$tgl_request = date('m/d/Y h:i:s');
		$status = $this->input->post('hidden_status'); // status 1 (new id card), 2 (extend idcard), 3 (lost idcard), 4 (broken idcard)
		$hr_nik_persadmin = $this->session->userdata('persadmin');
		$warna = $this->input->post('slc_warna');
		$is_sap_hr = $this->session->userdata('is_sap');
		$check_image = $_FILES['id_image']['name'];
		$image = $_FILES['id_image'];
		//$image = $_FILES["./assets/images/foto/".$photoName];
		
		if($is_sap_hr==1)
		{
			if($is_sap_hr!=$this->input->post('hidden_sap'))
			{
				$persadmin = $this->input->post('hidden_pers_admin');
				$is_sap_karyawan = 0;
			}else{
				$persadmin = $this->user_model->get_data_user_sap($nik)->PersAdmin;	
				$is_sap_karyawan = 1;	
			}
		}
		else{
			$persadmin = $this->session->userdata('persadmin');
			$is_sap_karyawan = $this->session->userdata('is_sap');
		}

		if($hr_nik_persadmin==$persadmin)
		{
			
			if($is_sap_hr==0)
			{
					$id_cardonline = $this->input->post('hidden_id_cardonline');
					$old_path_photo = $this->input->post('hidden_path_photo');
					$data['old']=$this->idcard_model->get_idcard_by_id($id_cardonline);
					
					if($check_image=='')
					{
						$imagick = new Imagick("./assets/images/foto/before/test.png");
						$imagick->writeImage("./assets/images/foto/".$nik.".jpg");
						if($status == '3' or $status =='4')
						{	
							$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'nama' => $nama, 
								'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
								'status' => $status, 'persadmin' => $hr_nik_persadmin, 'warna'=>$warna, 'flag_proses' => 1);
						}
						else
						{
								$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'berlaku'=>$tgl_berlaku, 'nama' => $nama, 
								'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
								'status' => $status, 'persadmin' => $hr_nik_persadmin, 'warna'=>$warna, 'flag_proses' => 1);
						}
							$this->idcard_model->update_idcard($id_cardonline,$arr_update_idcard);
							$this->session->set_flashdata('notif_type','alert-success');
							$this->session->set_flashdata('notif_text','Success Update ID Card');
							redirect(URL_HOME_LIST);	
					}
					else
					{
						unlink($old_path_photo); // This is an absolute path to the file
						$imagename = $image['name'];
						$fileExt = strtolower(array_pop(explode(".", $imagename)));
						$uploaded_imagename =strtolower(trim($nik).".".$fileExt);

						$folder_name = UPL_FOTO_PATH;
						$config['upload_path'] = $folder_name;
						$config['allowed_types'] = UPL_ALLOW_TYPE;
						$config['max_size'] = UPL_MAX_SIZE;
						$config['max_width']  = UPL_MAX_WIDTH;
				    		$config['max_height']  = UPL_MAX_HEIGHT;
				    		$config['file_name']  = $uploaded_imagename;
						$this->load->library('upload', $config);
						$file_image_path = $folder_name.'/'.$uploaded_imagename;
						$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
						
						if($this->upload->do_upload("id_image")!=true)
						{
							$error = $this->upload->display_errors();
							$data['notif_text']=$error;
							$data['notif_type']='alert-danger';
						}
						else
						{
							if($status == '3' or $status =='4')
							{	
			 					$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'nama' => $nama, 
								'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
								'status' => $status, 'persadmin' => $hr_nik_persadmin, 'path_photo' => $file_image_path,'warna'=>$warna,
								'photo_path_app' => $foto_path_app, 'flag_proses' => 1);
							}else{
								$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'berlaku'=>$tgl_berlaku, 'nama' => $nama, 
								'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
								'status' => $status, 'persadmin' => $hr_nik_persadmin, 'path_photo' => $file_image_path,'warna'=>$warna,
								'photo_path_app' => $foto_path_app, 'flag_proses' => 1);

							}
							$this->idcard_model->update_idcard($id_cardonline,$arr_update_idcard);
							$this->session->set_flashdata('notif_type','alert-success');
							$this->session->set_flashdata('notif_text','Success Update ID Card');

							redirect(URL_HOME_LIST);	
						}
					}
			}else{

				$image = $_FILES['id_image'];
				$id_cardonline = $this->input->post('hidden_id_cardonline');
				$old_path_photo = $this->input->post('hidden_path_photo');
				$data['old']=$this->idcard_model->get_idcard_by_id($id_cardonline);
				
				if($check_image=='')
				{
					if($status == '3' or $status =='4')
					{	
						$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'nama' => $nama, 
							'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
							'status' => $status, 'persadmin' => $persadmin,'warna'=>$warna, 'flag_proses' => 1);
					}else
					{
						$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'berlaku'=>$tgl_berlaku, 'nama' => $nama, 
							'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
							'status' => $status, 'persadmin' => $persadmin,'warna'=>$warna, 'flag_proses' => 1);
					}
						$this->idcard_model->update_idcard($id_cardonline,$arr_update_idcard);
						$this->session->set_flashdata('notif_type','alert-success');
						$this->session->set_flashdata('notif_text','Success Update ID Card');
						redirect(URL_HOME_LIST);	
				}
				else
				{
					//$old_path_photo = str_replace(".","", $old_path_photo);
					unlink($old_path_photo); // This is an absolute path to the file
					$imagename = $image['name'];
					$fileExt = strtolower(array_pop(explode(".", $imagename)));
					$uploaded_imagename =strtolower(trim($nik).".".$fileExt);

					$folder_name = UPL_FOTO_PATH;
					// $folder_name = UPL_FOTO_PATH.date('Ym');
					// if(!file_exists($folder_name))
					// {
					// 	mkdir($folder_name);
					// }

					$config['upload_path'] = $folder_name;
					$config['allowed_types'] = UPL_ALLOW_TYPE;
					$config['max_size'] = UPL_MAX_SIZE;
					$config['max_width']  = UPL_MAX_WIDTH;
				    	$config['max_height']  = UPL_MAX_HEIGHT;
				    	$config['file_name']  = $uploaded_imagename;
					$this->load->library('upload', $config);
					$file_image_path = $folder_name.'/'.$uploaded_imagename;
					$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
					
					if($this->upload->do_upload("id_image")!=true)
					{
						$error = $this->upload->display_errors();
						$data['notif_text']=$error;
						$data['notif_type']='alert-danger';
					}
					else
					{
						if($status == '3' or $status =='4')
						{		
							$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'nama' => $nama, 
							'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
							'status' => $status, 'persadmin' => $persadmin, 'path_photo' => $file_image_path,'warna'=>$warna,
							'photo_path_app' => $foto_path_app, 'flag_proses' => 1);
						}else{
							$arr_update_idcard = array('nik' => $nik, 'barcode' => $nik, 'berlaku'=>$tgl_berlaku, 'nama' => $nama, 
							'unit' => $unit, 'tgl_request' => $tgl_request, 'hr_nik_request' => $hr_nik, 
							'status' => $status, 'persadmin' => $persadmin, 'path_photo' => $file_image_path,'warna'=>$warna,
							'photo_path_app' => $foto_path_app, 'flag_proses' => 1);
						}
						$this->idcard_model->update_idcard($id_cardonline,$arr_update_idcard);
						$this->session->set_flashdata('notif_type','alert-success');
						$this->session->set_flashdata('notif_text','Success Update ID Card');

						redirect(URL_HOME_LIST);	
					}
				}
			}
		}else{

			if($is_sap_hr!=$is_sap_karyawan)
			{
				$data['notif_text']='Karyawan tersebut bukan karyawan PT anda.';
				$data['notif_type']='alert-danger';

			}
		}

		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js');
	}


	public function delete_idcard($id)
	{

		$arr_delete_idcard = array('id_cardonline' => $id);
		$data['pers']=$this->idcard_model->get_pers_admin_logos($id); 
		//unlink image from server
		$old_path_photo = $this->idcard_model->get_idcard_by_id($id)->path_photo;
		unlink($old_path_photo); 
		$this->idcard_model->delete_idcard($id,$arr_delete_idcard);
		$this->session->set_flashdata('notif_type','alert-success');
		$this->session->set_flashdata('notif_text','Success Delete ID Card');

		redirect(URL_HOME_LIST);	
	}


	public function extend_idcard()
	{
		
		$data['nama']= $this->session->userdata('nama');
		$data['type_warna']  = $this->idcard_model->get_warna();
		$data['title_id_card']= TITLE_EXTEND_ID;
		$data['action'] =  URL_EXTEND_NIK_IDCARD_EXE;
		$data['status'] = 2;
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js',$data);
	}

	public function add_extend_process()
	{
		
		$data['title_id_card']= TITLE_EXTEND_ID;
		$data['action'] =  URL_EXTEND_NIK_IDCARD_EXE;
		$data['status'] = 2;
		$data['type_warna']  = $this->idcard_model->get_warna();
		$nik = $this->input->post('nik');
		$nama = $this->input->post('nama');
		$unit = $this->input->post('unit');
		$tgl_berlaku = $this->input->post('date_expired');
		$hr_nik = $this->session->userdata('nik');
		$warna = $this->input->post('slc_warna');
		$tgl_request = date('m/d/Y h:i:s');
		$status = 2; // status 1 (new id card), 2 (extend idcard), 3 (lost idcard), 4 (broken idcard)
		$hr_nik_persadmin = $this->session->userdata('persadmin');

		
		$is_sap_hr = $this->session->userdata('is_sap');

		$count_nik_idcard=$this->user_model->check_user_idcard($nik);
		$count_user=$this->user_model->check_user_nik_sap($nik);

		if($count_nik_idcard!=0)
		{
			if($count_user==0)
			{
				$persadmin =$this->user_model->get_data_user_non_sap($hr_nik)->persadmin;
				$is_sap_karyawan =0;
			}
			else
			{
				$persadmin=$this->user_model->get_data_user_sap($nik)->PersAdmin;
				$is_sap_karyawan=1;
			}

			if($hr_nik_persadmin==$persadmin)
			{
				/* IMAGE MANIPULATE */
				$image = $_FILES['id_image'];
				$imagename = $image['name'];
				$fileExt = strtolower(array_pop(explode(".", $imagename)));
				$uploaded_imagename =strtolower($nik.".".$fileExt);

				//$folder_name = UPL_FOTO_PATH.date('Ym');
				$folder_name = UPL_FOTO_PATH;
				// if(!file_exists($folder_name))
				// {
				// 	mkdir($folder_name);
				// }

				if($imagename=='')
				{
					$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
									'berlaku'=>$tgl_berlaku, 'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request,
									 'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
									 'sap'=>$is_sap_hr, 'warna'=>$warna, 'tgl_cetak'=>null, 'flag_proses' => 1);
					$this->idcard_model->update_idcard_by_nik($nik, $arr_new_idcard);
					$this->session->set_flashdata('notif_type','alert-success');
					$this->session->set_flashdata('notif_text','Success Add ID Card');
					redirect(URL_HOME_LIST);	
				}
				else{
					$old_photo_path = $this->idcard_model->get_idcard_by_nik($nik)->path_photo;

					$config['upload_path'] = $folder_name;
					$config['allowed_types'] = UPL_ALLOW_TYPE;
					$config['max_size'] = UPL_MAX_SIZE;
					$config['max_width']  = UPL_MAX_WIDTH;
				    	$config['max_height']  = UPL_MAX_HEIGHT;
				    	$config['file_name']  = $uploaded_imagename;
					$this->load->library('upload', $config);
					$file_image_path = $folder_name.'/'.$uploaded_imagename;
					$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
					
					if($this->upload->do_upload("id_image")!=true)
					{
						$error = $this->upload->display_errors();
						$data['notif_text']=$error;
						$data['notif_type']='alert-danger';
					}
					else
					{
						$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
							'berlaku'=>$tgl_berlaku, 'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request,
							 'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
							 'path_photo' => $file_image_path,'sap'=>$is_sap_hr, 'warna'=>$warna, 'tgl_cetak'=>null,
							 'photo_path_app' => $foto_path_app, 'old_photo_path' => $old_photo_path, 'flag_proses' => 1);
						$this->idcard_model->update_idcard_by_nik($nik,$arr_new_idcard);
						$this->session->set_flashdata('notif_type','alert-success');
						$this->session->set_flashdata('notif_text','Success Add ID Card');
						redirect(URL_HOME_LIST);	
					}
				}	
			}
			else
			{
				$data['notif_text']='Karyawan tersebut bukan karyawan PT anda.';
				$data['notif_type']='alert-danger';
			}
		}
		else
		{
			$data['notif_text']='NIK tersebut tidak ada di data kami silahkan ajukan New ID.';
			$data['notif_type']='alert-danger';	
		}



		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_not_add_js');
	}


	public function lost_idcard()
	{
		
		$data['nama']= $this->session->userdata('nama');
		$data['type_warna']  = $this->idcard_model->get_warna();
		$data['title_id_card']= TITLE_LOST_ID;
		$data['action'] =  URL_LOST_NIK_IDCARD_EXE;
		$data['status'] = 3;
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js');

	}

	public function add_lost_process()
	{
		 
		$data['title_id_card']= TITLE_LOST_ID.'add_lost_process';
		$data['action'] =  URL_LOST_NIK_IDCARD_EXE;
		$data['status'] = 3;	
		$nik = $this->input->post('nik');
		$data['type_warna']  = $this->idcard_model->get_warna();
		$nama = $this->input->post('nama');
		$unit = $this->input->post('unit');
		$tgl_berlaku = $this->input->post('date_expired');
		$hr_nik = $this->session->userdata('nik');
		$warna = $this->input->post('slc_warna');
		$tgl_request = date('m/d/Y h:i:s');
		$status = 3; // status 1 (new id card), 2 (extend idcard), 3 (lost idcard), 4 (broken idcard)
		$hr_nik_persadmin = $this->session->userdata('persadmin');
		$is_sap_hr = $this->session->userdata('is_sap');
		$count_user=$this->user_model->check_user_nik_sap($nik);
		$count_nik_idcard=$this->user_model->check_user_idcard($nik);

		if($count_nik_idcard!=0)
		{

			if($count_user==0)
			{
				$persadmin =$this->user_model->get_data_user_non_sap($hr_nik)->persadmin;
				$is_sap_karyawan =0;
			}else{
				$persadmin=$this->user_model->get_data_user_sap($nik)->PersAdmin;
				$is_sap_karyawan=1;
			}
			


			if($hr_nik_persadmin==$persadmin)
			{
			
				/* IMAGE MANIPULATE */
				$image = $_FILES['id_image'];
				$imagename = $image['name'];
				$fileExt = strtolower(array_pop(explode(".", $imagename)));
				$uploaded_imagename =strtolower($nik.".".$fileExt);

				//$folder_name = UPL_FOTO_PATH.date('Ym');
				$folder_name = UPL_FOTO_PATH;
				// if(!file_exists($folder_name))
				// {
				// 	mkdir($folder_name);
				// }

				if($imagename=='')
				{
					$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
									'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request,
									 'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
									 'tgl_cetak' => NULL,'sap'=>$is_sap_hr, 'hilang'=>'1', 'warna'=>$warna, 'flag_proses' => 1);
					$this->idcard_model->update_idcard_by_nik($nik,$arr_new_idcard);
                                        $this->idcard_model->update_karya1_by_nik($nik);
					$this->session->set_flashdata('notif_type','alert-success');
					$this->session->set_flashdata('notif_text','Success Add ID Card');
					redirect(URL_HOME_LIST);	
				}
				else{
					$old_photo_path = $this->idcard_model->get_idcard_by_nik($nik)->path_photo;
					$config['upload_path'] = $folder_name;
					$config['allowed_types'] = UPL_ALLOW_TYPE;
					$config['max_size'] = UPL_MAX_SIZE;
					$config['max_width']  = UPL_MAX_WIDTH;
			    		$config['max_height']  = UPL_MAX_HEIGHT;
				    	$config['file_name']  = $uploaded_imagename;
					$this->load->library('upload', $config);
					$file_image_path = $folder_name.'/'.$uploaded_imagename;
					$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
					
					if($this->upload->do_upload("id_image")!=true)
					{
						$error = $this->upload->display_errors();
						$data['notif_text']=$error;
						$data['notif_type']='alert-danger';
					}
					else
					{
						$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
							'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request,
							 'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
							 'path_photo' => $file_image_path,'sap' => $is_sap_hr,'hilang'=>'1', 'warna'=>$warna,'tgl_cetak'=>NULL,
							 'photo_path_app' => $foto_path_app, 'old_photo_path' => $old_photo_path, 'flag_proses' => 1);
						$this->idcard_model->update_idcard_by_nik($nik,$arr_new_idcard);
                                                $this->idcard_model->update_karya1_by_nik($nik);
						$this->session->set_flashdata('notif_type','alert-success');
						$this->session->set_flashdata('notif_text','Success Add ID Card');
						redirect(URL_HOME_LIST);	
					}
				}

		
			}else{
				$data['notif_text']='Karyawan tersebut bukan karyawan PT anda.';
				$data['notif_type']='alert-danger';
			}
		}else{
			$data['notif_text']='NIK tersebut tidak ada di data kami silahkan ajukan New ID.';
			$data['notif_type']='alert-danger';	
		}

		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_not_add_js');
	}

	

	public function broken_idcard()
	{
		 
		$data['nama']= $this->session->userdata('nama');
		$data['type_warna']  = $this->idcard_model->get_warna();
		$data['title_id_card']= TITLE_BROKEN_ID;
		$data['action'] =  URL_BROKEN_NIK_IDCARD_EXE;
		$data['status'] = 4;
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data); 
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_js');
	}



	public function add_broken_process()
	{
		 
		$data['title_id_card']= TITLE_BROKEN_ID;
		$data['action'] =  URL_BROKEN_NIK_IDCARD_EXE;
		$data['status'] = 4;
		$data['type_warna']  = $this->idcard_model->get_warna();
		$nik = $this->input->post('nik');/////
		$nama = $this->input->post('nama');
		$unit = $this->input->post('unit');
		$tgl_berlaku = $this->input->post('date_expired');
		$hr_nik = $this->session->userdata('nik');
		$tgl_request = date('m/d/Y h:i:s');
		$warna = $this->input->post('slc_warna');
		$status = 4; // status 1 (new id card), 2 (extend idcard), 3 (lost idcard), 4 (broken idcard)
		$hr_nik_persadmin = $this->session->userdata('persadmin');
		$is_sap_hr = $this->session->userdata('is_sap');
		$count_user=$this->user_model->check_user_nik_sap($nik);
		$count_nik_idcard=$this->user_model->check_user_idcard($nik);

		if($count_nik_idcard!=0)
		{
			if($count_user==0)
			{
				$persadmin =$this->user_model->get_data_user_non_sap($hr_nik)->persadmin;	
				$is_sap_karyawan =0;
			}else{
				$persadmin=$this->user_model->get_data_user_sap($nik)->PersAdmin;
				$is_sap_karyawan=1;
			}
			


			if($hr_nik_persadmin==$persadmin)
			{
			
				/* IMAGE MANIPULATE */
				$image = $_FILES['id_image'];
				$imagename = $image['name'];
				$fileExt = strtolower(array_pop(explode(".", $imagename)));
				$uploaded_imagename =strtolower($nik.".".$fileExt);

				//$folder_name = UPL_FOTO_PATH.date('Ym');
				$folder_name = UPL_FOTO_PATH;
				// if(!file_exists($folder_name))
				// {
				// 	mkdir($folder_name);
				// }

				if($imagename=='')
				{
					$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
									'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request,
									 'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
									 'tgl_cetak' => NULL,'sap'=>$is_sap_hr, 'rusak'=>'1', 'warna'=>$warna, 'flag_proses' => 1);
					$this->idcard_model->update_idcard_by_nik($nik, $arr_new_idcard);
					$this->session->set_flashdata('notif_type','alert-success');
					$this->session->set_flashdata('notif_text','Success Add ID Card');
					redirect(URL_HOME_LIST);	
				}
				else{
					$old_photo_path = $this->idcard_model->get_idcard_by_nik($nik)->path_photo;
					$config['upload_path'] = $folder_name;
					$config['allowed_types'] = UPL_ALLOW_TYPE;
					$config['max_size'] = UPL_MAX_SIZE;
					$config['max_width']  = UPL_MAX_WIDTH;
			    	$config['max_height']  = UPL_MAX_HEIGHT;
			    	$config['file_name']  = $uploaded_imagename;
					$this->load->library('upload', $config);
					$file_image_path = $folder_name.'/'.$uploaded_imagename;
					$foto_path_app = UPL_FOTO_PATH_APP.'/'.$uploaded_imagename;
					
					if($this->upload->do_upload("id_image")!=true)
					{
						$error = $this->upload->display_errors();
						$data['notif_text']=$error;
						$data['notif_type']='alert-danger';
					}
					else
					{
						$arr_new_idcard = array('nik' => $nik, 'barcode' => $nik, 
							'nama' => $nama, 'unit' => $unit, 'tgl_request' => $tgl_request, 
							'hr_nik_request' => $hr_nik, 'status' => $status, 'persadmin' => $persadmin, 
							'path_photo' => $file_image_path, 'sap'=>$is_sap_hr, 'rusak'=>'1', 'warna'=>$warna,'tgl_cetak'=>NULL,
							'photo_path_app' => $foto_path_app, 'old_photo_path' => $old_photo_path, 'flag_proses' => 1);
						$this->idcard_model->update_idcard_by_nik($nik,$arr_new_idcard);
						$this->session->set_flashdata('notif_type','alert-success');
						$this->session->set_flashdata('notif_text','Success Add ID Card');
						redirect(URL_HOME_LIST);	
					}				
				}
			}else{
				$data['notif_text']='Karyawan tersebut bukan karyawan PT anda.';
				$data['notif_type']='alert-danger';
			}
		}else{
			$data['notif_text']='NIK tersebut tidak ada di data kami silahkan ajukan New ID.';
			$data['notif_type']='alert-danger';	
		}

		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/id_card',$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('frontend/id_card_not_add_js');
	}




	public function generate_Idcard()
	{
	$nama = $this->input->post('name'); ///
	$logo =  $this->input->post('logo'); //di controller 
	$nik  = $this->input->post('nik'); //di controller 

	/////////////////////////////////////mulai generate id

	/////////////////
	
	$handle = fopen('./assets/images/for_idcard/IDCARD_BASIC.jpg', "r");
	$img_content = fread($handle, filesize('./assets/images/for_idcard/foto.jpg'));
	fclose($handle);

	// './assets/images/for_idcard/foto.jpg'
	//$im = imagecreatefromjpeg($img_content); 

	// './assets/images/for_idcard/

	$my_img= imagecreatefromjpeg('./assets/images/for_idcard/IDCARD_BASIC.jpg');
	$text_colour = imagecolorallocate( $my_img, 0,0,0 );
	$text_nik=$nik;//ini nik 007201
	$text_nama=$nama;//GREGORIUS INDRA D C
	// $text_nik="056270";//ini nik 007201
	// $text_nama="ANDHIKA DWI SAPUTRA";//GREGORIUS INDRA D C
	
	
	$a=11;
	if ($a<=11) //pengecekan ada ato gak jabatan
		{
		$text_pembatas_baris="|";
		$text_jabatan="O directive"; //Resource Information System
		}
	else
		{
		$text_pembatas_baris="";			
		$text_jabatan=" ";//group of dyndra media international
		}
	$text_bawah=$text_nik." ".$text_pembatas_baris." ".$text_jabatan;
			
				///////////////setting font
	$fontNama=  './assets/images/for_idcard/Futura Std Bold.otf';
	
	$fontNik=  './assets/images/for_idcard/Century Gothic.ttf';
	
	$fontJabatan = './assets/images/for_idcard/Century Gothic.ttf';
		
	$fontPembatasBaris=  './assets/images/for_idcard/Futura Book font.ttf';
				///////////// end setting sizefont
				
	$sizeFontNama=23;//sudah fix
	$sizeFontBawah=28;

			///imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
	imagettftext($my_img,$sizeFontNama,0,10,935,$text_colour,$fontNama,$text_nama);

	imagettftext($my_img,$sizeFontBawah,0,10,980,$text_colour,$fontNik,$text_nik );

	imagettftext($my_img,$sizeFontBawah,0,145,980,$text_colour,$fontPembatasBaris,$text_pembatas_baris);

	imagettftext($my_img,$sizeFontBawah,0,160,980,$text_colour,$fontJabatan,$text_jabatan );


	imagesetthickness ( $my_img, 10 );
		

	//header('Content-Type: image/jpg');//klo mau liat req 1/2
	$gmbrTemp='./assets/images/for_idcard/IDCARD_BASICqwe.jpg';
	imagejpeg($my_img, $gmbrTemp, 100);//ngebikin file image di dengan nama IDcard.jpg

	 

	 imagedestroy( $my_img );
	
		 /////////////////////////////////////selesai generate id
	$data['link']=base_url().'assets/images/for_idcard/IDCARD_BASICqwe.jpg';

	 
	echo $this->load->view('frontend/prev_IDcard', $data, true);

	}
}
?>

