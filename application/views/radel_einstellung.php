<?php
require('translations.php');
?>
	<input type="hidden" name="first_call" value="1" />
	<input type="hidden" name="gender" id="gender" value="" />
	<input type="hidden" name="is_new_user" id="is_new_user" value="<?= $pre_is_new_user ?>" />
	<input type="hidden" name="ext_user" id="ext_user" value="<?= $pre_ext_user ?>" />
	<div class="bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
		<?= $dyn_info ?>
 	</div>


	<div class="bs-example">
		
		<h3><?= $strings['yourdata'][$lang]; ?></h3>

		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1"><i class="fa fa-<?= $pre_sql_gender ?>" id="gender_icon"></i></span>
			
			<div class="btn-group" role="group" aria-label="...">
				<button type="button" class="btn btn-default first <?= $pre_sql_anrede_m ?>" id="anrede_m" name="sql_anrede" value="m"><?= $strings['male'][$lang]; ?></button>
				<button type="button" class="btn btn-default <?= $pre_sql_anrede_w ?>" id="anrede_w" name="sql_anrede" value="w"><?= $strings['female'][$lang]; ?></button>
			</div>
		</div>
		
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
			<input type="text" class="form-control" placeholder="Vorname" aria-describedby="basic-addon1" name="sql_Vorname" value="<?= $ldap_vorname ?>" <?= $ldap_vorname?'readonly="readonly"':'' ?> />
		</div>
		
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
			<input type="text" class="form-control" placeholder="Nachname" aria-describedby="basic-addon1" name="sql_Nachname" value="<?= $ldap_nachname ?>" <?= $ldap_nachname?'readonly="readonly"':'' ?> />
		</div>
	
	</div>
	
	
	
	
	<div class="bs-example" data-example-id="static-dropdown">
		
		<h3><?= $strings['distance'][$lang]; ?></h3>

		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1"><i class="fa fa-bicycle"></i></span>
			<input type="number" name="sql_Km_zur_Arbeit" value="<?= $pre_Km_zur_Arbeit ?>" class="form-control" placeholder="gefahrene Strecke zur Arbeit" aria-describedby="basic-addon1" required="true" />
			<span class="input-group-addon" id="basic-addon2">km</span>
		</div>
		
		<p><?= $strings['distance_km'][$lang]; ?></p>
		
		<div class="input-group">
		<fieldset>
		
		<div class='row' style='display: none'>
	        <div class='col-sm-6'>    
	            <div class='form-group input-group'>
		            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-map-marker"></i></span>
	                <input class="form-control" name="sql_Anschrift_Privat" value="-" id="user_addr" placeholder="Ihre Privatanschrift" required="false" size="30" type="text" />
	            </div>
	        </div>
	        <div class='col-sm-2'>
	            <div class='form-group input-group'>
		            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope-o"></i></span>
	                <input class="form-control" name="sql_PLZ_Privat" value="00000" id="user_plz" placeholder="Postleitzahl" required="false" size="30" type="text" />
	            </div>
	        </div>
			<div class='col-sm-4'>
	            <div class='form-group input-group'>
		            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-building"></i></span>
	                <input class="form-control" name="sql_Stadt_Privat" value="-" id="user_town" placeholder="Stadt" required="false" size="30" type="text" />
	            </div>
	        </div>
	    </div>
	    
	    <div class='row' style='display: none'>
	        <div class='col-sm-6'>    
	            <div class='form-group input-group'>
		            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-map-marker"></i></span>
	                <input class="form-control" name="sql_Anschrift_Arbeit" value="-" id="company_addr" placeholder="Anschrift der Arbeitsstelle" required="false" size="30" type="text" />
	            </div>
	        </div>
	        <div class='col-sm-2'>
	            <div class='form-group input-group'>
		            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope-o"></i></span>
	                <input class="form-control" name="sql_PLZ_Arbeit" value="00000" id="company_plz" placeholder="Postleitzahl" required="false" size="30" type="text" />
	            </div>
	        </div>
	        <div class='col-sm-4'>
	            <div class='form-group input-group'>
		            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-building"></i></span>
	                <input class="form-control" name="sql_Stadt_Arbeit" value="-" id="company_town" placeholder="Stadt" required="false" size="30" type="text" />
	            </div>
	        </div>
	    </div>
	    

		</fieldset>
		</div>
	</div>


	<div class="bs-example">
	<h3><?= $strings['team_your'][$lang]; ?></h3>

		<div class="input-group">
			<span tabindex="-1" data-toggle="dropdown" class="input-group-addon btn btn-default dropdown-toggle" id="basic-addon1"><i class="fa fa-users"></i></span>
			
			<?php
				if (! $pre_team_name) {
			?>		
			<select class="form-control" name="sql_team_change" id="team_change">
			  <option value="none"><?= $strings['team_no'][$lang]; ?></option>
			  <option value="add"><?= $strings['team_add'][$lang]; ?></option>
			  <option value="new"><?= $strings['team_create'][$lang]; ?></option>
			</select>
			<?php
				}
			?>
			
			<input type="text" id="team_name" name="sql_team_name" class="form-control" placeholder="<?= $pre_team_name ?>" aria-describedby="basic-addon1"
			<?php if ($pre_team_name) { ?>
				readonly="readonly"
			<?php } ?>
			/>
			<select class="form-control" name="sql_team_join" id="team_join">
				<?= $open_teams; ?>
			</select>
		</div>
		
			<?php
				if ($pre_team_name) {
					?>
			<button type="submit" class="btn btn-primary" id="delete_team" name="sql_delete_team"><?= $strings['team_exit'][$lang]; ?></button>
					<?php
				}
			?>
		
    </div>
    
    <?= $user_is_new ?>
	
  	<button type="submit" class="btn btn-primary"><?= $strings['change'][$lang]; ?></button>
	
	
	</form>