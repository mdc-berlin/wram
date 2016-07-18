<script>
	<?php
		if (preg_match("/\d{2}.\d{2}.\d{4}/",$datum)) { 
			
			$str = preg_split("/[\.]/", $datum);
			
			echo "var date_set = new Date('".$str[1]."-".$str[0]."-".$str[2]."');";
		}
		else {
			echo "var date_set = new Date();";
		}
	?>
</script>

	<div class="bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
		<?= $dyn_info ?>
 	</div>


<!-- Button trigger modal -->
<!-- button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <!-- div class="modal-body">
        ...
      </div -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- button type="button" class="btn btn-primary">Save changes</button -->
      </div>
    </div>
  </div>
</div>


<div class="bs-example row">
 			
  <div class="col-md-4"><h3>1. Schritt <?= $check_1 ?><br />Datum wählen</h3>

		<div class="input-group">
			<div id="datepicker"></div>
		</div></div>
  <div class="col-md-8">
	  <h3>2. Schritt <?= $check_2 ?>: <br />Ihre Strecke <?= $datum ?></h3>

		<div class="input-group">
 
   <input type="hidden" name="remote_user" value="<?= $remote_user ?>" />
   <input type="checkbox" name="check_arbeit" id="check_arbeit" value="<?= $km_arbeit ?>" <?= $km_work?'checked':''?>> Mit dem Rad zur Arbeitsstelle gefahren<br />
   
    <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-bicycle"></i></div>
      <input type="number" name="sql_arbeits_km" id="sql_arbeits_km" class="form-control" placeholder="Kilometer" value="<?= $km_work ?>" readonly>
      <div class="input-group-addon">km</div>
    </div>
  </div>
  
  <br /><br />
  Sonstige Fahrten mit dem Rad<br />
  <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-bicycle"></i></div>
      <input type="number" class="form-control" name="sql_sonstige_km" id="sql_sonstige_km" value="<?= $km_priv ?>" placeholder="Kilometer">
      <div class="input-group-addon">km</div>
    </div>
  </div>
</div>
  
  <br />
  
  <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
	  <textarea class="form-control" id="begr" name="sql_bemerkungen" rows="3" placeholder="Erläuterungen zu sonstigen Fahrten"><?= $km_begr ?></textarea>
    </div>
  </div>
  
  <br />
  <button type="submit" class="btn btn-primary">eintragen</button>
  </div>
		
		
</div>
	


	
	
	