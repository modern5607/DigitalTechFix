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
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = date("Y-m-d", strtotime("-1 month", time()));
		$params['EDATE'] = date("Y-m-d");
		$params['CHART'] = 1;

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}
		$start = 0;
		$config['per_page'] = 9999;

		$data['title'] = "스마트공장 KPI 설비가동률";
		$data['List']   = $this->kpi_model->equip_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->equip_cut($params);
		
		$this->load->view('/kpi/equipchart',$data);
	}
	
	public function fair1()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = date("Y-m-d", strtotime("-1 month", time()));
		$params['EDATE'] = date("Y-m-d");
		$params['CHART'] = 1;

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}
		$start = 0;
		$config['per_page'] = 9999;

		$data['title'] = "스마트공장 KPI 공정불량률";
		$data['List']   = $this->kpi_model->fair_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->fair_cut($params);

		$this->load->view('/kpi/fairchart',$data);
	}
	
	public function equip2()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = "";
		$params['EDATE'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}

		$data['pageNum'] = 0;
		$data['title'] = "설비가동률 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
	

		$data['List'] = $this->kpi_model->equip_list($params);
		$data['mean'] = $this->kpi_model->equip_mean($params);

		
		$this->load->view('/kpi/equiplist',$data);
	}
	
	public function fair2()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = "";
		$params['EDATE'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}

		$data['pageNum'] = 0;
		$data['title'] = "공정불량률 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
	

		$data['List'] = $this->kpi_model->fair_list($params);
		$data['mean'] = $this->kpi_model->fair_mean($params);

		
		$this->load->view('/kpi/fairlist',$data);
	}


}