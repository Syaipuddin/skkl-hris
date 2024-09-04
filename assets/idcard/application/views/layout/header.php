<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<title>ID Card Online</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Brente">

	<!-- Le styles -->
	<?php echo link_tag('assets/css/bootstrap.css'); ?>
  <?php echo link_tag('assets/css/custom.css'); ?>
  <?php echo link_tag('assets/css/jquery-ui.css'); ?>
  <?php echo link_tag('assets/css/jquery.fancybox.css'); ?>
  
  

  </head>

  <body>

  <div id="custom-bootstrap-menu" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header"><a class="navbar-brand" href="<?php echo base_url().'home' ?>">IDCARD</a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-menubuilder">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="<?php echo base_url().'home' ?>">Home</a>
                </li>
                <?php
                  if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE){
                    ?>
                <li class="dropdown">
                  <a id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                    User Management
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                    	<li><?php echo anchor(URL_USER_ADMIN_LISTS,"List User Admin")?></li>
			<li><?php echo anchor(URL_PERS_ADMIN_LISTS,"List PersAdmin Non SAP")?></li>
			<li><?php echo anchor(URL_PERS_ADMIN_SAP_LISTS,"List PersAdmin SAP")?></li>
                  </ul>
                </li>
                <!-- <li><a href="/about-us">Setting</a> -->
                </li>
				<!-- Charles -->
				<li><?php echo anchor(URL_TAGIHAN_LISTS,"Nota Tagihan")?></li>
				
                <?php } ?>
                <li><?php echo anchor(URL_REPORT_LISTS,"Report")?></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><?php echo anchor('backend/logout', 'Logout');?></li>
            </ul>
        </div>

    </div>
</div>
    <div class="container <?php if(isset($is_fluid) && $is_fluid){ echo 'container-fluid';} ?>">
