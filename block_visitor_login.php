<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Course list block.
 *
 * @package    block_visitor_login
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_visitor_login extends block_base {
        
    function init() {
        $this->title = get_string('pluginname', 'block_visitor_login');  
    }

    function has_config() {
        return true;
    }

    function get_content() {   
        global $CFG, $USER, $DB, $OUTPUT, $PAGE;

        if($this->content !== NULL) {
            return $this->content;
        }  
         
        if($this->in_coursemodule()){
            if($USER->username == 'guest'){
                $this->click_limit();
            }    
        }
        return $this->content;
    }
    
     private function click_limit(){   
        global $USER, $CFG, $DB, $COURSE, $PAGE;
        $cookiename = $this->get_cookie_name();
        $config_visitor_login = get_config ('block_visitor_login');   
        $cookie_lifetime =  $config_visitor_login->cookieexpiretime*24*3600;
        $cmid = $PAGE->cm->id;
        $cookie_value = $this->get_cookie_value();
        $cookievalues = explode(',',  $cookie_value);
        
        if(isset($_COOKIE[$cookiename])){
            if(!$this->in_limit()){
                if(!in_array($cmid, $cookievalues)){
                    $this->redirect_page();
                }    
            }else{     
                $this->update_cookie($cookiename, $cookie_lifetime);
            }
        }else{ 
            setcookie($cookiename, $cmid, time()+$cookie_lifetime,'/');    
        }
    }
    
    private function update_cookie($cookiename, $cookie_lifetime){
        global $PAGE;
        $cookie_value = $this->get_cookie_value();
        $cookievalues = explode(',',  $cookie_value);
        $cmid = $PAGE->cm->id;
        
        if(!in_array($cmid, $cookievalues, true)){
            array_push($cookievalues,$cmid);
        }
        
        $updated_cookie_value = implode(',',  $cookievalues);
        setcookie($cookiename, $updated_cookie_value, time()+$cookie_lifetime,'/');
    }
    
    private function in_coursemodule(){ 
        global $PAGE;
        
        if($PAGE->cm){
            $cmid = $PAGE->cm->id;   
            return $cmid;
        }
        
        return false;
    }
    
    private function in_limit(){
        global $DB;
        $cookiename = $this->get_cookie_name();
        $cookie_value = $this->get_cookie_value();
        $click_limit_value = get_config('block_visitor_login');
        $cookievalues = explode(',',  $cookie_value);  
        
        if((count($cookievalues) < $click_limit_value->countitemsvisited)){
            return true;
        }    
        
        return false;
    }
    
    private function get_cookie_value(){
        $cookiename = $this->get_cookie_name(); 
        $cookievalue = clean_param($_COOKIE[$cookiename], PARAM_TEXT);
        return $cookievalue;     
    }
    
    private function get_cookie_name(){  
        global $DB;
        $visitor_login = get_config ('block_visitor_login');  
        
        if($visitor_login->cookiename){
            $cookiename =  $visitor_login->cookiename;
        }else{
            $cookiename = 'lambdavisitorcookie';
        }
        
        return $cookiename;
    }
    
    private function redirect_page(){
        global $CFG;
        $url = $CFG->wwwroot.'/blocks/visitor_login/redirect_page.php';
        redirect($url);
    }
    
     
    /**
     * Returns the role that best describes the course list block.
     *
     * @return string
     */
    public function get_aria_role() {
        return 'navigation';
    }
    
    
}


