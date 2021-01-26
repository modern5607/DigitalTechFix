<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		
		// $this->data['userLevel'] = $this->session->userdata('user_level');
		
		$this->load->model(array('kpi_model'));

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

	public function equip1()
	{
		$data['List']   = $this->kpi_model->equip_chart();

		$this->load->view('/kpi/equipchart',$data);
	}

	public function fair1()
	{
		$data['List']   = $this->kpi_model->fair_chart();

		$this->load->view('/kpi/fairchart',$data);
	}

	public function equip2()
	{
		$data['List']   = $this->kpi_model->equip_list();

		$this->load->view('/kpi/equiplist',$data);
	}

	public function fair2()
	{
		$data['List']   = $this->kpi_model->fair_list();

		$this->load->view('/kpi/fairlist',$data);
	}


}