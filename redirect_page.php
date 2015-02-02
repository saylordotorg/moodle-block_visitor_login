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

$redirecturl = get_config("block_visitor_login", "redirecturl");	
echo $OUTPUT->header();
redirect($redirecturl,get_string('redirectmessage', 'block_visitor_login'), 5);
echo $OUTPUT->footer();





