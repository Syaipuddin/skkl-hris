
	<!-- Modal -->
	  <div class="modal-dialog" id="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><?=TITLE_SEARCH_USER?></h4>
	      </div>
	      <div class="modal-body">
	        	
	      <?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'id_form_search_user');
		echo form_open($action, $attributes); ?>	

		
		<div class="row">
			<div class="col-xs-12">
				  <div class="form-group">
				    <label class="col-sm-2 control-label">Nama</label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="nama" placeholder="Nama" name="nama" required>
				    </div>
				    <div class="col-sm-2">
				      <button type="submit" class="btn btn-primary" >Search</button>
				    </div>
				  </div>
			</div>



		<?php
			echo form_close();
		?>
		</div>

			 <table class="table table-hover">
            <thead>
                <tr>
                  <!-- <th>#</th> -->
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Unit</th>
                </tr>
            </thead>
            <tbody id='table_data'>
            </tbody>
          </table>
      

	      </div>
	    </div>
	  </div>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#id_form_search_user').submit(function(event) {
		    
		    	   var nama = $('#nama').val();
				    $.ajax
				    ({
				        type:"POST",
				        url: "<?=base_url();?>master_data/user_admin/get_data_user_name",
				        data: { nama : nama},
				        dataType: 'json', 
				        success: function(_data)
				        {
				        	var tr;
				        	$('table').html('');
				        	th = $('<tr/>');
						        th.append("<td><strong>NIK</strong></td>");
						        th.append("<td><strong>Nama</strong></td>");
						        th.append("<td><strong>Unit</strong></td>");
						    $('table').append(th);   
						    for (var i = 0; i < _data.length; i++) {
						        tr = $('<tr/>');
						        tr.append("<td>" + _data[i].NIK + "</td>");
						        tr.append("<td>" + _data[i].Nama + "</td>");
						        tr.append("<td>" + _data[i].Unit + "</td>");
						        $('table').append(tr);
						    }

				        	//$('#table_data').append('<tr><td>aa</td><td>aaa</td><td>asad</td></tr>');
				        }
				    })
				    event.preventDefault();

		   	});
		});
	</script>