<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{		  	 
		$this->load->model('ldap_model');
		$this->ldap_model->ldap_conn();
		$this->load->model('eintragen_model');

		$navi['active_1'] 	= '';
		$navi['active_2'] 	= '';
		$navi['active_3']	= '';
		$navi['user']		= $this->ldap_model->ldap_vorname  . " " . $this->ldap_model->ldap_nachname;
		$navi['admin']		= $this->eintragen_model->gen_admin_menu($this->ldap_model->ldap_kennung);

		$data['navigation'] = $this->load->view('radel_navigation', $navi, TRUE);
		$data['headline']	= '';
		$data['body_class']	= 'start';
		$data['content']	= $this->load->view('radel_index', '', TRUE);
		$data['server']     = $_SERVER['SERVER_NAME'];
    		$this->load->view('radel_main_template', $data);
	}
	
	public function einstellung($ext_user = NULL)
	{	
		$this->load->model('ldap_model');
		$this->ldap_model->ldap_conn();

		$this->load->helper('form');
		$this->load->model('eintragen_model');
		
		// $this->eintragen_model->check_and_write_entries($this->ldap_model->ldap_kennung, $this->ldap_model->ldap_nachname, $this->ldap_model->ldap_vorname, $this->ldap_model->ldap_abteilung, NULL);
		$navi['active_1']	= 'active';
		$navi['active_2']	= '';
		$navi['active_3']	= '';
		$navi['user']		= $this->ldap_model->ldap_vorname  . " " . $this->ldap_model->ldap_nachname;
		$navi['admin']		= $this->eintragen_model->gen_admin_menu($this->ldap_model->ldap_kennung);

		if ($ext_user) {
			if ($ext_user == 'new') {
				
				$data['pre_sql_gender'] = 'transgender';
				$data['pre_sql_anrede_m'] = NULL;
				$data['pre_sql_anrede_w'] = NULL;
				$data['pre_Km_zur_Arbeit'] = NULL;
				$data['pre_Anschrift_Privat'] = NULL;
				$data['pre_PLZ_Privat'] = NULL;
				$data['pre_Stadt_Privat'] = NULL;
				$data['pre_Anschrift_Arbeit'] = NULL;
				$data['pre_PLZ_Arbeit'] = NULL;
				$data['pre_Stadt_Arbeit'] = NULL;
				$data['pre_team_name'] = NULL;
				$data['pre_is_new_user'] = 1;
				
				$this->ldap_model->ldap_kennung = $ext_user;
				$this->ldap_model->ldap_nachname = '';
				$this->ldap_model->ldap_vorname = '';
				
				$this->eintragen_model->pre_fill_entries(NULL,NULL,NULL,$ext_user);		
			}
			else {
				$user = $this->eintragen_model->get_tn_obj($ext_user);
				
				if ($user) {
					$this->ldap_model->ldap_kennung = $ext_user;
					$this->ldap_model->ldap_nachname = $user->Name;
					$this->ldap_model->ldap_vorname = $user->Vorname;
					
					$data = $this->eintragen_model->pre_fill_entries($this->ldap_model->ldap_kennung, $this->ldap_model->ldap_nachname, $this->ldap_model->ldap_vorname,$ext_user);	
					
					$data['pre_Km_zur_Arbeit'] = $user->Km_zur_Arbeit;
					$data['pre_Anschrift_Privat'] = $user->Anschrift_Privat;
					$data['pre_PLZ_Privat'] = $user->PLZ_Privat;
					$data['pre_Stadt_Privat'] = $user->Stadt_Privat;
					$data['pre_Anschrift_Arbeit'] = $user->Anschrift_Arbeit;
					$data['pre_PLZ_Arbeit'] = $user->PLZ_Arbeit;
					$data['pre_Stadt_Arbeit'] = $user->Stadt_Arbeit;
					
					$date['pre_ext_user']		= $ext_user;
					
					$data['pre_team_name'] = $this->eintragen_model->get_team_name($user->Team_id);;
					$data['pre_is_new_user'] = 0;
				
				}
				else {
					// user not found
					array_push($this->eintragen_model->error_text, 'Diesen User gibt es nicht.');
					
					$this->ldap_model->ldap_kennung = $ext_user;
					$this->ldap_model->ldap_nachname = NULL;
					$this->ldap_model->ldap_vorname = NULL;
					
					$data['pre_sql_gender'] = 'transgender';
					$data['pre_sql_anrede_m'] = NULL;
					$data['pre_sql_anrede_w'] = NULL;
					$data['pre_Km_zur_Arbeit'] = NULL;
					$data['pre_Anschrift_Privat'] = NULL;
					$data['pre_PLZ_Privat'] = NULL;
					$data['pre_Stadt_Privat'] = NULL;
					$data['pre_Anschrift_Arbeit'] = NULL;
					$data['pre_PLZ_Arbeit'] = NULL;
					$data['pre_Stadt_Arbeit'] = NULL;
					$data['pre_team_name'] = NULL;
					$data['pre_is_new_user'] = 1;
				
				}
			}
		}
		else {
			$data	= $this->eintragen_model->pre_fill_entries($this->ldap_model->ldap_kennung, $this->ldap_model->ldap_nachname, $this->ldap_model->ldap_vorname);
			$data['pre_is_new_user'] = 0;

		}
		
		$this->eintragen_model->check_and_write_entries($this->ldap_model->ldap_kennung, $this->ldap_model->ldap_nachname, $this->ldap_model->ldap_vorname, $this->ldap_model->ldap_abteilung, NULL);

		$data['body_class']	= 'einstellung';
		if ($this->eintragen_model->is_user_new() || $ext_user == 'new') {
			$data['user_is_new']	= $this->load->view('radel_user_is_new', '', TRUE);
			$data['button_name']	= 'eintragen';
		}
		else {
			$data['user_is_new']	= '';
			$data['button_name']	= 'ändern';
		}
		
		if (isset($_POST['pre_ext_user'])) {
			$data['pre_ext_user']	= $ext_user;
		}
		else {
			$data['pre_ext_user']	= NULL;
		}
		
		$data['navigation'] = $this->load->view('radel_navigation', $navi, TRUE);
		
		$data['headline']		= 'Einstellung';
		$data['dyn_info']   	= $this->eintragen_model->dyn_info();
		$data['ldap_nachname']	= $this->ldap_model->ldap_nachname;
		$data['ldap_vorname']	= $this->ldap_model->ldap_vorname;
		
		$data['open_teams']		= $this->eintragen_model->gen_html_from_open_teams();
		
		$data['content']		= form_open('main/einstellung/' . $ext_user);
		$data['content']   	   .= $this->load->view('radel_einstellung', $data, TRUE);
		$data['content']       .= form_close();
		$data['server']			= $_SERVER['SERVER_NAME'];
		
    	$this->load->view('radel_main_template', $data);
	}
	
	public function ajax_eintragen($web_date = NULL)
	{
		$this->load->model('ldap_model');
		$this->ldap_model->ldap_conn();
		$this->load->model('eintragen_model');
		
		$user = $this->eintragen_model->get_tn_obj($this->ldap_model->ldap_kennung);
		$data = $this->eintragen_model->check_km($user->id, $web_date);
		
		$data['dyn_info']   	= $this->eintragen_model->dyn_info();

				
		$this->load->view('radel_popup', $data);

		
	}
		
	public function eintragen($web_date = NULL, $user_in = NULL)
	{	
		$this->load->model('ldap_model');
		$this->load->helper('form');
		$this->ldap_model->ldap_conn();
		$this->load->model('eintragen_model');

		$navi['active_1']	= '';
		$navi['active_2']	= 'active';
		$navi['active_3']	= '';
		$navi['user']		= $this->ldap_model->ldap_vorname  . " " . $this->ldap_model->ldap_nachname;
		$navi['admin']		= $this->eintragen_model->gen_admin_menu($this->ldap_model->ldap_kennung);
		
		if (isset($_POST['remote_user']) && $_POST['remote_user'] != '') {
			$this->ldap_model->ldap_kennung = $_POST['remote_user'];
		}
		if ($web_date && ! preg_match("/\d{2}-\d{2}-\d{4}/", $web_date)) {
			$this->ldap_model->ldap_kennung = $web_date;
		}
		if ($user_in) {
 			$this->ldap_model->ldap_kennung = $user_in;
		}
		
		// $this->eintragen_model->is_user_new()) 
		if ($user = $this->eintragen_model->get_tn_obj($this->ldap_model->ldap_kennung)) {
			
			$data = $this->eintragen_model->check_km($user->id, $web_date);
			
			if ($web_date && ! preg_match("/\d{2}-\d{2}-\d{4}/", $web_date)) {
				$data['remote_user'] = $web_date;
				$web_date = '';
			}
			else if (isset($_POST['remote_user'])) {
				$data['remote_user'] = $_POST['remote_user'];
			}
			else if ($user_in) {
				$data['remote_user'] = $user_in;
			}
			else {
				$data['remote_user'] = NULL;
			}
			
			if ($data['remote_user']) {
				array_push($this->eintragen_model->succ_text, 'Sie ändern die km von '.$user->Vorname.' '.$user->Name);
			}

			list($data['km_work'], $data['km_priv'],$data['km_begr']) = $this->eintragen_model->preset_km($user->id, $web_date);
			
			if ($web_date) {
				$data['check_1']	= '<span class="green">&#x2713;</span>';
			}
			else {
				$data['check_1']	= '<span class="red">&#x2717;</span>';
			}
			
			if ($data['km_work'] > 0 || $data['km_priv'] > 0) {
				$data['check_2']	= '<span class="green">&#x2713;</span>';
			}
			else {
				$data['check_2']	= '<span class="red">&#x2717;</span>';
			}
			
					
			$data['headline']	= 'Kilometer eintragen';
			$data['dyn_info']   = $this->eintragen_model->dyn_info();
			
			$data['km_arbeit']	= $user->Km_zur_Arbeit;
			$data['datum']		= preg_match("/\d{2}-\d{2}-\d{4}/", $web_date)?str_replace('-','.',$web_date):'- Bitte zuerst ein Datum wählen';
			$data['content']	= form_open('main/eintragen/'. $web_date, 'class="form-inline"');
			$data['content']   .= $this->load->view('radel_eintragen', $data, TRUE);
			$data['content']   .= form_close();
			
		}
		else {
			$data['headline']	= 'Bitte zuerst anmelden!';
			$data['content']	= '';
		}

		$data['navigation'] = $this->load->view('radel_navigation', $navi, TRUE);
		$data['body_class']	= 'eintragen';
		$data['server']     = $_SERVER['SERVER_NAME'];

    	$this->load->view('radel_main_template', $data);
	}	
	
	public function charts($type = 'ich')
	{	
		$this->load->model('ldap_model');
		$this->ldap_model->ldap_conn();
		$this->load->model('eintragen_model');
		$this->load->helper('url');

		$navi['active_1']	= '';
		$navi['active_2']	= '';
		$navi['active_3']	= 'active';
		$navi['user']		= $this->ldap_model->ldap_vorname  . " " . $this->ldap_model->ldap_nachname;
		$navi['admin']		= $this->eintragen_model->gen_admin_menu($this->ldap_model->ldap_kennung);

		$data['navigation'] = $this->load->view('radel_navigation', $navi, TRUE);
		
		$data['headline']	= 'Statistiken: ';
		$data['body_class']	= 'charts';
		$data['server']     = $_SERVER['SERVER_NAME'];
		if ($user = $this->eintragen_model->get_tn_obj($this->ldap_model->ldap_kennung)) {
			
			if ($type == 'ich') { $data['headline'] .= 'ich'; }
			else if ($type == 'team' && $user->Team_id)  { $data['headline'] .= 'mein Team "' . $this->eintragen_model->get_team_name($user->Team_id) . '"';  }
			else if ($type == 'firma') { $data['headline'] .= 'meine Firma';}
			else if ($type == 'team') { $data['headline'] .= 'kein Team.'; }
		
			if ($type == 'ich')	{
				list($dates, $km_work, $km_priv) = $this->eintragen_model->gen_js_user_data($user->id);
				$data['js']			 = 'var my_labels = ['.$dates.'];'."\n";
				$data['js']			.= 'var km_work	  = ['.$km_work.'];'."\n";
				$data['js']			.= 'var km_priv	  = ['.$km_priv.'];'."\n";
	
				$data['table']		= $this->eintragen_model->gen_user_list($user->id);
				$data['table']	   .= $this->eintragen_model->gen_user_chart($user->id);
			}
			
			else if ($type == 'dep' && $user->Abteilung)	{
				list($dates, $km_work, $km_priv) = $this->eintragen_model->gen_js_group_data($user->Abteilung);
				$data['js']			 = 'var my_labels = ['.$dates.'];'."\n";
				$data['js']			.= 'var km_work	  = ['.$km_work.'];'."\n";
				$data['js']			.= 'var km_priv	  = ['.$km_priv.'];'."\n";
	
				$data['table']		= $this->eintragen_model->gen_group_members($user->Abteilung);
				$data['table']	   .= $this->eintragen_model->gen_group_list($user->Abteilung);
				$data['table']	   .= $this->eintragen_model->gen_group_chart($user->Abteilung);
			} else if ($type == 'team' && $user->Team_id)	{
				list($dates, $km_work, $km_priv) = $this->eintragen_model->gen_js_group_data($user->Team_id);
				$data['js']			 = 'var my_labels = ['.$dates.'];'."\n";
				$data['js']			.= 'var km_work	  = ['.$km_work.'];'."\n";
				$data['js']			.= 'var km_priv	  = ['.$km_priv.'];'."\n";

				$data['table']		= $this->eintragen_model->gen_group_members($user->Team_id);
				$data['table']	   .= $this->eintragen_model->gen_group_list($user->Team_id);
				$data['table']	   .= $this->eintragen_model->gen_group_chart($user->Team_id);
			}
			else if ($type == 'team') {
				$data['table']		= 'Sie gehören zur Zeit keinem Team an.';
			}
			else {
				$data['table']		= '<iframe src="https://www.wer-radelt-am-meisten.de/#charts" style="border: 0px; width: 95%; height: 95%;"> </iframe>';
			}


		}
		else {
			$data['headline']	= 'Bitte zuerst anmelden!';
			$data['content']	= '';
			$data['table']		= '';
		}
				
		$data['content']	= $this->load->view('radel_charts', $data, TRUE);

		
    	$this->load->view('radel_main_template', $data);
	}

}
