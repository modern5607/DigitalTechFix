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

		$data['title'] = "설비가동률 차트";
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

		$data['title'] = "공정불량률 차트";
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

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['title'] = "설비가동률 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		
		$data['seq'] = "";
		$data['set'] = "";
		
		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		

		$data['List']   = $this->kpi_model->equip_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->equip_cut($params);


		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
        $config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';


		$this->pagination->initialize($config);
        $this->data['pagenation'] = $this->pagination->create_links();

		/* pagenation end */

		
		
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

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['title'] = "공정불량률 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		
		$data['seq'] = "";
		$data['set'] = "";
		
		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		

		$data['List']   = $this->kpi_model->fair_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->fair_cut($params);


		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
        $config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';


		$this->pagination->initialize($config);
        $this->data['pagenation'] = $this->pagination->create_links();

		/* pagenation end */

		$this->load->view('/kpi/fairlist',$data);
	}


}