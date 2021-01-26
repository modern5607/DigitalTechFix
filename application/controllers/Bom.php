<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bom extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		$this->data['subpos_3'] = $this->uri->segment(3);

		$this->data['userLevel'] = $this->session->userdata('user_level');
		
		$this->load->model(array('bom_model','main_model','biz_model'));

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


	public function test()
	{
		//$this->bom_model->get_testUpdate();
	}


	/* ITEMS */
	public function index($idx="")
	{
		$this->load->library('barcode');
		
		$data['str'] = array(); //검색어관련
		$data['str']['bno'] = trim($this->input->get('bno')); //BL_NO
		$data['str']['iname'] = trim($this->input->get('iname')); //ITEM_NAME
		$data['str']['mscode'] = $this->input->get('mscode'); //MSAB
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['str']['gjcode'] = $this->input->get('gjcode'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$params['BL_NO'] = "";
		$params['ITEM_NAME'] = "";
		$params['MSAB'] = "";
		$params['M_LINE'] = "";
		$params['GJ_GB'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['iname'])){
			$params['ITEM_NAME'] = $data['str']['iname'];
			$data['qstr'] .= "&iname=".$data['str']['iname'];
		}
		if(!empty($data['str']['mscode'])){
			$params['MSAB'] = $data['str']['mscode'];
			$data['qstr'] .= "&mscode=".$data['str']['mscode'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['gjcode'])){
			$params['GJ_GB'] = $data['str']['gjcode'];
			$data['qstr'] .= "&gjcode=".$data['str']['gjcode'];
		}
		if(!empty($data['str']['use'])){
			$params['USE_YN'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
		}

		


		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		//print_r($data['perpage']);
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;

		$data['pageNum'] = $start;

		$data['title'] = "BOM-ITMES";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['seq'] = "";
		$data['set'] = "";
		
		
		
		
		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		//$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['bomList']  = $this->bom_model->get_items_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_items_cut($params);

		//$data['bomInfo']  = (!empty($idx))?$this->bom_model->get_items_info($idx):"";
		
		$data['MSAB']     = $this->main_model->get_selectInfo("tch.CODE","MSAB");
		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['stClass1'] = $this->main_model->get_selectInfo("tch.CODE","1ST_CLASS");
		$data['stClass2'] = $this->main_model->get_selectInfo("tch.CODE","2ND_CLASS");
		$data['STATE']    = $this->main_model->get_selectInfo("tch.CODE","STATE");
		$data['UNIT']     = $this->main_model->get_selectInfo("tch.CODE","UNIT");
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['CUSTOMER'] = $this->biz_model->get_selectInfo();

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

		
		
		$this->load->view('/bom/index',$data);
	}




	/* ITEMS */
	public function index_ajax($idx="")
	{
		$idx = $this->input->post("idx");
		$data['bomInfo']  = (!empty($idx))?$this->bom_model->get_items_info($idx):"";
		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		
		$data['MSAB']     = $this->main_model->get_selectInfo("tch.CODE","MSAB");
		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['stClass1'] = $this->main_model->get_selectInfo("tch.CODE","1ST_CLASS");
		$data['stClass2'] = $this->main_model->get_selectInfo("tch.CODE","2ND_CLASS");
		$data['STATE']    = $this->main_model->get_selectInfo("tch.CODE","STATE");
		$data['UNIT']     = $this->main_model->get_selectInfo("tch.CODE","UNIT");
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['CUSTOMER'] = $this->biz_model->get_selectInfo();
		

		return $this->load->view('/bom/ajax_index',$data);
		

		
	}




	/*	자재관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function materials($idx="")
	{
		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		$data['qstr'] = "?P";
		$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		
		

		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;
		$data['title'] = "재고실사관리";

		$data['seq'] = "";
		$data['set'] = "";
		
		
		$params = array();
		if(!empty($this->input->get("set"))){
			$params['seq'] = $this->input->get("seq");
			$params['set'] = $this->input->get("set");

			$data['seq'] = $this->input->get("seq");
			$data['set'] = $this->input->get("set");

			$data['qstr'] .= "&seq=".$data['seq']."&set=".$data['set'];
		}

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_material_cut($params);

		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

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


		$this->load->view('/bom/material',$data);
	}


	/*	자재관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function materials_ajax()
	{
		
		$idx = $this->input->post("idx");
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		//$data['GJ_GB']         = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		

		return $this->load->view('/bom/ajax_material',$data);
	}


	/*	재고현황
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stocklist($idx="")
	{
		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		$data['qstr'] = "?P";
		$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		
		$params['M_LINE'] = "";
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;




		$data['seq'] = "";
		$data['set'] = "";
		
		$params = array();
		if(!empty($this->input->get("set"))){
			$params['seq'] = $this->input->get("seq");
			$params['set'] = $this->input->get("set");

			$data['seq'] = $this->input->get("seq");
			$data['set'] = $this->input->get("set");

			$data['qstr'] .= "&seq=".$data['seq']."&set=".$data['set'];
		}

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['title'] = "재고현황";
		$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$this->data['cnt'] = $this->bom_model->get_material_cut($params);
		
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


		$this->load->view('/bom/stocklist',$data);
	}



	/*	자재실사관리관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stock($idx="")
	{
		
		$data['str'] = array(); //검색어관련
		$data['str']['component'] = trim($this->input->get('component')); //BL_NO
		$data['str']['comp_name'] = trim($this->input->get('comp_name')); //ITEM_NAME
		$data['str']['gjcode'] = $this->input->get('gjcode'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['GJ_GB'] = "";
		$params['SPEC'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['comp_name'])){
			$params['COMPONENT_NM'] = $data['str']['comp_name'];
			$data['qstr'] .= "&comp_name=".$data['str']['comp_name'];
		}
		if(!empty($data['str']['gjcode'])){
			$params['GJ_GB'] = $data['str']['gjcode'];
			$data['qstr'] .= "&gjcode=".$data['str']['gjcode'];
		}
		if(!empty($data['str']['use'])){
			$params['USE_YN'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
		}

		
		
		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		//$data['qstr'] = "?P";
		//$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;

		
		
		

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['title'] = "BOM-자재등록";
		
		$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$this->data['cnt'] = $this->bom_model->get_material_cut($params);

		$data['GJ_GB']         = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['UNIT']		   = $this->main_model->get_selectInfo("tch.CODE","UNIT");


		$data['idx'] = $idx;

		
		/* pagenation start */

		$this->load->library("pagination");		//CI 라이브러리의 Pagination class를 불러와 페이지에 관한 라이브러리를 사용
		$config['base_url'] = base_url(uri_string());		//http://localhost/DigitalTechFix/bom/stock(자기 자신의 URL을 반환)
		//echo $config['base_url'];
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
		//var_dump($this->data['pagenation']);
		/* pagenation end */

		$this->load->view('/bom/stock',$data);
	}




	/*	자재실사관리관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stock_ajax()
	{
		$idx = $this->input->post("idx");
		
		//$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$data['GJ_GB']         = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['UNIT']		   = $this->main_model->get_selectInfo("tch.CODE","UNIT");
		

		return $this->load->view('/bom/ajax_stock',$data);
	}



	/* BOM등록 */
	public function insert($idx="",$gjgb='')
	{
		
		$data['str'] = array(); //검색어관련
		$data['str']['bno'] = trim($this->input->get('bno')); //BL_NO
		$data['str']['iname'] = trim($this->input->get('iname')); //ITEM_NAME
		$data['str']['mscode'] = $this->input->get('mscode'); //MSAB
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['str']['gjcode'] = $this->input->get('gjcode'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$data['controller'] = $this;

		$params['BL_NO'] = "";
		$params['ITEM_NAME'] = "";
		$params['MSAB'] = "";
		$params['M_LINE'] = "";
		$params['GJ_GB'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['iname'])){
			$params['ITEM_NAME'] = $data['str']['iname'];
			$data['qstr'] .= "&iname=".$data['str']['iname'];
		}
		if(!empty($data['str']['mscode'])){
			$params['MSAB'] = $data['str']['mscode'];
			$data['qstr'] .= "&mscode=".$data['str']['mscode'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['gjcode'])){
			$params['GJ_GB'] = $data['str']['gjcode'];
			$data['qstr'] .= "&gjcode=".$data['str']['gjcode'];
		}
		if(!empty($data['str']['use'])){
			$params['USE_YN'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
		}


		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		

		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;


		$data['title'] = "BOM등록";
		
		
		$data['idx'] = $idx;
		$data['gjgb'] = $gjgb;

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		//$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['bomList']  = $this->bom_model->get_items_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_items_cut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");


		$data['MSAB']     = $this->main_model->get_selectInfo("tch.CODE","MSAB");
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");


		$data['insertBomList'] = $this->bom_model->get_bom_list($idx);
		
		
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



		$this->load->view('/bom/insert',$data);
	}



	/* ITEMS */
	public function trans($idx="")
	{
		
		

		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); 
		
		$data['str']['mline'] = $this->input->get('mline');
		$data['str']['sdate'] = $this->input->get('sdate');
		//var_dump($data['str']['sdate']);
		$data['str']['edate'] = $this->input->get('edate');
		$data['str']['bno']   = trim($this->input->get('bno'));
		$data['str']['item'] = trim($this->input->get('item'));
		
		$params['GJ_GB'] = "";
		$params['M_LINE'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['BL_NO'] = "";
		$params['ITEM'] = "";
		
		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['item'])){
			$params['ITEM'] = $data['str']['item'];
			$data['qstr'] .= "&item=".$data['str']['item'];
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

		$data['title'] = "자재소모현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		

		$data['transList']  = $this->bom_model->get_trans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_trans_cut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['MSAB']     = $this->main_model->get_selectInfo("tch.CODE","MSAB");
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

		
		
		$this->load->view('/bom/trans',$data);
	}



	public function trans_excel()
	{
		error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('memory_limit','-1');
        ini_set("max_execution_time","0");       
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '&lt;br /&gt;');
        date_default_timezone_set('Asia/Seoul');
        
        $this->load->library('PHPExcel');
        
        $objPHPExcel = new PHPExcel();
        
        
        $objPHPExcel->getProperties()->setCreator('Aliseon')
                                        ->setLastModifiedBy('Aliseon')
                                        ->setTitle('Aliseon_SALE LIST')
                                        ->setSubject('Aliseon_SALE LIST')
                                        ->setDescription('Aliseon_SALE LIST');

        function column_char($i) { return chr( 65 + $i ); }

        
        $headers = array('BL_NO','제품','수량','단위','자재코드','자재명','소요량','소요일');
        $last_char = column_char( count($headers) - 1 );
        $widths = array(30, 30, 15, 15, 20, 20, 15, 20);
                                        
        $objPHPExcel->setActiveSheetIndex(0);
        /** 상단 스타일지정 **/
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9EDF7');
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Nanum Gothic')->setSize(12);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        
        $objPHPExcel->getActiveSheet()->getStyle('A:'.$last_char)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        foreach($widths as $i => $w){
            $objPHPExcel->getActiveSheet()->setCellValue(column_char($i).'1', $headers[$i]);
            $objPHPExcel->setActiveSheetIndex()->getColumnDimension( column_char($i) )->setWidth($w);
        }
        
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //BL_NO
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');
		$data['str']['bno']   = $this->input->get('bno');
		$data['str']['item'] = $this->input->get('item');
		
		$params['GJ_GB'] = "";
		$params['M_LINE'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['BL_NO'] = "";
		$params['ITEM'] = "";
		
		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['item'])){
			$params['ITEM'] = $data['str']['item'];
			$data['qstr'] .= "&item=".$data['str']['item'];
		}
		

		$data['perpage'] = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		$pageNum = ($this->input->get('pageNum') > 0)?$this->input->get('pageNum') : 0;		
		$start = $pageNum;
		

		

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
        $this->data['cDetail_list'] = $this->bom_model->get_trans_list_nolimit($params);
		
		
		$nnn = array();
		if(count($this->data['cDetail_list']) > 0){
		
			foreach ($this->data['cDetail_list'] as $k=>$row) {
				$nnn[$k]['BL_NO'] = $row->BL_NO;
				$nnn[$k]['ITEM_NAME'] = $row->ITEM_NAME;
				$nnn[$k]['QTY'] = $row->QTY;
				$nnn[$k]['UNIT'] = $row->UNIT;
				$nnn[$k]['COMPONENT'] = $row->COMPONENT;
				$nnn[$k]['COMPONENT_NM'] = $row->COMPONENT_NM;
				$nnn[$k]['OUT_QTY'] = number_format($row->OUT_QTY);
				$nnn[$k]['TRANS_DATE'] = substr($row->TRANS_DATE,0,10);
			}

		}
		
		

        $rows = $nnn;
        $data = array_merge(array($headers), $rows);
        
        $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
        
		$fileName = iconv("UTF-8","EUC-KR","자재소모현황.xls");
        
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        //header('Content-type: application/x-msexcel;charset=utf-8');
		//header("Content-Type:text/html;charset=ISO-8859-1");
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
	}

	public function ajax_level2BomWriteform()
	{
		$data['title'] = "자재선택";
		$data['hidx'] = $this->input->post("hidx");
		$data['cidx'] = $this->input->post("cidx");
		$data['gjgb'] = $this->input->post("gjgb");

		$params['H_IDX']='';
		$params['C_IDX']='';
		$params['GJGB'] = '';
		
		$params['qstr'] = "?P";
		$params['qstr'] .= (!empty($this->input->post('perpage')))?'':'';

		$params['H_IDX']=$data['hidx'];
		$params['C_IDX']=$data['cidx'];
		$params['GJGB'] = $data['gjgb'];

		$data['seq'] = (!empty($this->input->post("seq")))?$this->input->post("seq"):"";
		$data['set'] = (!empty($this->input->post("set")))?$this->input->post("set"):"";
		
		
		//$params['materialList'] = $this->bom_model->get_bom_material($data,0,50);
		$data['materialList'] = $this->bom_model->get_level2Bom_material($params,0,50);
		$this->data['cnt'] = $this->bom_model->get_level2Bom_material_cut($params);

		$params['seq'] = $data['seq'];
		$params['set'] = $data['set'];


		return $this->load->view('/ajax/level2bomwriteform',$data);
	}


	

	/* BOM등록 등록*/
	public function ajax_bomWriteform()
	{
		$params['title'] = "자재선택";
		$params['idx'] = $this->input->post("idx");
		$params['gjgb'] = $this->input->post("gjgb");

		
		$params['qstr'] = "?P";
		$params['qstr'] .= (!empty($this->input->post('perpage')))?'':'';

		
		$data['idx'] = $this->input->post("idx");
		$data['gjgb'] = $this->input->post("gjgb");
		$data['seq'] = (!empty($this->input->post("seq")))?$this->input->post("seq"):"";
		$data['set'] = (!empty($this->input->post("set")))?$this->input->post("set"):"";
		
		
		$params['materialList'] = $this->bom_model->get_bom_material($data,0,50);
		$this->data['cnt'] = $this->bom_model->get_bom_material_cut($data);

		$params['seq'] = $data['seq'];
		$params['set'] = $data['set'];


		return $this->load->view('/ajax/bomWriteform',$params);

	}


	
	public function ajax_bomWriteform_list()
	{
		
		$params['idx'] = $this->input->post("idx");
		$params['gjgb'] = $this->input->post("gjgb");
				
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 2;
		$start = 50 * ($pageNum - 1);

		$data['idx'] = $this->input->post("idx");
		$data['gjgb'] = $this->input->post("gjgb");
		$data['seq'] = (!empty($this->input->post("seq")))?$this->input->post("seq"):"";
		$data['set'] = (!empty($this->input->post("set")))?$this->input->post("set"):"";


		$params['materialList'] = $this->bom_model->get_bom_material($data,$start,50);
		$params['pageNum'] = $pageNum;
		$cut = $this->bom_model->get_bom_material_cut($data)-$start;

		//$datan['num'] = $cut;

		return $this->load->view('/ajax/bomWriteform_list',$params);
	}




	public function ajax_bom_insertform()
	{
		$params = array();
		foreach($this->input->post("comIdx") as $i=>$cidx){
			
			$chkBom = $this->bom_model->get_check_bom($this->input->post("itemIdx"),$cidx);
			if($chkBom->num > 0){
				continue;
			}
			
			$params[] = array(
				"H_IDX"       => $this->input->post("itemIdx"),
				"C_IDX"       => $cidx,
				"INSERT_ID"   => $this->session->userdata('user_name'),
				"INSERT_DATE" => date("Y-m-d H:i:s",time())
			);
		}
		
		if(!empty($params)){
			$data['ins_id'] = $this->bom_model->set_bom_insertform($params);
			$data['msg'] = ($data['ins_id'] > 0)?"등록되었습니다.":"등록실패-관리자문의 code-0001";
		}else{
			$data['msg'] = "";
		}
		
		echo json_encode($data);

	}

	/* 
	* 자재정보삭제 
	* 삭제하려는 자재정보가 BOM리스트에 존재하는경우 삭제가 안되게 한다.
	*/
	public function ajax_delete_materials()
	{
		$param['IDX'] = $this->input->post("idx");
		$chkBom = $this->bom_model->check_bom_info($param,"C_IDX");
		if($chkBom > 0){
			$msg['set'] = 0;
			$msg['text'] = 'BOM에 등록된 자재입니다 BOM에 등록된 자재를 삭제후 다시 시도하세요';
			echo json_encode($msg);
			return false;
		}
		$data = $this->bom_model->delete_material($param);
		if($data > 0){
			$msg['set'] = 1;
			$msg['text'] = "삭제되었습니다.";
		}else{
			$msg['set'] = 0;
			$msg['text'] = "삭제처리에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($msg);

	}


	/* 
	* items삭제 
	* 
	*/
	public function ajax_delete_items()
	{
		$param['IDX'] = $this->input->post("idx");
		$chkBom = $this->bom_model->check_bom_info($param,"H_IDX");
		if($chkBom > 0){
			$msg['set'] = 0;
			$msg['text'] = 'BOM에 등록된 ITEM입니다 BOM에 등록된 item를 삭제후 다시 시도하세요';
			echo json_encode($msg);
			return false;
		}
		$data = $this->bom_model->delete_item($param);
		if($data > 0){
			$msg['set'] = 1;
			$msg['text'] = "삭제되었습니다.";
		}else{
			$msg['set'] = 0;
			$msg['text'] = "삭제처리에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($msg);

	}


	/*
	* BOM 리스트에 여유율,PT,POINT,REEL 을 업데이트
	* idx : BOM > IDX
	*/
	public function ajax_bomlist_update()
	{
		$params = array(
			"POINT"     => $this->input->post("point"),
			//"WORK_ALLO" => $this->input->post("work"),
			//"PT"        => $this->input->post("pt"),
			//"REEL_CNT"  => $this->input->post("reel"),
			"UPDATE_ID" => $this->session->userdata('user_name'),
			"UPDATE_DATE" => date("Y-m-d H:i:s",time())
		);
		
		$data = $this->bom_model->set_bomlistUpdate($params,$this->input->post("idx"));
		echo $data;
	}

	public function ajax_l2_bomlist_update()
	{
		$params = array(
			"POINT"     => $this->input->post("point"),
			"UPDATE_ID" => $this->session->userdata('user_name'),
			"UPDATE_DATE" => date("Y-m-d H:i:s",time())
		);
		
		$data = $this->bom_model->set_l2_bomlistUpdate($params,$this->input->post("idx"));
		echo $data;
	}
	public function ajax_l3_bomlist_update()
	{
		$params = array(
			"POINT"     => $this->input->post("point"),
			"UPDATE_ID" => $this->session->userdata('user_name'),
			"UPDATE_DATE" => date("Y-m-d H:i:s",time())
		);
		
		$data = $this->bom_model->set_l3_bomlistUpdate($params,$this->input->post("idx"));
		echo $data;
	}


	public function ajax_bom_update()
	{
		if($this->input->post("chk") == "1"){

			$COMPONENT = $this->bom_model->get_material_info($this->input->post("CIDX"));
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX"),
				"WORK_ALLO"   => $COMPONENT->WORK_ALLO,
				"PT"          => $COMPONENT->PT,
				"REEL_CNT"    => $COMPONENT->REEL_CNT,
				"INSERT_ID"   => $this->session->userdata('user_name'),
				"INSERT_DATE" => date("Y-m-d H:i:s",time())
			);
			$data = $this->bom_model->set_bom_formUpdate($param);

			
		}else{
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX")
			);
			$data = $this->bom_model->set_bom_formDelete($param);

		}
		
		$text['msg'] = "등록오류-관리자에게 문의하세요";
		if($data > 0){
			
			$text['msg'] = "처리되었습니다. - 팝업창을 닫기시 적용됩니다.";

		}
		echo json_encode($text);
	}

	public function ajax_l2_bom_update()
	{
		if($this->input->post("chk") == "1"){

			$COMPONENT = $this->bom_model->get_material_info($this->input->post("CIDX"));
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX"),
				"L2_IDX"	  => $this->input->post("L2IDX"),
				"WORK_ALLO"   => $COMPONENT->WORK_ALLO,
				"PT"          => $COMPONENT->PT,
				"REEL_CNT"    => $COMPONENT->REEL_CNT,
				"INSERT_ID"   => $this->session->userdata('user_name'),
				"INSERT_DATE" => date("Y-m-d H:i:s",time())
			);
			$data = $this->bom_model->set_l2_bom_formUpdate($param);

			
		}else{
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX"),
				"L2_IDX"	  => $this->input->post("L2IDX")
			);
			$data = $this->bom_model->set_l2_bom_formDelete($param);

		}
		
		$text['msg'] = "등록오류-관리자에게 문의하세요";
		if($data > 0){
			
			$text['msg'] = "처리되었습니다. - 팝업창을 닫기시 적용됩니다.";

		}
		echo json_encode($text);
	}

	public function ajax_l3_bom_update()
	{
		if($this->input->post("chk") == "1"){

			$COMPONENT = $this->bom_model->get_material_info($this->input->post("CIDX"));
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX"),
				"L2_IDX"	  => $this->input->post("L2IDX"),
				"L3_IDX"	  => $this->input->post("L3IDX"),
				"WORK_ALLO"   => $COMPONENT->WORK_ALLO,
				"PT"          => $COMPONENT->PT,
				"REEL_CNT"    => $COMPONENT->REEL_CNT,
				"INSERT_ID"   => $this->session->userdata('user_name'),
				"INSERT_DATE" => date("Y-m-d H:i:s",time())
			);
			$data = $this->bom_model->set_l3_bom_formUpdate($param);

			
		}else{
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX"),
				"L2_IDX"	  => $this->input->post("L2IDX"),
				"L3_IDX"	  => $this->input->post("L3IDX")
			);
			$data = $this->bom_model->set_l3_bom_formDelete($param);

		}
		
		$text['msg'] = "등록오류-관리자에게 문의하세요";
		if($data > 0){
			
			$text['msg'] = "처리되었습니다. - 팝업창을 닫기시 적용됩니다.";

		}
		echo json_encode($text);
	}




	public function materialUpdate()
	{
		


		$params = array(
			"COMPONENT"       => trim($this->input->post("COMPONENT")),
			"COMPONENT_NM"    => trim($this->input->post("COMPONENT_NM")),
			"SPEC"            => trim($this->input->post("SPEC")),
			"REEL_CNT"        => trim($this->input->post("REEL_CNT")),
			"WORK_ALLO"       => trim($this->input->post("WORK_ALLO")),
			"UNIT"       	  => trim($this->input->post("UNIT")),
			//"PT"              => trim($this->input->post("PT")),
			"PRICE"           => trim($this->input->post("PRICE")),
			//"INTO_DATE"       => trim($this->input->post("INTO_DATE")),
			//"REPL_DATE"       => trim($this->input->post("REPL_DATE")),
			"REMARK"          => trim($this->input->post("REMARK")),
			"COL1"            => trim($this->input->post("COL1")),
			"COL2"            => trim($this->input->post("COL2")),
			"GJ_GB"           => trim($this->input->post("GJ_GB")),
			"USE_YN"          => $this->input->post("USE_YN"),
			"INSERT_ID"       => $this->session->userdata('user_name'),
			"INSERT_DATE"     => date("Y-m-d H:i:s",time())
		);

		if(!empty($this->input->post("midx"))){ //수정인경우
			
			$params['UPDATE_DATE'] = date("Y-m-d H:i:s",time());
			$params['UPDATE_ID'] = $this->session->userdata('user_name');
			unset($params['INSERT_DATE']);
			unset($params['INSERT_ID']);
			$data = $this->bom_model->set_materialUpdate($params,$this->input->post("midx"));
			$msg = "변경되었습니다.";
		}else{
			$data = $this->bom_model->set_materialInsert($params);
			$msg = "등록되었습니다.";
		}
		
		if($data > 0){
			echo "$msg";
			//redirect('/bom/materials');
		}


		

	}


	public function itemsUpdate()
	{
		
		$params = array(
			"BL_NO"       => trim($this->input->post("BL_NO")),
			"MSAB"        => trim($this->input->post("MSAB")),
			"1ST_CLASS"   => trim($this->input->post("1ST_CLASS")),
			"2ND_CLASS"   => trim($this->input->post("2ND_CLASS")),
			"ITEM_NAME"   => trim($this->input->post("ITEM_NAME")),
			"ITEM_SPEC"   => trim($this->input->post("ITEM_SPEC")),
			"MODEL"       => trim($this->input->post("MODEL")),
			"STATE"       => trim($this->input->post("STATE")),
			"PACKING"     => trim($this->input->post("PACKING")),
			"CUSTOMER"    => trim($this->input->post("CUSTOMER")),
			"PRICE"       => trim($this->input->post("PRICE")),
			"UNIT"        => trim($this->input->post("UNIT")),
			"INSERT_DATE" => $this->input->post("INSERT_DATE"),
			"INSERT_ID"   => trim($this->input->post("INSERT_ID")),
			"M_LINE"      => trim($this->input->post("M_LINE")),
			"P_T"         => trim($this->input->post("P_T")),
			"USE_YN"      => trim($this->input->post("USE_YN")),
			"2ND_LINE"    => $this->input->post("2ND_LINE"),
			"2ND_P_T"     => trim($this->input->post("2ND_P_T")),
			"3ND_LINE"    => $this->input->post("3ND_LINE"),
			"3ND_P_T"     => trim($this->input->post("3ND_P_T")),
			"REMARK"      => $this->input->post("REMARK"),
			"GJ_GB"       => $this->input->post("GJ_GB")
		);

		
		if(!empty($this->input->post("midx"))){ //수정인경우
			$time = time() + (60 * 60 * 9);
			$params['UPDATE_DATE'] = date("Y-m-d H:i:s",$time);
			$params['UPDATE_ID'] = $this->session->userdata('user_name');
			unset($params['INSERT_DATE']);
			unset($params['INSERT_ID']);
			$data = $this->bom_model->set_itemsUpdate($params,$this->input->post("midx"));
			$msg = "변경되었습니다.";

		}else{
			$data = $this->bom_model->set_itemsInsert($params);
			$msg = "등록되었습니다.";
		}
		
		if($data > 0){
			echo $msg;
		}
	}

	public function level2($hidx="",$cidx='',$gjgb='')
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['bno'] = trim($this->input->get('bno')); //BL_NO
		$data['str']['compname'] = trim($this->input->get('compname')); //2LV COMPONENT_NM 
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		//$data['str']['use'] = $this->input->get('use'); //USE_YN

		$data['controller'] = $this;

		$params['BL_NO'] = "";
		$params['COMPONENT_NM'] = "";
		$params['M_LINE'] = "";
		//$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['compname'])){
			$params['COMPONENT_NM'] = $data['str']['compname'];
			$data['qstr'] .= "&compname=".$data['str']['compname'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		// if(!empty($data['str']['use'])){
		// 	$params['USE_YN'] = $data['str']['use'];
		// 	$data['qstr'] .= "&use=".$data['str']['use'];
		// }


		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		

		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;


		$data['title'] = "2레벨 BOM 등록";
		
		
		$data['hidx'] = $hidx;
		$data['cidx'] = $cidx;
		$data['gjgb'] = $gjgb;

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";

		$data['bomList']  = $this->bom_model->get_level2_items($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_level2_cut($params);
		//$this->data['cnt'] = $this->bom_model->get_items_cut($params);

		 $data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['Rlist'] = $this->bom_model->get_level2_Rlist($hidx,$cidx);
		
		
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


		$this->load->view('/bom/level2',$data);
	}
	
	public function level3($hidx="",$cidx='',$l2idx='')
	{
		$data['str'] = array(); //검색어관련
		//$data['str']['bno'] = trim($this->input->get('bno')); //BL_NO
		$data['str']['compname'] = trim($this->input->get('compname')); //2LV COMPONENT_NAME
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE

		$data['controller'] = $this;

		$params['BL_NO'] = "";
		$params['COMPONENT_NM'] = "";
		$params['MSAB'] = "";
		$params['M_LINE'] = "";
		$params['GJ_GB'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['compname'])){
			$params['COMPONENT_NM'] = $data['str']['compname'];
			$data['qstr'] .= "&compname=".$data['str']['compname'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}


		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		

		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;


		$data['title'] = "3레벨 BOM 등록";
		
		
		$data['hidx'] = $hidx;
		$data['cidx'] = $cidx;
		$data['l2idx'] = $l2idx;
		// $data['gjgb'] = $gjgb;

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";

		$data['bomList']  = $this->bom_model->get_level3_items($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_level3_cut($params);

		// $data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['Rlist'] = $this->bom_model->get_level3_Rlist($hidx,$cidx,$l2idx);
		
		
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


		$this->load->view('/bom/level3',$data);
	}

	public function ajax_level3BomWriteform()
	{
		$data['title'] = "자재선택";
		$data['hidx'] = $this->input->post("hidx");
		$data['cidx'] = $this->input->post("cidx");
		$data['l2idx'] = $this->input->post("l2idx");
		$data['gjgb'] = "ASS";

		$params['H_IDX']='';
		$params['C_IDX']='';
		$params['L2_IDX']='';
		$params['GJ_GB']='';
		
		$params['qstr'] = "?P";
		$params['qstr'] .= (!empty($this->input->post('perpage')))?'':'';

		$params['H_IDX']=$data['hidx'];
		$params['C_IDX']=$data['cidx'];
		$params['L2_IDX'] = $data['l2idx'];
		$params['GJ_GB'] = $data['gjgb'];

		$data['seq'] = (!empty($this->input->post("seq")))?$this->input->post("seq"):"";
		$data['set'] = (!empty($this->input->post("set")))?$this->input->post("set"):"";
		
		
		//$params['materialList'] = $this->bom_model->get_bom_material($data,0,50);
		$data['materialList'] = $this->bom_model->get_level3Bom_material($params,0,50);
		$this->data['cnt'] = $this->bom_model->get_level3Bom_material_cut($params);

		$params['seq'] = $data['seq'];
		$params['set'] = $data['set'];


		return $this->load->view('/ajax/level3bomwriteform',$data);
	}
}