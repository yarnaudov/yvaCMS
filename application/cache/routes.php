<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route["(\w{2})/gallery(.*)"] = "gallery/albums:*$2";