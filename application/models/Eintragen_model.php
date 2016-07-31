<?php defined('BASEPATH') OR exit('No direct script access allowed');
$lang = strtolower(@array_shift(explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE'])));
if($lang == 'de-de') { $lang = 'de'; }
if($lang != 'de') { $lang = 'en'; }

class Eintragen_model extends CI_Model {
	
		// <p class="bg-success"></p>
		// <p class="bg-danger"></p>

        public $error_text	= array();
        public $succ_text	= array();
        public $date;
        public $user_is_new			= 0;
        public $user_is_registered	= 0; 
        public $user_is_admin		= 0;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get_last_ten_entries()
        {
                $query = $this->db->get('entries', 10);
                return $query->result();
        }
        
        public function check_and_write_entries($ldap_kennung, $ldap_nachname, $ldap_vorname, $ldap_abteilung, $ext_user = NULL)
        {
	        // Test auf Vollständigkeit
	        if (isset($_POST['sql_Km_zur_Arbeit']) && 
	        		isset($_POST['sql_Anschrift_Privat']) && 
					isset($_POST['sql_PLZ_Privat']) && 
					isset($_POST['sql_Stadt_Privat']) && 
					isset($_POST['sql_Anschrift_Arbeit']) && 
					isset($_POST['sql_PLZ_Arbeit']) && 
					isset($_POST['sql_Stadt_Arbeit'])) {
						
		        
		        // Test auf Validität
		        if (! is_numeric($_POST['sql_Km_zur_Arbeit'])) {
			        array_push($this->error_text, 'km Angabe muß eine Zahl sein.');
			        array_push($this->error_text, 'Ihre Daten konnten nicht gespeichert werden.');
			        return(false);
		        }
		        if ($_POST['sql_Km_zur_Arbeit'] <= 0) {
			        array_push($this->error_text, 'km Angabe muß eine positive Zahl sein');
			        array_push($this->error_text, 'Ihre Daten konnten nicht gespeichert werden.');
					return(false);
		        }
		        if (! is_numeric($_POST['sql_PLZ_Privat'])) {
			        array_push($this->error_text, 'PLZ Angabe muß eine Zahl sein');
			        array_push($this->error_text, 'Ihre Daten konnten nicht gespeichert werden.');
					return(false);
		        }
		        if (! is_numeric($_POST['sql_PLZ_Arbeit'])) {
			        array_push($this->error_text, 'PLZ Angabe muß eine Zahl sein');
			        array_push($this->error_text, 'Ihre Daten konnten nicht gespeichert werden.');
			        return(false);
		        }
		        if (strlen($_POST['sql_PLZ_Privat']) != 5) {
			    	array_push($this->error_text, 'PLZ Angabe muß 5-stellig sein');
			        array_push($this->error_text, 'Ihre Daten konnten nicht gespeichert werden.');
			        return(false);
		        }
		        if (strlen($_POST['sql_PLZ_Arbeit']) != 5) {
			        array_push($this->error_text, 'PLZ Angabe muß 5-stellig sein');
  			        array_push($this->error_text, 'Ihre Daten konnten nicht gespeichert werden.');
			        return(false);
		        }
		        
		        // create external user manually
		        if (isset($_POST['is_new_user']) && $_POST['is_new_user'] == 1 && isset($_POST['sql_Vorname']) && isset($_POST['sql_Nachname'])) {
					
					$ldap_kennung 			= $_POST['sql_Vorname'].'_'.$_POST['sql_Nachname'];
					$data['Data_entry'] 	= 'm';
					$ldap_vorname			= $_POST['sql_Vorname'];
					$ldap_nachname			= $_POST['sql_Nachname'];
				}
		        
				if ($ldap_kennung) {
					$limit = 1;
					$query = $this->db->get_where('teilnehmer', array('LDAP_Kennung' => $ldap_kennung), $limit);
					
					
					$data['Km_zur_Arbeit']		= $_POST['sql_Km_zur_Arbeit'];
					$data['Anschrift_Privat']	= $_POST['sql_Anschrift_Privat'];
					$data['PLZ_Privat']			= $_POST['sql_PLZ_Privat'];
					$data['Stadt_Privat']		= $_POST['sql_Stadt_Privat'];
					$data['Anschrift_Arbeit']	= $_POST['sql_Anschrift_Arbeit'];
					$data['PLZ_Arbeit']			= $_POST['sql_PLZ_Arbeit'];
					$data['Stadt_Arbeit']		= $_POST['sql_Stadt_Arbeit'];
					$data['Anrede']				= $_POST['gender'];
					
					$data['Unternehmen_id']		= $this->config->item('unternehmen_id');
					$data['LDAP_Kennung']		= $ldap_kennung;
					$data['Vorname']			= $ldap_vorname;
					$data['Name']				= $ldap_nachname;
					$data['Abteilung']			= $ldap_abteilung;
					$data['Veranstaltung_Jahr']	= $this->config->item('veranstaltung_jahr');
					$data['Veranstaltung_WRAM']	= 'y';					
					
					// change setting
					if ($query->num_rows() > 0) {
						
						if (isset($_POST['sql_team_change'])) {
							if (! $this->set_team($_POST['sql_team_change'], $ldap_kennung)) {
								return (false);
							}
						}
						elseif (isset($_POST['sql_delete_team'])) {
							if (! $this->set_team('delete', $ldap_kennung)) {
								return (false);
							}
						}
						
						$this->db->update('teilnehmer', $data, array('LDAP_Kennung' => $ldap_kennung));
						array_push($this->succ_text, 'Die Änderungen wurden erfolgreich gespeichert.');
					}
					
					// first time register
					else {
						
						$data['Anmelde_Datum']		= date('Y-m-d H:i:s');
						
						if (! $this->set_team($_POST['sql_team_change'], $ldap_kennung)) {
							return (false);
						}

						$this->db->insert('teilnehmer', $data);
						array_push($this->succ_text, 'Sie haben sich erfolgreich registriert.');
						$this->user_is_new = 1;
					}
				}
				else {
					array_push($this->error_text, 'Ihre LDAP Kennung fehlt.');
					return(false);					
				}		        	
	       	}
		   	else if (! $ldap_kennung) {
			       	array_push($this->succ_text, 'Neuer User wird angelegt.');
		    }
	        else {
		        if (isset($_POST['first_call'])) {
			        array_push($this->error_text, 'Es wurden nicht alle Pflichtfelder ausgefüllt.');
		       	}

		       	
	       	}
	    }
	    
	    public function get_open_teams($is_test = 0)
	    {
		    
		    if ($is_test) {
			    $query = $this->db->get_where('teams', 'id IN (1)');
		    }
		    else {
			    $query = $this->db->get_where('teams', 'id NOT IN (1)');
			    $this->db->order_by('Name');
		    }
			
			if ($query->num_rows() > 0) {
				$cnt = 0;
				
				foreach ($query->result() as $row) {
					if ($this->count_members($row->id) <= 3) {
                        if ((3 - $this->count_members($row->id)) == 0) continue;

                        $data[$cnt]['id'] = $row->id;
                        $data[$cnt]['Name'] = $row->Name;
                        $data[$cnt]['free'] = (3 - $this->count_members($row->id));
                        $cnt++;

					}
					else {
						// $data[$cnt]['id']	= $row->id;
						// $data[$cnt]['Name']	= $row->Name;
						// $data[$cnt]['free']	= 'keine';
						// $cnt++;
						$data = array();					
					}
				}
			}
			else { $data = array(); }
			return ($data);	    
	    }
	    
	    public function gen_html_from_open_teams($test = 0)
	    {
		    // <option value="1">Team 1</option>
		    $data = $this->get_open_teams($test);
		    $out = '';
		    
		    foreach($data as $k => $v) {
			    $out .= '<option value="'.$data[$k]['id'].'">'.$data[$k]['Name'].' (';
			    $out .= $data[$k]['free'];
			    
			    if ($data[$k]['free'] > 1) {
				    $out .= ' Plätze';
			    }
			    else {
				    $out .= ' Plätze';
			    }
			    $out .= ' frei)</option>'; 
		    }
		    
		    return ($out);
	    }
	    
	    public function pre_fill_entries($ldap_kennung, $ldap_nachname, $ldap_vorname, $ext_user = NULL)
	    {
		    
		    static $out;
		    
		    if (isset($_POST['sql_Km_zur_Arbeit'])) 	{ $out['pre_Km_zur_Arbeit'] 	= $_POST['sql_Km_zur_Arbeit']; }	else { $out['pre_Km_zur_Arbeit'] 	= NULL; }
		    if (isset($_POST['sql_Anschrift_Privat']))	{ $out['pre_Anschrift_Privat'] 	= $_POST['sql_Anschrift_Privat']; }	else { $out['pre_Anschrift_Privat']	= NULL; }
		    if (isset($_POST['sql_PLZ_Privat'])) 		{ $out['pre_PLZ_Privat'] 		= $_POST['sql_PLZ_Privat']; }		else { $out['pre_PLZ_Privat'] 		= NULL; }
		    if (isset($_POST['sql_Stadt_Privat'])) 		{ $out['pre_Stadt_Privat'] 		= $_POST['sql_Stadt_Privat']; }		else { $out['pre_Stadt_Privat'] 	= NULL; }
		    if (isset($_POST['sql_Anschrift_Arbeit'])) 	{ $out['pre_Anschrift_Arbeit']	= $_POST['sql_Anschrift_Arbeit']; }	else { $out['pre_Anschrift_Arbeit']	= NULL; }
		    if (isset($_POST['sql_PLZ_Arbeit'])) 		{ $out['pre_PLZ_Arbeit'] 		= $_POST['sql_PLZ_Arbeit']; }		else { $out['pre_PLZ_Arbeit'] 		= NULL; }
		    if (isset($_POST['sql_Stadt_Arbeit'])) 		{ $out['pre_Stadt_Arbeit'] 		= $_POST['sql_Stadt_Arbeit']; }		else { $out['pre_Stadt_Arbeit'] 	= NULL; }
			if (isset($_POST['sql_Anrede']) && $_POST['sql_Anrede'] == "m") {
				$out['pre_sql_anrede_m']	= 'active'; $out['pre_sql_gender'] = 'mars'; }	else { $out['pre_sql_anrede_m'] = NULL; }
			if (isset($_POST['sql_Anrede']) && $_POST['sql_Anrede'] == "w") {
				$out['pre_sql_anrede_w'] 	= 'active'; $out['pre_sql_gender'] = 'venus';}	else { $out['pre_sql_anrede_w'] = NULL; }
			if (! isset($out['pre_sql_gender'])) { $out['pre_sql_gender'] = 'transgender';}
		    
		    if ($ldap_kennung || $ext_user) {
				$limit = 1;
				if ($ext_user) { $ldap_kennung = $ext_user; }
				$query = $this->db->get_where('teilnehmer', array('LDAP_Kennung' => $ldap_kennung), $limit); 
						
				// if 1 row, preset with DB values
				if ($query->num_rows() > 0) {
					$row = $query->row();
						
				    if (! isset($out['pre_Km_zur_Arbeit']))		{ $out['pre_Km_zur_Arbeit'] 	= $row->Km_zur_Arbeit; }
					if (! isset($out['pre_Anschrift_Privat']))	{ $out['pre_Anschrift_Privat'] 	= $row->Anschrift_Privat; }
				    if (! isset($out['pre_PLZ_Privat'])) 		{ $out['pre_PLZ_Privat'] 		= $row->PLZ_Privat; }
				    if (! isset($out['pre_Stadt_Privat'])) 		{ $out['pre_Stadt_Privat'] 		= $row->Stadt_Privat; }
				    if (! isset($out['pre_Anschrift_Arbeit'])) 	{ $out['pre_Anschrift_Arbeit']	= $row->Anschrift_Arbeit; }
				    if (! isset($out['pre_PLZ_Arbeit'])) 		{ $out['pre_PLZ_Arbeit'] 		= $row->PLZ_Arbeit; }
				    if (! isset($out['pre_Stadt_Arbeit'])) 		{ $out['pre_Stadt_Arbeit'] 		= $row->Stadt_Arbeit; }
				    if (! isset($out['pre_sql_anrede_m']) && $row->Anrede== 'm')	{ $out['pre_sql_anrede_m']	= 'active'; $out['pre_sql_gender'] == 'mars'; }
				    if (! isset($out['pre_sql_anrede_w']) && $row->Anrede== 'w')	{ $out['pre_sql_anrede_w']	= 'active'; $out['pre_sql_gender'] == 'venus'; }
				    if (! isset($out['pre_sql_gender'])) { $out['pre_sql_gender'] == 'transgender'; }
				    			    
				    if ($this->get_team_name($row->Team_id)) {
					    $out['pre_team_name'] = $this->get_team_name($row->Team_id);	
				    }
				    else {
					    $out['pre_team_name'] = false;
				    }
				}
				else {
					$this->user_is_new		= 1;
					$out['pre_team_name']	= false;
				}
		    }
		    return ($out);
	    }
	    
	    public function set_team($team_change = '', $ldap_kennung = '')
	    {

			
		    if ($team_change == 'add' && isset($_POST['sql_team_join'])) {
			    
			    $this->db->update('teilnehmer', array('Team_id' => $_POST['sql_team_join']), array('LDAP_Kennung' => $ldap_kennung));
			    $team_name = $this->get_team_name($_POST['sql_team_join']);

				if ($team_name == '') {
				    array_push($this->error_text, 'Team Name darf nicht leer sein.');
				    array_push($this->error_text, 'Ihr Team konnte nicht gespeichert werden.');
				    return(false);
				}
				
				if (strlen($team_name) < 3) {
				    array_push($this->error_text, 'Team Name muß mindestens 3 Buchstaben lang sein.');
				    array_push($this->error_text, 'Ihr Team konnte nicht gespeichert werden.');
				    return(false);
				}
						    
			    array_push($this->succ_text, 'Sie wurden erfolgreich zum Team '.$team_name.' hinzugefügt.');
		    }
		    elseif ($team_change == 'new' && isset($_POST['sql_team_name'])) {
			    
			    if ($_POST['sql_team_name'] == '') {
				    array_push($this->error_text, 'Team Name darf nicht leer sein.');
				    array_push($this->error_text, 'Ihr Team konnte nicht gespeichert werden.');
				    return(false);
				}
				
				if (strlen($_POST['sql_team_name']) < 3) {
				    array_push($this->error_text, 'Team Name muß mindestens 3 Buchstaben lang sein.');
				    array_push($this->error_text, 'Ihr Team konnte nicht gespeichert werden.');
				    return(false);
				}
			 
			    $query = $this->db->get_where('teams', array('Name' => $_POST['sql_team_name']));
			    if ($query->num_rows() > 0) {
				     array_push($this->error_text, 'Team mit dem selben Namen existiert bereits. Probieren sie die Funktion "einem Team hinzufügen".');
					 array_push($this->error_text, 'Ihr Team konnte nicht angelegt werden.');
					 return(false);
			    }
			    
			    
			    $this->db->insert('teams', array('Name' => $_POST['sql_team_name']));

			    $team_id = $this->get_team_id_by_name($_POST['sql_team_name']);
			    $team_name = $this->get_team_name($team_id);
			    
			    array_push($this->succ_text, 'Sie haben erfolgreich das Team '.$team_name.' erstellt.');

			    $this->db->update('teilnehmer', array('Team_id'	=> $team_id), array('LDAP_Kennung' => $ldap_kennung));
			    array_push($this->succ_text, 'Sie wurden erfolgreich zum Team '.$team_name.' hinzugefügt.');
			    
		    }
		    elseif ($team_change == 'delete') {
			    
				$user = $this->get_tn_obj($ldap_kennung);
				$team_name = $this->get_team_name($user->Team_id);
                $this->db->update('teilnehmer', array('Team_id'	=> NULL), array('LDAP_Kennung' => $ldap_kennung));
                array_push($this->succ_text, 'Sie wurden erfolgreich aus dem Team '.$team_name.' entfernt.');
                
                if ($this->count_members($user->Team_id) == 0) {
	                
	                $this->db->where('id', $user->Team_id);
					$this->db->delete('teams');
	                array_push($this->succ_text, 'Das Team '.$team_name.' wurde gelöscht, da es keine Teilnehmer mehr hatte.');
                }
            }
            return (true);
	    }
	    
	    public function preset_km($user_id = 0, $web_date = NULL)
	    {
		    
		    if (preg_match("/(\d{2})-(\d{2})-(\d{4})/", $web_date, $my_date)) {
			    	
				$query = $this->db->get_where('fahrtenbuch', array('Teilnehmer_id' => $user_id, 'Datum'=> $my_date[3].'-'.$my_date[2].'-'.$my_date[1]), 1);
				if ($query->num_rows() > 0) {
					$row = $query->row();
					return array($row->Km_zur_Arbeit, $row->Km_Privat, $row->Bemerkungen);
				}
			}
	    }
	    	    
	    public function check_km($user_id = 0, $web_date = NULL)
        {
	        // Test auf Vollständigkeit
	        if ($web_date &&
	        	(isset($_POST['sql_arbeits_km']) ||
	            (isset($_POST['sql_sonstige_km']) && isset($_POST['sql_bemerkungen'])))) {
				
				
				preg_match("/(\d{2})-(\d{2})-(\d{4})/", $web_date, $my_date);

				$limit = 1;
				$query = $this->db->get_where('fahrtenbuch', array('Teilnehmer_id' => $user_id, 'Datum'=> $my_date[3].'-'.$my_date[2].'-'.$my_date[1]), $limit);
				
				
				$data['Datum']				= $my_date[3].'-'.$my_date[2].'-'.$my_date[1];
				$data['Km_zur_Arbeit']		= isset($_POST['sql_arbeits_km'])?$_POST['sql_arbeits_km']:0;
				$data['Km_Privat']	 		= isset($_POST['sql_sonstige_km'])?$_POST['sql_sonstige_km']:0;
				$data['Bemerkungen']		= isset($_POST['sql_bemerkungen'])?$_POST['sql_bemerkungen']:'';
				$data['Teilnehmer_id']		= $user_id;
				
					
				if ($query->num_rows() > 0) {
					
					$this->db->where('Teilnehmer_id',$user_id);
					$this->db->where('Datum', $data['Datum']);
					$this->db->update('fahrtenbuch', $data);
					array_push($this->succ_text, 'Sie haben erfolgreich Ihre Kilometer geändert.');
				}
				else {
					$this->db->insert('fahrtenbuch', $data);
					array_push($this->succ_text, 'Sie haben erfolgreich Ihre Kilometer gemeldet.');
				}
			}
		}
		
		public function gen_user_list($user_id = NULL)
		{
            global $lang;
			if ($user_id) {
				
	    		$query = $this->db->select('*, week(`Datum`) AS week')->order_by('Datum', 'ASC')->get_where('fahrtenbuch', array('Teilnehmer_id' => $user_id)); 
				$ret = '';
				

				
				if ($query->num_rows() > 0) {
					
					$ret 		= $this->eintragen_model->gen_table_start();
					$ret	   .= $this->eintragen_model->gen_table_head(array('Datum'.$lang, 'km zur Arbeit', 'sonstige km','Summe km'));
					$ret	   .= $this->eintragen_model->gen_tbody_start();
				
					$cnt = 0;
					$Km_sum = 0;

					foreach ($query->result() as $row) {
						
						$cnt++;
						
						preg_match("/(\d{4})-(\d{2})-(\d{2})/", $row->Datum, $my_date);
						$date1 = implode ("-", array($my_date[3],$my_date[2],$my_date[1]));
						$date2 = implode (".", array($my_date[3],$my_date[2],$my_date[1]));
						
						$ret .= '					<tr>';
						$ret .= '	                  <td><a href="'. site_url("main/eintragen/" . $date1) . '">'.$date2 .'</a></td>';
						$ret .= '	                  <td>'. $row->Km_zur_Arbeit .'</td>';
						$ret .= '	                  <td>'. $row->Km_Privat .'</td>';
						$ret .= '	                  <td>'. ( $row->Km_zur_Arbeit + $row->Km_Privat ) .'</td>';
						$ret .= '	                </tr>';
							
						$Km_sum += ( $row->Km_zur_Arbeit + $row->Km_Privat );
					}
					
					$ret .= '<tr class="info"><td>Summe</td><td></td><td></td><td>'.$Km_sum.'</td></tr>';
					
					$ret	.= $this->eintragen_model->gen_tbody_end();
					$ret	.= $this->eintragen_model->gen_table_end();
			
					return ($ret);
				}
				else { return false; }
			}
			else {
				return false;
			}
		}
		

		
		public function gen_user_chart($user_id = NULL)
		{
            global $lang;
			if ($user_id) {
				
				$query = $this->db->query("SELECT t.Vorname as Vorname, t.Name as Name, Teilnehmer_id, (sum(f.Km_zur_Arbeit)+sum(f.Km_Privat)) as Km_Gesamt FROM `fahrtenbuch` f, teilnehmer t where t.id=f.Teilnehmer_id GROUP BY Teilnehmer_id ORDER BY 2 DESC");
				$ret = '';
				$cnt = 0;
				if ($query->num_rows() > 0) {
					
					$ret 		= $this->gen_table_start();
					$ret	   .= $this->gen_table_head(array('Rang'.$lang, 'Name', 'Gesamt km'));
					$ret	   .= $this->gen_tbody_start();
					
					foreach ($query->result() as $row) {
						$cnt++;
						
						$ret .= '					<tr>';
						$ret .= '	                  <td>'.$cnt.'</td>';
						$ret .= '	                  <td>'. $row->Vorname . " " . $row->Name .'</td>';
						$ret .= '	                  <td>'. $row->Km_Gesamt .'</td>';
						$ret .= '	                </tr>';
						

						
					}
					
					$ret	.= $this->gen_tbody_end();
					$ret	.= $this->gen_table_end();
					
					return ($ret);
				}
				else { return false; }
			}
			else {
				return false;
			}
		}
		
		public function gen_group_list($group_id = NULL)
		{
            global $lang;
			if ($group_id) {
				
	    		$query = $this->db->query("SELECT Datum, SUM(f.`Km_zur_Arbeit`) as Km_Arbeit_sum, SUM(f.`Km_Privat`) as Km_Privat_sum
					FROM teilnehmer t JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`) WHERE Team_id IN($group_id) GROUP BY Datum"); 
				$ret = '';
				
				if ($query->num_rows() > 0) {
					
					$ret 		= $this->eintragen_model->gen_table_start();
					$ret	   .= $this->eintragen_model->gen_table_head(array('Datum'.$lang, 'km zur Arbeit', 'sonstige km','Summe km'));
					$ret	   .= $this->eintragen_model->gen_tbody_start();
				
					$cnt = 0;
					$Km_sum = 0;

					foreach ($query->result() as $row) {
						
						$cnt++;
						
						preg_match("/(\d{4})-(\d{2})-(\d{2})/", $row->Datum, $my_date);
						$date1 = implode ("-", array($my_date[3],$my_date[2],$my_date[1]));
						$date2 = implode (".", array($my_date[3],$my_date[2],$my_date[1]));
						
						$ret .= '					<tr>';
						$ret .= '	                  <td><a href="'. site_url("main/eintragen/" . $date1) . '">'.$date2 .'</a></td>';
						$ret .= '	                  <td>'. $row->Km_Arbeit_sum .'</td>';
						$ret .= '	                  <td>'. $row->Km_Privat_sum .'</td>';
						$ret .= '	                  <td>'. ( $row->Km_Arbeit_sum + $row->Km_Privat_sum ) .'</td>';
						$ret .= '	                </tr>';
							
						$Km_sum += ( $row->Km_Arbeit_sum + $row->Km_Privat_sum );
					}
					
					$ret .= '<tr class="info"><td>Summe</td><td></td><td></td><td>'.$Km_sum.'</td></tr>';
					
					$ret	.= $this->eintragen_model->gen_tbody_end();
					$ret	.= $this->eintragen_model->gen_table_end();
			
					return ($ret);
				}
				else { return false; }
			}
			else {
				return false;
			}
		}
		
		public function gen_group_members($group_id = NULL)
		{
			$ret = '';
			
			if ($group_id){

			
				$query = $this->db->query("SELECT SUM(f.`Km_zur_Arbeit`) AS Km_Arbeit_sum, SUM(f.`Km_Privat`) AS Km_Privat_sum, SUM(f.`Km_zur_Arbeit`)+SUM(f.`Km_Privat`) AS Km_ges_sum ,te.Name, t.Vorname, t.Name
						FROM teilnehmer t JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`)
						JOIN teams te ON (t.`Team_id` = te.`id`)
						WHERE Team_id in ($group_id)
						GROUP BY `Teilnehmer_id`"); 
				if ($query->num_rows() > 0) {
					
					$ret 		= $this->gen_table_start();
					$ret	   .= $this->gen_table_head(array('Vorname', 'Nachname', 'km zur Arbeit', 'sonstige km', 'Gesamt km'));
					$ret	   .= $this->gen_tbody_start();
						
					foreach ($query->result() as $row) {
							$ret .= '					<tr>';
							$ret .= '	                  <td>'. $row->Vorname .'</td>';
							$ret .= '	                  <td>'. $row->Name .'</td>';
							$ret .= '	                  <td>'. $row->Km_Arbeit_sum .'</td>';
							$ret .= '	                  <td>'. $row->Km_Privat_sum .'</td>';
							$ret .= '	                  <td>'. $row->Km_ges_sum .'</td>';
							$ret .= '	                </tr>';
					}
					
					$ret	.= $this->gen_tbody_end();
					$ret	.= $this->gen_table_end();
					
					return ($ret);
				}
							
			}
			else {
				return ('Sie gehören zur Zeit keinem Team an. Dies können Sie in den Einstellungen ändern.');
			}
			
		}
		
		public function gen_admin_menu($ldap_kennung = NULL)
		{
	    	if ($ldap_kennung) {
	    		$query = $this->db->get_where('teilnehmer', array('LDAP_Kennung' => $ldap_kennung), 1); 
						
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						if ($row->Is_admin == 'y') {
							$dat['menu'] = $this->admin_gets_alphabet();
							return ($this->load->view('radel_admin_menu', $dat, TRUE));
						}
					}
				}
				else { return false; }
	    	}   
		}
		
		public function gen_group_chart($group_id = NULL)
		{
			if ($group_id) {
				
				$query = $this->db->query("SELECT SUM(f.`Km_zur_Arbeit`) AS Km_Arbeit_sum, SUM(f.`Km_Privat`) AS Km_Privat_sum, SUM(f.`Km_zur_Arbeit`)+SUM(f.`Km_Privat`) AS Km_ges_sum ,te.Name
					FROM teilnehmer t JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`)
					JOIN teams te ON (t.`Team_id` = te.`id`)
					GROUP BY Team_id");
				$ret = '';
				$cnt = 0;
				if ($query->num_rows() > 0) {
					
					$ret 		= $this->gen_table_start();
					$ret	   .= $this->gen_table_head(array('Rang', 'Team Name', 'Gesamt km'));
					$ret	   .= $this->gen_tbody_start();
					
					foreach ($query->result() as $row) {
						$cnt++;
						
						$ret .= '					<tr>';
						$ret .= '	                  <td>'.$cnt.'</td>';
						$ret .= '	                  <td>'. $row->Name .'</td>';
						$ret .= '	                  <td>'. $row->Km_ges_sum .'</td>';
						$ret .= '	                </tr>';
						

						
					}
					
					$ret	.= $this->gen_tbody_end();
					$ret	.= $this->gen_table_end();
					
					return ($ret);
				}
				else { return false; }
			}
			else {
				return false;
			}
		}
		
		public function gen_table_start()
		{
			return ('					<table class="table table-striped table-bordered table-responsive">'."\n");
		}
		
		public function gen_table_end()
		{
			return ('					</table>'."\n");
		}
		
		public function gen_tbody_start()
		{
			return ('              <tbody>'."\n");
		}

		public function gen_tbody_end()
		{
			return ('              </tbody>'."\n");
		}
				
		public function gen_table_head($elem = array())
		{
			$ret  = '';
			$ret .= '			  <thead>';
 			$ret .= '               <tr>';
 			foreach ($elem as $el) {
	 			$ret .= '                 <th>'.$el.'</th>';
 			}
			$ret .= '                </tr>';
 			$ret .= '             </thead>';
 			return ($ret);
		}
	    
	    public function gen_js_user_data($user_id = NULL)
	    {
			
			date_default_timezone_set('UTC');

		    $date = $this->config->item('veranstaltung_start_date');
		    $end_date = $this->config->item('veranstaltung_end_date');
		    
		    $km = array();
		    $ret = '';
		    $ret_date_arr = array();
		    $ret_priv_arr = array();
		    $ret_work_arr = array();
		    
		    $query = $this->db->order_by('Datum', 'asc')->get_where('fahrtenbuch', array('Teilnehmer_id' => $user_id)); 
		
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$km[$row->Datum]['Arbeit'] = $row->Km_zur_Arbeit;
					$km[$row->Datum]['Privat'] = $row->Km_Privat;
				}
			}
						
		    while (strtotime($date) <= strtotime($end_date)) {
				
				preg_match("/(\d{4})-(\d{2})-(\d{2})/", $date, $my_date);
				
				array_push($ret_date_arr, "'".$my_date[3].'.'.$my_date[2].'.'.$my_date[1]."'");

				if (isset($km[$date]['Arbeit'])) {
					array_push($ret_work_arr, $km[$date]['Arbeit']);
				}
				else {
					array_push($ret_work_arr, 0);
				}
				
				if (isset($km[$date]['Privat'])) {
					array_push($ret_priv_arr, $km[$date]['Privat']);
				}
				else {
					array_push($ret_priv_arr, 0);
				}

				$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));


			}


			return (array(join(',',$ret_date_arr), join(',',$ret_work_arr), join(',',$ret_priv_arr)));
	    }
	    
	    public function gen_js_group_data($group_id = NULL)
	    {
			
			date_default_timezone_set('UTC');

		    $date = $this->config->item('veranstaltung_start_date');
		    $end_date = $this->config->item('veranstaltung_end_date');
		    
		    $km = array();
		    $ret = '';
		    $ret_date_arr = array();
		    $ret_priv_arr = array();
		    $ret_work_arr = array();
		    
		    if ($group_id) {
		    	if(is_numeric($group_id)) {
					$query = $this->db->query("SELECT Datum, SUM(f.`Km_zur_Arbeit`) as Km_Arbeit_sum, SUM(f.`Km_Privat`) as Km_Privat_sum
						FROM teilnehmer t JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`) WHERE Team_id IN($group_id) GROUP BY Datum");
				} else {
					$query = $this->db->query("SELECT Datum, SUM(f.`Km_zur_Arbeit`) as Km_Arbeit_sum, SUM(f.`Km_Privat`) as Km_Privat_sum
						FROM teilnehmer t JOIN fahrtenbuch f ON (t.id = f.`Teilnehmer_id`) WHERE Abteilung = '$group_id' GROUP BY Datum");
				}
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						$km[$row->Datum]['Arbeit'] = $row->Km_Arbeit_sum;
						$km[$row->Datum]['Privat'] = $row->Km_Privat_sum;
					}
				}
							
			    while (strtotime($date) <= strtotime($end_date)) {
					
					preg_match("/(\d{4})-(\d{2})-(\d{2})/", $date, $my_date);
					
					array_push($ret_date_arr, "'".$my_date[3].'.'.$my_date[2].'.'.$my_date[1]."'");
	
					if (isset($km[$date]['Arbeit'])) {
						array_push($ret_work_arr, $km[$date]['Arbeit']);
					}
					else {
						array_push($ret_work_arr, 0);
					}
					
					if (isset($km[$date]['Privat'])) {
						array_push($ret_priv_arr, $km[$date]['Privat']);
					}
					else {
						array_push($ret_priv_arr, 0);
					}
	
					$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	
	
				}
				return (array(join(',',$ret_date_arr), join(',',$ret_work_arr), join(',',$ret_priv_arr)));
			}
	    }
	    
	    public function admin_gets_alphabet()
	    {
		    
		    $query = $this->db->query("SELECT SUBSTRING(`Name`,1,1) AS letter FROM teilnehmer t WHERE `Data_entry`='m' AND id NOT IN (1) GROUP BY 1");
		    $ret = '';
		    if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$ret .= '<li class="dropdown-submenu"><a tabindex="-1" href="#">...'.$row->letter.'</a>';
					$ret .= $this->admin_gets_names($row->letter);
					$ret .= '</li>';
				}
			}
			return ($ret);
	    }
	    
	    public function admin_gets_names($letter = NULL)
	    {
		    if ($letter) {
				$query = $this->db->query("SELECT Vorname, Name FROM teilnehmer WHERE `Data_entry`='m' AND id NOT IN (1) AND SUBSTRING(`Name`,1,1) LIKE '$letter'");
			    $ret = '';
			    $cnt = 0;
			    if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						$cnt++;
						if ($cnt == 1) {
							$ret .= '';
							$ret .= '<ul class="dropdown-menu">'. "\n";
						}
                        $ret .= '                <li><a href="main/einstellung/'.$row->Vorname."_".$row->Name.'"><i class="fa fa-cog"></i></a><a href="main/eintragen/'.$row->Vorname."_".$row->Name.'"><i class="fa fa-bicycle"></i> '.$row->Vorname." ".$row->Name.'</a></li>';
                        $ret .= "\n";
					}
					$ret .= "</ul>\n\n";
				}
		    }
		    return ($ret);	    

	    }
	    
	    public function is_user_new()
	    {
		    return ($this->user_is_new);
	    }

	    
	    public function is_user_registered($ldap_kennung = NULL)
	    {
	    	if ($ldap_kennung) {
	    		$query = $this->db->get_where('teilnehmer', array('LDAP_Kennung' => $ldap_kennung)); 
						
				if ($query->num_rows() > 0) {
					$this->user_is_registered = 1;
					return ($this->user_is_registered);
				}
				else { return false; }
	    	}   
	    }
	    
	    public function get_team_id_by_name($name = '') {
		    $query = $this->db->get_where('teams', array('Name' => $name)); 
						
			if ($query->num_rows() > 0) {
				$row = $query->row();
				return ($row->id);
			}
		    
	    }
	    
	    public function get_tn_obj($ldap_kennung)
	    {
		    $query = $this->db->get_where('teilnehmer', array('LDAP_Kennung' => $ldap_kennung));
		    if ($query->num_rows() > 0) {
				$row = $query->row();
				return ($row);
			}
			else { return false; }
	    }
	    
	    public function get_tn_obj_by_id($id)
	    {
		    $query = $this->db->get_where('teilnehmer', array('id' => $id));
		    if ($query->num_rows() > 0) {
				$row = $query->row();
				return ($row);
			}
			else { return false; }
	    }
	    
	    public function get_team_name($id = 0)
	    {
		    $query = $this->db->get_where('teams', array('id' => $id)); 
						
			if ($query->num_rows() > 0) {
				$row = $query->row();
				return ($row->Name);
			}
			else {
				return (false);
			}
	    }
	    
	    public function count_members($id = 0)
	    {
		    $sql = "SELECT * FROM `teilnehmer` WHERE `Team_id` IN (?)";
			$query = $this->db->query($sql, array($id));
					    
			return ($query->num_rows());
	    }

        public function dyn_info()
        {
	        static $out = NULL;
	        if (count($this->succ_text) > 0) {
		        foreach ($this->succ_text as $value) {
			        $out .= '<p class="bg-success">'.$value.'</p>';
				}
	        }
	        if (count($this->error_text) > 0) {
		        foreach ($this->error_text as $value) {
			        $out .= '<p class="bg-danger">'.$value.'</p>';
				}
	        }
	        return ($out);
        }

}
