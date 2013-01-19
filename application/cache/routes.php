<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route["(\w{2})/gallery(.*)"] = "contact_forms/2";
$route["(\w{2})/contacts(.*)"] = "contact_forms/2";
$route["(\w{2})/drevnost(.*)"] = "contact_forms/3";
$route["(\w{2})/online_request(.*)"] = "contact_forms/3";
$route["(\w{2})/search(.*)"] = "search/$2";