<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Act extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('act_model','bom_model','main_model'));

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


	public function temp()
	{
		$data = $this->act_model->get_temp_list();
		$this->load->view('/act/temp',$data);
	}


	public function temp_update()
	{
		$mode = $this->input->post('mode');

		$user_name = $this->session->userdata('user_name');

		if($mode == "update"){
			$res = $this->db->get('T_ACTPLN_EX');
			foreach($res->result() as $row){
				
				$params['PLN_NO']     = '';
				$params['LOT_NO']     = $row->LOT_NO;
				$params['BL_NO']      = $row->BL_NO;
				$params['MSAB']       = '';
				$params['ST_DATE']    = $row->ST_DATE;
				$params['GJ_CODE']    = $row->GJ_CODE; //공정코드
				$params['QTY']        = $row->QTY; //생산수량
				
				$params['UNIT']       = $row->UNIT; //단위
				$params['STATE']      = $row->STATE; //상태
				$params['PLN_QTY']    = $row->PL_QTY; //지시수
				$params['GJ_QTY']     = $row->GJ_QTY; //공정지시수

				$params['SIZ_NO']     = $row->SASIZ; //사시즈NO
				$params['NAME']       = $row->GJ_NAME; //공정명
				$params['PT']         = ''; //제작소요시간
				$params['T_PT']       = ''; //전체제작소요시간
				$params['M_LINE']     = ''; //생산라인
				$params['CUSTOMER']   = ''; //거래처
				$params['STA_DATE']   = date('Y-m-d H:i:s',time()); //생산시작일
				$params['END_DATE']   = ''; //생산완료일
				$params['ACT_DATE']   = $row->ACT_DATE; //완료예정일
				$params['PLN_DATE']   = $row->PLN_DATE; //계획배포일
				$params['GJ_GB']      = $row->GJ_GB; //공정구분
				$params['REMARK']     = ''; //비고
				$params['FINISH']     = ''; //완료여부
				$params['FINISH_DATE']= ''; //완료일
				$params['BAR_CODE']   = ''; //바코드
				$params['COL2']       = '';
				$params['COL3']       = '';
				$params['COL4']       = '';
				$params['INSERT_DATE']= date('Y-m-d H:i:s',time());
				$params['INSERT_ID']  = $user_name;

				$data = $this->act_model->ajax_temp_update($params);

			}
			
			$this->main_model->delete_actpln_ex();

		}
	}


	public function temp_cancel()
	{
		$this->main_model->delete_actpln_ex();
	}


	
	


	








	public function index($idx=0)
	{
		echo phpinfo();
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //GJ_GB
		$data['str']['bno'] = trim($this->input->get('bno')); //GJ_GB
		$data['str']['sta1'] = empty($this->input->get('sta1'))?date("Y-m-d",strtotime("-3 day")):$this->input->get('sta1'); //DATE
		$data['str']['sta2'] = empty($this->input->get('sta2'))?date("Y-m-d",time()):$this->input->get('sta2'); //DATE
		
		$params['GJ_GB'] = "SMT";
		$params['BL_NO'] = "";
		$params['STA1'] = "";
		$params['STA2'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
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
		

		$data['title'] = "수주정보";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list1($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut1($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

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

		$this->load->view('/act/actlist',$data);
	}




	public function a1($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['blno'] = trim($this->input->get('blno')); //BL_NO
		$data['str']['customer'] = trim($this->input->get('customer')); //CUSTOMER
		$data['str']['pln1'] = empty($this->input->get('pln1'))?date("Y-m-d",strtotime("-3 day")):$this->input->get('pln1'); //PLN_DATE
		$data['str']['pln2'] = empty($this->input->get('pln2'))?date("Y-m-d",time()):$this->input->get('pln2'); //PLN_DATE
		// $data['str']['act1'] = $this->input->get('act1'); //DATE
		// $data['str']['act2'] = $this->input->get('act2'); //DATE
		
		$params['GJ_GB'] = "SMT";
		$params['BL_NO'] = "";
		$params['CUSTOMER'] = "";
		$params['PLN1'] = "";
		$params['PLN2'] = "";
		$params['ACT1'] = "";
		$params['ACT2'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}

		if(!empty($data['str']['blno'])){
			$params['BL_NO'] = $data['str']['blno'];
			$data['qstr'] .= "&blno=".$data['str']['blno'];
		}
		if(!empty($data['str']['customer'])){
			$params['CUSTOMER'] = $data['str']['customer'];
			$data['qstr'] .= "&customer=".$data['str']['customer'];
		}
		if(!empty($data['str']['pln1'])){
			$params['PLN1'] = $data['str']['pln1'];
			$data['qstr'] .= "&pln1=".$data['str']['pln1'];
		}
		if(!empty($data['str']['pln2'])){
			$params['PLN2'] = $data['str']['pln2'];
			$data['qstr'] .= "&pln2=".$data['str']['pln2'];
		}
		if(!empty($data['str']['act1'])){
			$params['ACT1'] = $data['str']['act1'];
			$data['qstr'] .= "&act1=".$data['str']['act1'];
		}
		if(!empty($data['str']['act2'])){
			$params['ACT2'] = $data['str']['act2'];
			$data['qstr'] .= "&act2=".$data['str']['act2'];
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
		

		$data['title'] = "수주현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list1($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut1($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

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
		$this->load->view('/act/actlist_a1',$data);
	}


	public function a2($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['finish'] = $this->input->get('finish'); //FINISH
		$data['str']['pln1'] = empty($this->input->get('pln1'))?date("Y-m-d",strtotime("-3 day")):$this->input->get('pln1'); //PLN_DATE
		$data['str']['pln2'] = empty($this->input->get('pln2'))?date("Y-m-d",time()):$this->input->get('pln2'); //PLN_DATE
		
		$params['GJ_GB'] = "SMT";
		$params['FINISH'] = "";
		$params['PLN1'] = "";
		$params['PLN2'] = "";

		$data['qstr'] = "?P";

		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['finish'])){
			$params['FINISH'] = $data['str']['finish'];
			$data['qstr'] .= "&finish=".$data['str']['finish'];
		}
		if(!empty($data['str']['pln1'])){
			$params['PLN1'] = $data['str']['pln1'];
			$data['qstr'] .= "&pln1=".$data['str']['pln1'];
		}
		if(!empty($data['str']['pln2'])){
			$params['PLN2'] = $data['str']['pln2'];
			$data['qstr'] .= "&pln2=".$data['str']['pln2'];
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
		
		

		$data['title'] = "수주대비 진행현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list1($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut1($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");


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
		$this->load->view('/act/actlist_a2',$data);
	}


	public function a3($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['pln1'] = $this->input->get('pln1'); //DATE
		$data['str']['pln2'] = $this->input->get('pln2'); //DATE
		
		$params['GJ_GB'] = "SMT";
		$params['PLN1'] = "";
		$params['PLN2'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['pln1'])){
			$params['PLN1'] = $data['str']['pln1'];
			$data['qstr'] .= "&pln1=".$data['str']['pln1'];
		}
		if(!empty($data['str']['pln2'])){
			$params['PLN2'] = $data['str']['pln2'];
			$data['qstr'] .= "&pln2=".$data['str']['pln2'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		

		$params['ACT_DATE'] = true; //완료예정일체크

		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		

		$data['title'] = "납기지연예상";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list1($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut1($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

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
		$this->load->view('/act/actlist_a3',$data);
	}



}