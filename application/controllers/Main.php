<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('act_model','main_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');

		

	}

	public function _remap($method, $params = array())
	{
		if($this->input->is_ajax_request()){
            if( method_exists($this, $method) ){
                call_user_func_array(array($this,$method), $params);
            }
        }else{ //ajax가 아니면
			
			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				if(isset($user_id) && $user_id != ""){
					
					$this->load->view('/layout/header',$this->data);
					call_user_func_array(array($this,$method), $params);
					$this->load->view('/layout/tail');

				}else{

					alert('로그인이 필요합니다.',base_url('register/login'));

				}

            } else {
                show_404();
            }

        }
		
	}


	public function index()
	{
		/*
		$data['str'] = array(); //검색어관련
		$data['str']['sta1'] = $this->input->get('sta1'); //PLN_DATE
		$data['str']['sta2'] = $this->input->get('sta2'); //PLN_DATE
		$data['str']['kpigb'] = $this->input->get('kpigb'); //KPI 구분

		$params['STA1'] = "";
		$params['STA2'] = "";
		$params['KPIGB'] = "";


		$data['qstr'] = "?P";
		if(!empty($data['str']['sta1'])){
			$params['STA1'] = $data['str']['sta1'];
			$data['qstr'] .= "&sta1=".$data['str']['sta1'];
		}
		if(!empty($data['str']['sta2'])){
			$params['STA2'] = $data['str']['sta2'];
			$data['qstr'] .= "&sta2=".$data['str']['sta2'];
		}
		if(!empty($data['str']['kpigb'])){
			$params['KPIGB'] = $data['str']['kpigb'];
			$data['qstr'] .= "&kpigb=".$data['str']['kpigb'];
		}
		



		$data['title'] = "KPI 구분";
		$data['viewList'] = $this->act_model->get_rel_view($params);
		$data['viewList1'] = $this->act_model->get_rel_view1($params);

 
		return $this->load->view('intro',$data); 
		*/
	}
}