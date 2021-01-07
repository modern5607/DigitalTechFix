<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('act_model','bom_model','main_model','release_model'));

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
				//if(isset($user_id) && $user_id != ""){
					
				$this->load->view('/layout/m_header',$this->data);
				call_user_func_array(array($this,$method), $params);
				$this->load->view('/layout/m_tail');

				//}else{

				//	alert('로그인이 필요합니다.',base_url('register/login'));

				//}

            } else {
                show_404();
            }

        }
		
	}

	public function index(){
		$data['title'] = "DIGITAL";
		$this->load->view('/mobile/index',$data);
	}

	public function m1()
	{
		$data['title'] = "일일작업일지";
		
		
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['M_TITLE'] = "전체";
		
		$params['GJ_GB'] = "SMT";

		$data['qstr'] = "?P";
		/*
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
			
			$data['M_TITLE'] = $params['M_LINE'];
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
		
		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		
		
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

		$this->load->view('/mobile/m1',$data);
	}



	public function m2()
	{
		$data['title'] = "생산현황판";

		$data['leftData'] = $this->release_model->get_left_data();
		$data['toDate'] = $this->release_model->get_todate_num();
		$data['viewList'] = $this->release_model->get_rel_view();

		$this->load->view('/mobile/m2',$data);

	}


	public function ajax_actplan_info()
	{
		$data['title'] = "상세정보";
		$param['idx'] = $this->input->post("idx"); //actplan idx
		$data['ACTPLAN'] = $this->act_model->get_actplan_info($param['idx']);
		$this->load->view('/mobile/m3_view',$data);
	}


	public function m3()
	{
		//$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		//$data['str']['lot'] = $this->input->get('lot'); //BL_NO
		//$data['str']['blno'] = $this->input->get('blno'); //BL_NO
		//$data['str']['customer'] = $this->input->get('customer'); //CUSTOMER
		//$data['str']['finish'] = $this->input->get('finish'); //CUSTOMER
		//$data['str']['st1'] = $this->input->get('st1'); //PLN_DATE
		//$data['str']['st2'] = $this->input->get('st2'); //PLN_DATE
		
		//$params['BL_NO'] = "";
		//$params['LOT_NO'] = "";
		$params['GJ_GB'] = "SMT";
		//$params['CUSTOMER'] = "";
		//$params['FINISH'] = "";
		//$params['ST1'] = "";
		//$params['ST2'] = "";

		//$data['qstr'] = "?P";
		/*
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/

		/*
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
		}*/

		

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

		

		$data['title'] = "생산계획대비실적";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

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

		$this->load->view('/mobile/m3',$data);
	}


	public function m4($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['insert1'] = $this->input->get('insert1'); //DATE
		$data['str']['insert2'] = $this->input->get('insert2'); //DATE
		
		$params['INSERT1'] = "";
		$params['INSERT2'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['insert1'])){
			$params['INSERT1'] = $data['str']['insert1'];
			$data['qstr'] .= "&insert1=".$data['str']['insert1'];
		}
		if(!empty($data['str']['insert2'])){
			$params['INSERT2'] = $data['str']['insert2'];
			$data['qstr'] .= "&insert2=".$data['str']['insert2'];
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
		$this->load->view('/mobile/m4',$data);
	}
	

}

?>
