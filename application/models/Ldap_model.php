<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ldap_model extends CI_Model {

        public $title;
        public $content;
        public $date;
        
        public $ldap_vorname;
        public $ldap_nachname;
        public $ldap_kennung;
        public $ldap_abteilung;
        
        public $ldap_tel;
        public $ldap_email;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function ldap_conn()
        {
//	        print_r($_SERVER);
	        // apache liefert den User (User ist eingeloggt
	    	if (isset($_SERVER['REMOTE_USER']) && $_SERVER['REMOTE_USER'] != 'test123') {
		        $user = explode("@", $_SERVER['REMOTE_USER']);
		        
		        
	            // Verbindung zu LDAP
	            $this->ldap_con  = ldap_connect($this->config->item('ldap_server'));
	
	            if ($this->ldap_con == false) {
	                throw new Exception("Could not connect to server. Error is " . ldap_error($ldap_con));
	                return false;
	            }
	            
	            ldap_set_option($this->ldap_con, LDAP_OPT_PROTOCOL_VERSION,3);
	            ldap_set_option($this->ldap_con, LDAP_OPT_REFERRALS,0);
	
	            $this->ldap_bound = ldap_bind($this->ldap_con, $this->config->item('ldap_user'), $this->config->item('ldap_pass'));
	
	            if ($this->ldap_bound == false) {
	                //throw new Exception("Could not bind to server. Error is " . ldap_error($ldap_con))
	                return false;
	            }
	
	            $result = ldap_search($this->ldap_con, $this->config->item('ldap_dn'), "(samaccountname=". $user[0] . ")");
	            if ($result == false) {
	                //throw new Exception("Error in query\n" .ldap_error($ldap_con)."\n");
	                echo "Error in search query: ".ldap_error($this->ldap_con);
                    return false;
            	    }
	                
	            $data = ldap_get_entries($this->ldap_con, $result);

	            ldap_close($this->ldap_con);
	
	            $this->ldap_vorname   = @$data[0]["givenname"][0];
	            $this->ldap_nachname  = @$data[0]["sn"][0];
	            $this->ldap_kennung   = @$data[0]["cn"][0];
	            $this->ldap_abteilung = @$data[0]["department"][0];
	            
	            //?
	            $this->ldap_tel       = @$data[0]["telephonenumber"][0];
	            $this->ldap_email     = @$data[0]["mail"][0];
			}
			elseif ($_SERVER['REMOTE_USER'] == 'test123') {
				$this->ldap_vorname		= 'Max';
		        $this->ldap_nachname	= 'Mustermann';
				$this->ldap_kennung		= 'M0001';
		        $this->ldap_abteilung	= 'N.N.';
		        
				$data['ldap_vorname']	= $this->ldap_vorname;
		        $data['ldap_nachname']	= $this->ldap_nachname;
				$data['ldap_kennung']	= $this->ldap_kennung;
		        $data['ldap_abteilung']	= $this->ldap_abteilung;
			}
			// wenn nicht eingeloggt, liefer "John Doe" Dummy User
			else {
	
		        $query = $this->db->query('SELECT * FROM `teilnehmer` WHERE `id` IN (1)');
			
				$row = $query->row();
					
				$this->ldap_vorname   = $row->Vorname;
				$this->ldap_nachname  = $row->Name;
	            $this->ldap_kennung   = $row->LDAP_Kennung;
		        $this->ldap_abteilung = $row->Abteilung;
		            
		        $data['ldap_vorname'] = $this->ldap_vorname;
		        $data['ldap_nachname'] = $this->ldap_nachname;
				$data['ldap_kennung']	= $this->ldap_kennung;
		        $data['ldap_abteilung']	= $this->ldap_abteilung;
				
			}    	
        }
}