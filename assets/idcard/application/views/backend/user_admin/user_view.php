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
    <h1>Welcome to ID Card Online!</h1>
    <ol class="breadcrumb">
      <li class="active"><a href="<?php echo base_url() ?>">Home</a></li>
      <li class="active"><a href="<?php echo base_url() ?>"><?=$title_user?></a></li>
    </ol>

    <div class="row">
         <div class="col-xs-12 col-md-10">
            <?php
            $attributes = array('class' => 'form-inline', 'id' => 'id_search');
            echo form_open($action, $attributes);
            ?>
              <div class="form-group">
                <label class="sr-only" for="exampleInputAmount">Search</label>
                <div class="input-group">
                  <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
                  <input type="text" class="form-control" id="txt_nama" name="txt_nama" minlength="3" placeholder="Search Nama">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Search</button>
            <?php echo form_close(); ?>
         </div>
         <div class="col-xs-6 col-md-2">
          <?php echo anchor($link['new_user'],'<i class="glyphicon glyphicon-plus"></i> New Admin','class="btn btn-default"'); ?>
        </div>
    </div>
    
    
    <div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
                <tr>
                  <!-- <th>#</th> -->
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Unit</th>
                  <th>PersAdmin</th>
                  <th>Group</th>
                  <th>Status SAP</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($request_data as $row) {

                        switch ($row['is_sap']) {
                            case 0:
                                $sap_status = 'Non SAP';
                                break;
                            case 1:
                                $sap_status = 'SAP';
                                break;
                        }
                        echo '<tr>';
                            echo '<td>'.$row['nik'].'</td>';
                            echo '<td>'.$row['nama'].'</td>';
                            echo '<td>'.$row['unit'].'</td>';
                            echo '<td>'.$row['persadmin'].'</td>';
                            echo '<td>'.$row['role'].'</td>';
                            echo '<td>'.$sap_status.'</td><td>';
                            echo anchor($link['edit_user'].$row['id_user'],'<i class="glyphicon glyphicon-pencil"></i>','class="btn fancybox" title="Edit"').' </td><td>';
                            echo anchor($link['delete_user'].$row['id_user'],'<i class="glyphicon glyphicon-trash"></i>','class="btn fancybox" title="Delete"').' </td>';
                            echo '</tr>';
                }

                ?>
            </tbody>
          </table>
          <div class="pagination"><?php if(!empty($links)){ echo $links; } ?></div>
        </div>
    </div>

    