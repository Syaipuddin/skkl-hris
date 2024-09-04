<div class="container">
    <div class="row-fluid">
  <div class="col-md-4 col-md-offset-7">
    <?php 

    if ($notif!=''){
      echo '<div class="alert alert-danger">';
      echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
      echo '<h4 class="alert-heading">Access Denied !</h4>';
      echo $notif;
      echo '</div>';
    }?>
  </div>
</div>

    <div class="row">
        <div class="col-md-4 col-md-offset-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-lock"></span> Login Administrator</div>
                <div class="panel-body">
                    <?php 
                    $attribute =  array('class' => 'form-horizontal', 'id'=> 'login_form');
                    echo form_open('backend/login_process', $attribute); ?>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">
                            NIK</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nik" name="nik" placeholder="Nik" maxlenght="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">
                            Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group last">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success btn-sm">
                                Sign in</button>
                                 <button type="reset" class="btn btn-default btn-sm">
                                Reset</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
