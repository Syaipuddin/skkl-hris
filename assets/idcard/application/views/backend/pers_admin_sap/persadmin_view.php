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
    </div>
    
    
    <div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
                <tr>
                  <!-- <th>#</th> -->
                  <th>PersAdmin SAP</th>
                  <th>Nama PersAdmin SAP</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($request_data as $row) {

                        echo '<tr>';
                            echo '<td>'.$row['PersAdmin'].'</td>';
                            echo '<td>'.$row['PersAdminText'].'</td><td>';
                            echo '</tr>';
                }

                ?>
            </tbody>
          </table>
          <div class="pagination"><?php if(!empty($links)){ echo $links; } ?></div>
        </div>
    </div>

    
