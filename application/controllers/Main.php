<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

    }

    //index 함수 => "URL/index.php/main" or "URL/index.php/main/index"로 설정
    public function index()
    {
        $arr = array(
            "dasdas" => "Dasdas"
        );
        json_encode($arr);

    }
}