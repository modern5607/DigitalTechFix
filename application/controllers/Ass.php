<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ass extends CI_Controller {

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
	

	public function s1($idx=0)
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['lot'] = $this->input->get('lot'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['customer'] = $this->input->get('customer'); //CUSTOMER
		$data['str']['finish'] = $this->input->get('finish'); //CUSTOMER
		$data['str']['st1'] = $this->input->get('st1'); //PLN_DATE
		$data['str']['st2'] = $this->input->get('st2'); //PLN_DATE
		
		$params['BL_NO'] = "";
		$params['LOT_NO'] = "";
		$params['GJ_GB'] = "ASS";
		$params['CUSTOMER'] = "";
		$params['FINISH'] = "";
		$params['ST1'] = "";
		$params['ST2'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['lot'])){
			$params['LOT_NO'] = $data['str']['lot'];
			$data['qstr'] .= "&lot=".$data['str']['lot'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['customer'])){
			$params['CUSTOMER'] = $data['str']['customer'];
			$data['qstr'] .= "&customer=".$data['str']['customer'];
		}
		if(!empty($data['str']['finish'])){
			$params['FINISH'] = $data['str']['finish'];
			$data['qstr'] .= "&finish=".$data['str']['finish'];
		}
		if(!empty($data['str']['st1'])){
			$params['ST1'] = $data['str']['st1'];
			$data['qstr'] .= "&st1=".$data['str']['st1'];
		}
		if(!empty($data['str']['st2'])){
			$params['ST2'] = $data['str']['st2'];
			$data['qstr'] .= "&st2=".$data['str']['st2'];
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

		

		$data['title'] = "[조립]생산계획등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);



		$data['idx'] = $idx;
		
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

		$this->load->view('/smt/s1',$data);
		
	}


	public function s2($idx=0)
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['lot'] = $this->input->get('lot'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //CUSTOMER
		$data['str']['finish'] = $this->input->get('finish'); //CUSTOMER
		$data['str']['st1'] = $this->input->get('st1'); //PLN_DATE
		$data['str']['st2'] = $this->input->get('st2'); //PLN_DATE
		
		$params['BL_NO'] = "";
		$params['LOT_NO'] = "";
		$params['GJ_GB'] = "ASS";
		$params['M_LINE'] = "";
		$params['FINISH'] = "";
		$params['ST1'] = "";
		$params['ST2'] = "";

		$data['qstr'] = "?P";
		/*
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['lot'])){
			$params['LOT_NO'] = $data['str']['lot'];
			$data['qstr'] .= "&lot=".$data['str']['lot'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['finish'])){
			$params['FINISH'] = $data['str']['finish'];
			$data['qstr'] .= "&finish=".$data['str']['finish'];
		}
		if(!empty($data['str']['st1'])){
			$params['ST1'] = $data['str']['st1'];
			$data['qstr'] .= "&st1=".$data['str']['st1'];
		}
		if(!empty($data['str']['st2'])){
			$params['ST2'] = $data['str']['st2'];
			$data['qstr'] .= "&st2=".$data['str']['st2'];
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

		

		$data['title'] = "[조립]생산계획조회";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		$data['M_LINE']   = $this->main_model->get_selectInfo_new("tch.CODE","M_LINE","AS");

		$data['idx'] = $idx;
		
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

		$this->load->view('/smt/s2',$data);
		
	}


	public function s3($idx=0)
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['lot'] = $this->input->get('lot'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['customer'] = $this->input->get('customer'); //CUSTOMER
		$data['str']['finish'] = $this->input->get('finish'); //CUSTOMER
		$data['str']['st1'] = $this->input->get('st1');
		$data['str']['st2'] = $this->input->get('st2');
		
		$params['BL_NO'] = "";
		$params['LOT_NO'] = "";
		$params['GJ_GB'] = "ASS";
		$params['CUSTOMER'] = "";
		$params['FINISH'] = "";
		$params['ST1'] = "";
		$params['ST2'] = "";

		$data['qstr'] = "?P";
		/*if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['lot'])){
			$params['LOT_NO'] = $data['str']['lot'];
			$data['qstr'] .= "&lot=".$data['str']['lot'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['customer'])){
			$params['CUSTOMER'] = $data['str']['customer'];
			$data['qstr'] .= "&customer=".$data['str']['customer'];
		}
		if(!empty($data['str']['finish'])){
			$params['FINISH'] = $data['str']['finish'];
			$data['qstr'] .= "&finish=".$data['str']['finish'];
		}
		if(!empty($data['str']['st1'])){
			$params['ST1'] = $data['str']['st1'];
			$data['qstr'] .= "&st1=".$data['str']['st1'];
		}
		if(!empty($data['str']['st2'])){
			$params['ST2'] = $data['str']['st2'];
			$data['qstr'] .= "&st2=".$data['str']['st2'];
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

		

		$data['title'] = "[조립]생산계획대비실적";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		$data['idx'] = $idx;
		
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

		$this->load->view('/smt/s3',$data);
		
	}

	
	/* 작업지시등록 */
	public function s4($idx=0)
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['lot'] = $this->input->get('lot'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['customer'] = $this->input->get('customer'); //CUSTOMER
		$data['str']['finish'] = $this->input->get('finish'); //CUSTOMER
		$data['str']['st1'] = $this->input->get('st1'); //PLN_DATE
		$data['str']['st2'] = $this->input->get('st2'); //PLN_DATE
		
		$params['BL_NO'] = "";
		$params['LOT_NO'] = "";
		$params['GJ_GB'] = "ASS";
		$params['CUSTOMER'] = "";
		$params['FINISH'] = "";
		$params['ST1'] = "";
		$params['ST2'] = "";

		$data['qstr'] = "?P";
		/*if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['lot'])){
			$params['LOT_NO'] = $data['str']['lot'];
			$data['qstr'] .= "&lot=".$data['str']['lot'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['customer'])){
			$params['CUSTOMER'] = $data['str']['customer'];
			$data['qstr'] .= "&customer=".$data['str']['customer'];
		}
		if(!empty($data['str']['finish'])){
			$params['FINISH'] = $data['str']['finish'];
			$data['qstr'] .= "&finish=".$data['str']['finish'];
		}
		if(!empty($data['str']['st1'])){
			$params['ST1'] = $data['str']['st1'];
			$data['qstr'] .= "&st1=".$data['str']['st1'];
		}
		if(!empty($data['str']['st2'])){
			$params['ST2'] = $data['str']['st2'];
			$data['qstr'] .= "&st2=".$data['str']['st2'];
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

		

		$data['title'] = "[조립]작업지시등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list_finish($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut_finish($params);

		$data['idx'] = $idx;
		
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

		$this->load->view('/smt/s4',$data);
		
	}

	/* 작업일보 */
	public function s5($idx=0)
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //BL_NO
		$data['str']['st_date'] = $this->input->get('st_date'); //ST_DATE
		
		$params['GJ_GB'] = "ASS";
		$params['M_LINE'] = "";
		$params['ST_DATE'] = "";
		$params['FINISH'] = "N";

		$data['qstr'] = "?P";
		/*
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];

		}
		if(!empty($data['str']['st_date'])){
			$params['ST_DATE'] = $data['str']['st_date'];
			$data['qstr'] .= "&st_date=".$data['str']['st_date'];

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

		

		$data['title'] = "[조립]작업일보";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['idx'] = $idx;
		$data['gjgb'] = "ASS";
		
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

		$this->load->view('/smt/s5',$data);
		
	}


	public function ajax_mline_info()
	{
		$param['idx']  = $this->input->post("idx");
		$param['blno'] = $this->input->post("blno");
		
		$data['mline'] = $this->act_model->ajax_mline_info($param);
		$data['AIDX'] = $param['idx'];
		
		$this->load->view('/smt/ajax_mline',$data);

	}


	public function ajax_mline_update()
	{
		$param['IDX']  = $this->input->post("idx");
		list($mline,$pt) = explode("_",$this->input->post("mline"));
		$param['LINE'] = $mline;
		$param['PT']   = $pt;

		$data = $this->act_model->ajax_mline_update($param);
		
		echo $data;
		//update처리할것~~!!!!!

	}


	public function s_print($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['str']['sta'] = $this->input->get('sta'); //M_LINE
		$data['str']['chkbox'] = $this->input->get('chkbox');

		
		$params['GJ_GB'] = "ASS";
		$params['M_LINE'] = "";
		$params['BL_NO'] = "";
		$params['STA'] = "";
		$params['chkbox'] = "";


		$data['qstr'] = "?P";
		/*if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];

		}

		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];

		}

		if(!empty($data['str']['sta'])){
			$params['STA'] = $data['str']['sta'];
			$data['qstr'] .= "&sta=".$data['str']['sta'];

		}
		if(!empty($data['str']['chkbox'])){
			$params['CHKBOX'] = $data['str']['chkbox'];
			$data['qstr'] .= "&chkbox=".$data['str']['chkbox'];

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

		

		$data['title'] = "[조립]Line별 작업지시";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list_finish($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut_finish($params);

		$data['idx'] = $idx;
		$data['gjgb'] = "ASS";
		
		//$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");
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

		$this->load->view('/smt/s_print',$data);
	}

	

	/* 조립생산관리 제작완료실적수신*/
	public function asslist1($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actdate'] = $this->input->get('actdate'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['sta1'] = $this->input->get('sta1'); //PLN_DATE
		$data['str']['sta2'] = $this->input->get('sta2'); //PLN_DATE
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		
		$params['ACT_DATE'] = "";
		$params['BL_NO'] = "";
		$params['STA1'] = "";
		$params['STA2'] = "";
		$params['M_LINE'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['actdate'])){
			$params['ACT_DATE'] = $data['str']['actdate'];
			$data['qstr'] .= "&actdate=".$data['str']['actdate'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['sta1'])){
			$params['STA1'] = $data['str']['sta1'];
			$data['qstr'] .= "&sta1=".$data['str']['sta1'];
		}
		if(!empty($data['str']['sta2'])){
			$params['STA2'] = $data['str']['sta2'];
			$data['qstr'] .= "&sta2=".$data['str']['sta2'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
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

		

		$data['title'] = "[조립]제작완료실적수신";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['xList']  = $this->act_model->get_asslist1_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_asslist1_cut($params);
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['idx'] = $idx;
		
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

		$this->load->view('/smt_list',$data);
		
	}
	/* 조립생산관리 검사정보실적관리*/
	public function asslist2($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actdate'] = $this->input->get('actdate'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['sta1'] = $this->input->get('sta1'); //PLN_DATE
		$data['str']['sta2'] = $this->input->get('sta2'); //PLN_DATE
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		
		$params['ACT_DATE'] = "";
		$params['BL_NO'] = "";
		$params['STA1'] = "";
		$params['STA2'] = "";
		$params['M_LINE'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['actdate'])){
			$params['ACT_DATE'] = $data['str']['actdate'];
			$data['qstr'] .= "&actdate=".$data['str']['actdate'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['sta1'])){
			$params['STA1'] = $data['str']['sta1'];
			$data['qstr'] .= "&sta1=".$data['str']['sta1'];
		}
		if(!empty($data['str']['sta2'])){
			$params['STA2'] = $data['str']['sta2'];
			$data['qstr'] .= "&sta2=".$data['str']['sta2'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
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

		

		$data['title'] = "[조립]검사정보실적관리";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['xList']  = $this->act_model->get_asslist2_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_asslist2_cut($params);
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");
		
		$data['idx'] = $idx;
		
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

		$this->load->view('/smt_list',$data);
		
	}


	/* 조립생산관리 솔더실적관리*/
	public function asslist3($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actdate'] = $this->input->get('actdate'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['sta1'] = $this->input->get('sta1'); //PLN_DATE
		$data['str']['sta2'] = $this->input->get('sta2'); //PLN_DATE
		
		$params['STA1'] = "";
		$params['STA2'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['sta1'])){
			$params['STA1'] = $data['str']['sta1'];
			$data['qstr'] .= "&sta1=".$data['str']['sta1'];
		}
		if(!empty($data['str']['sta2'])){
			$params['STA2'] = $data['str']['sta2'];
			$data['qstr'] .= "&sta2=".$data['str']['sta2'];
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

		

		$data['title'] = "[조립]솔더실적관리";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['xList']  = $this->act_model->get_asslist3_list($params,$start,$config['per_page']);
		//var_dump($data['xList']);
		$this->data['cnt'] = $this->act_model->get_asslist3_cut($params);
		//$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");
		$data['idx'] = $idx;
		
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

		$this->load->view('/x_list',$data);
		
	}
	public function sold_select_ajax()
	{
		$idx = $this->input->post("idx");
		$params['INSERT_DATE'] = $idx;

		$data = array();
		$data['soldID']=$this->act_model->get_sold_ID($params);

		return $this->load->view('ajax/soldselect_ajax',$data);
	}

	public function sold_ajax()
	{
		$idx = $this->input->post("idx");
		
		$params['IDX'] = $idx;
		
		$data = array();
		$data['soldinfo'] = $this->act_model->sold_ajax_info($params);

		return $this->load->view('ajax/sold_ajax',$data);
	}


	public function finish_actpln()
	{
		$param['idx'] = $this->input->post("idx");
		$param['userName'] = $this->session->userdata('user_name');
		$param['gjgb'] = "ASS";
		
		$data = $this->act_model->set_finish_actpln($param);
		die(json_encode($data));
	}

	public function print_actpln()
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['str']['st1'] = $this->input->get('st1');

		$data['M_TITLE'] = "전체";
		
		$params['GJ_GB'] = "ASS";
		$params['M_LINE'] = "";
		$params['ST1'] = "";

		$data['qstr'] = "?P";
		/*if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
			
			$data['M_TITLE'] = $params['M_LINE'];
		}
		if(!empty($data['str']['st1'])){
			$params['ST1'] = $data['str']['st1'];
			$data['qstr'] .= "&st1=".$data['str']['st1'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):15;
		

		
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;
		
		
		

		$data['title'] = "[조립]작업지시등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list_finish($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut_finish($params);

		
		
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");
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

		return $this->load->view('/smt/ajax_print',$data);
	}


	
	public function barcode()
	{
		
		$this->load->library('barcode39');

		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['lot'] = $this->input->get('lot'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //CUSTOMER
		$data['str']['finish'] = $this->input->get('finish'); //CUSTOMER
		$data['str']['st1'] = $this->input->get('st1'); //PLN_DATE
		$data['str']['st2'] = $this->input->get('st2'); //PLN_DATE
		
		$params['BL_NO'] = "";
		$params['M_LINE'] = "";
		$params['LOT_NO'] = "";
		$params['GJ_GB'] = "ASS";
		$params['CUSTOMER'] = "";
		$params['FINISH'] = "";
		$params['ST1'] = "";
		$params['ST2'] = "";

		$data['qstr'] = "?P";
		/*
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['lot'])){
			$params['LOT_NO'] = $data['str']['lot'];
			$data['qstr'] .= "&lot=".$data['str']['lot'];
		}
		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['finish'])){
			$params['FINISH'] = $data['str']['finish'];
			$data['qstr'] .= "&finish=".$data['str']['finish'];
		}
		if(!empty($data['str']['st1'])){
			$params['ST1'] = $data['str']['st1'];
			$data['qstr'] .= "&st1=".$data['str']['st1'];
		}
		if(!empty($data['str']['st2'])){
			$params['ST2'] = $data['str']['st2'];
			$data['qstr'] .= "&st2=".$data['str']['st2'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):10;
		


		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		

		$data['title'] = "[조립]생산진행현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		/* $data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE"); */

		//$data['idx'] = $idx;
		
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

		$this->load->view('/smt/barcode',$data);
	}


	public function creat_barcode()
	{
		$this->load->library('barcode39');
		$idx = $this->input->post("idx");
		
		$act = $this->act_model->get_actplan_info($idx);
		$code = $act->GJ_CODE."-".$act->M_LINE."-".$act->IDX;
		
		$bc = new Barcode39($act->GJ_CODE."-".$act->M_LINE."-".$act->IDX);
		$bc->barcode_text_size = 2;
		$bc->barcode_bar_thick = 3;
		$bc->barcode_bar_thin = 1;
		
		$path = FCPATH."_static/barcode/";
		$img  = "barcode_".$act->IDX.".gif";
		
		$bc->draw($path.$img);

		$param['BAR_CODE'] = $code;
		$param['IDX']      = $act->IDX;

		$this->act_model->set_actplan_barcode($param);		

		$returnpath = base_url('_static/barcode/'.$img);

		
		echo $returnpath;
	}

	


	/* 생산진행현황 */
	public function s6($idx=0)
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //BL_NO
		$data['str']['st_date'] = $this->input->get('st_date'); //ST_DATE
		
		$params['GJ_GB'] = "ASS";
		$params['M_LINE'] = "";
		$params['ST_DATE'] = "";

		$data['qstr'] = "?P";
		/*if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];

		}
		if(!empty($data['str']['st_date'])){
			$params['ST_DATE'] = $data['str']['st_date'];
			$data['qstr'] .= "&st_date=".$data['str']['st_date'];

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

		

		$data['title'] = "[조립]생산진행현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['idx'] = $idx;
		
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

		$this->load->view('/smt/s6',$data);
		
	}


}