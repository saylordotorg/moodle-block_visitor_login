<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../../config.php');
require_once($CFG->libdir.'/moodlelib.php');


$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('base');
$PAGE->set_title("Redirect Page");
$PAGE->set_heading("Redirect...");
$PAGE->set_url($CFG->wwwroot.'/redirect_page.php');

//$casurl is the CAS host url. Cannot have trailing slash.
$casurl = get_config("block_visitor_login", "casurl"); 
$referredurl = $_SERVER['HTTP_REFERER'];

// To build the redirect url, take the CAS host url, append '/login?service='
// and the escaped url of the previous page.
// This will redirect the user back to the same page once they are logged in.
$redirecturl = $casurl . '/login?service=' . urlencode($referredurl);

echo $OUTPUT->header();
redirect($redirecturl,get_string('redirectmessage', 'block_visitor_login'), 5);
echo $OUTPUT->footer();





