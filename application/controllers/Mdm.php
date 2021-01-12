<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdm extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model(array('main_model','item_model','biz_model','register_model','bom_model'));

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
		$data['str'] = array(); //검색어관련
		$data['str']['code'] = trim((string)$this->input->get('code'));
		$data['str']['name'] = trim((string)$this->input->get('name'));
		$data['str']['use'] = $this->input->get('use');
		$data['str']['d_code'] = trim((string)$this->input->get('d_code'));
		$data['str']['d_name'] = trim((string)$this->input->get('d_name'));
		$data['str']['d_use'] = $this->input->get('d_use');

		$hid  = $this->input->get('hid');

		$params['CODE'] = "";
		$params['NAME'] = "";
		$params['USE'] = "";
		$params1['D_CODE'] = "";
		$params1['D_NAME'] = "";
		$params1['D_USE'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['code'])){
			$params['CODE'] = $data['str']['code'];
			$data['qstr'] .= "&code=".$data['str']['code'];
		}
		if(!empty($data['str']['name'])){
			$params['NAME'] = $data['str']['name'];
			$data['qstr'] .= "&name=".$data['str']['name'];
		}
		if(!empty($data['str']['use'])){
			$params['USE'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
		}
		if(!empty($data['str']['d_code'])){
			$params1['D_CODE'] = $data['str']['d_code'];
			if(!$hid){
				$data['qstr'] .= "&d_code=".$data['str']['d_code'];
			}
		}
		if(!empty($data['str']['d_name'])){
			$params1['D_NAME'] = $data['str']['d_name'];
			if(!$hid){
				$data['qstr'] .= "&d_name=".$data['str']['d_name'];
			}
		}
		if(!empty($data['str']['d_use'])){
			$params1['D_USE'] = $data['str']['d_use'];
			if(!$hid){
				$data['qstr'] .= "&d_use=".$data['str']['d_use'];
			}
		}

		$data['title'] = "공통코드등록";
		
		
		$data['headList']   = $this->main_model->get_cocdHead_list($params);
		$data['detailList'] = $this->main_model->get_cocdDetail_list($hid,$params1);

		$data['H_IDX']      = $hid;
		$data['de_show_chk']= ($hid != "")?true:false;

		$this->load->view('main',$data);
		
	}


	public function item($idx="")
	{
		/*$data['title'] = "품목등록";
		$data['headList']   = $this->item_model->get_itemHead_list();
		$data['detailList'] = $this->item_model->get_itemDetail_list($hid);

		$data['H_IDX']      = $hid;
		$data['de_show_chk']= ($hid != "")?true:false;
		//$data = '';

		$this->load->view('item',$data);
		*/

		
		//$this->load->library('barcode');
		
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
		
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;

		$data['pageNum'] = $start;

		$data['title'] = "품목등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['seq'] = "";
		$data['set'] = "";
		
		
		
		
		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		//$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['bomList']  = $this->bom_model->get_items_list_item($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_items_cut_item($params);

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

		
		
		$this->load->view('/bom/index_itemx',$data);



	}


	public function biz()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['custnm'] = trim((string)$this->input->get('custnm'));
		$data['str']['address'] = trim((string)$this->input->get('address'));

		$params['CUST_NM'] = "";
		$params['ADDRESS'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['custnm'])){
			$params['CUST_NM'] = $data['str']['custnm'];
			$data['qstr'] .= "&custnm=".$data['str']['custnm'];
		}
		if(!empty($data['str']['address'])){
			$params['ADDRESS'] = $data['str']['address'];
			$data['qstr'] .= "&address=".$data['str']['address'];
		}

		$data['title'] = "업체등록";
		$data['bizList']   = $this->biz_model->get_bizReg_list($params); 
		$this->load->view('/biz/index',$data);
	}
	

	public function excelDown($hidx="")
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

        
        $headers = array('HEAD-CODE','CODE','NAME','사용유무','비고');
        $last_char = column_char( count($headers) - 1 );
        $widths = array(10, 30, 40, 50);
                                        
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
		$data['str']['d_code'] = $this->input->get('d_code');
		$data['str']['d_name'] = $this->input->get('d_name');
		$data['str']['d_use'] = $this->input->get('d_use');

		$params1['D_CODE'] = "";
		$params1['D_NAME'] = "";
		$params1['D_USE'] = "";

		if(!empty($data['str']['d_code'])){
			$params1['D_CODE'] = $data['str']['d_code'];
			$data['qstr'] .= "&d_code=".$data['str']['d_code'];
		}
		if(!empty($data['str']['d_name'])){
			$params1['D_NAME'] = $data['str']['d_name'];
			$data['qstr'] .= "&d_name=".$data['str']['d_name'];
		}
		if(!empty($data['str']['d_use'])){
			$params1['D_USE'] = $data['str']['d_use'];
			$data['qstr'] .= "&d_use=".$data['str']['d_use'];
		}
        
        
        $this->data['cDetail_list'] = $this->main_model->get_cocdDetail_list($hidx,$params1);
		$nnn = array();
		if(count($this->data['cDetail_list']) > 0){
		
			foreach ($this->data['cDetail_list'] as $k=>$row) {
				$nnn[$k]['H_CODE'] = $row->H_CODE;
				$nnn[$k]['CODE'] = $row->CODE;
				$nnn[$k]['NAME'] = $row->NAME;
				$nnn[$k]['USE_YN'] = ($row->USE_YN == "Y")?"사용":"미사용";
				$nnn[$k]['REMARK'] = strip_tags($row->REMARK);
			}

		}
		
		

        $rows = $nnn;
        $data = array_merge(array($headers), $rows);
        
        $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
        
		$fileName = iconv("UTF-8","EUC-KR","공통코드-디테일.xls");
        
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        //header('Content-type: application/x-msexcel;charset=utf-8');
		//header("Content-Type:text/html;charset=ISO-8859-1");
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
	}



	
	/* 공통코드 HEAD 폼 호출 */
	public function ajax_cocdHead_form()
	{
		$params['title'] = "공통코드-HEAD";
		$params['mod'] = 0;
		
		
		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->main_model->get_cocdHead_info($_POST['IDX']);
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		
		return $this->load->view('/ajax/cocd_head',$params);
	}


	public function ajax_cocdDetail_form()
	{
		$params['title'] = "공통코드-DETAIL";
		$params['mod'] = '';
		
		$params['hidx'] = $this->input->post("hidx");
		

		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->main_model->get_cocdDetail_info($this->input->post("idx"));
			$params['data'] = $data;
			$params['mod'] = 1;
			$params['hidx'] = $data->H_IDX;
		}

		$params['headList']  = $this->main_model->get_cocdHead_info($params['hidx']);
		
		return $this->load->view('/ajax/cocd_detail',$params);
	}

	//공통코드 head update
	public function set_cocd_HeadUpdate()
	{
		$params['mod']      = $this->input->post("mod");
		$params['CODE']    = $this->input->post("CODE");
		$params['NAME']    = $this->input->post("NAME");
		$params['USE_YN']    = $this->input->post("USE_YN");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');

		$ins = $this->main_model->codeHead_update($params);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins != ""){
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}

	
	//공통코드 detail update
	public function set_cocd_DetailUpdate()
	{
		$params['mod']       = $this->input->post("mod");
		$params['H_IDX']     = $this->input->post("H_IDX");
		$params['S_NO']     = $this->input->post("S_NO");
		$params['CODE']     = $this->input->post("CODE");
		$params['NAME']     = $this->input->post("NAME");
		$params['USE_YN']    = $this->input->post("USE_YN");
		$params['REMARK']  = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');



		$ins = $this->main_model->codeDetail_update($params);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins != ""){
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}



	public function set_cocdHead_delete()
	{

		$del = $this->main_model->delete_cocdHead($_POST['code']);
		echo $del;
		
	}


	public function set_cocdDetail_delete()
	{

		$del = $this->main_model->delete_cocdDetail($_POST['idx']);
		echo $del;
		
	}


	/* head code 중복체크 */
	public function ajax_cocdHaedchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->main_model->ajax_cocdHaedchk('CODE',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "중복된 코드입니다.";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);
		
	}


	/* head code 중복체크 */
	public function ajax_cocdDetailchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->main_model->ajax_cocdDetailchk('CODE',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "중복된 코드입니다.";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);
		
	}




	public function infoform($idx="")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['mid'] = trim($this->input->get('mid')); //MEMBER ID
		$data['str']['mname'] = trim($this->input->get('mname')); //MEMBER ID
		$data['str']['level'] = $this->input->get('level'); //LEVEL
		
		$params['ID'] = "";
		$params['NAME'] = "";
		$params['LEVEL'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['mid'])){
			$params['ID'] = $data['str']['mid'];
			$data['qstr'] .= "&mid=".$data['str']['mid'];
		}
		if(!empty($data['str']['mname'])){
			$params['NAME'] = $data['str']['mname'];
			$data['qstr'] .= "&mname=".$data['str']['mname'];
		}
		if(!empty($data['str']['level'])){
			$params['LEVEL'] = $data['str']['level'];
			$data['qstr'] .= "&level=".$data['str']['level'];
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
		

		$data['title'] = "인사정보등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		
		$data['memberList'] = $this->register_model->get_member_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->register_model->get_member_cut($params);
		
		

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


		
		$this->load->view('/register/infoform',$data);
	}



	public function infolist($idx="")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['mid'] = $this->input->get('mid'); //MEMBER ID
		$data['str']['mname'] = $this->input->get('mname'); //MEMBER ID
		$data['str']['level'] = $this->input->get('level'); //LEVEL
		
		$params['ID'] = "";
		$params['NAME'] = "";
		$params['LEVEL'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['mid'])){
			$params['ID'] = $data['str']['mid'];
			$data['qstr'] .= "&mid=".$data['str']['mid'];
		}
		if(!empty($data['str']['mname'])){
			$params['NAME'] = $data['str']['mname'];
			$data['qstr'] .= "&mname=".$data['str']['mname'];
		}
		if(!empty($data['str']['level'])){
			$params['LEVEL'] = $data['str']['level'];
			$data['qstr'] .= "&level=".$data['str']['level'];
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
		

		$data['title'] = "사용자등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		
		$data['memberList'] = $this->register_model->get_member_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->register_model->get_member_cut($params);
		
		

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


		
		$this->load->view('/register/userform',$data);
	}



	public function ajax_set_memberinfo()
	{
		$mode = $this->input->post("mode");
		$idx  = $this->input->post("idx");

		$data = array();
		if(!empty($idx)){
			$data['memInfo'] = $this->register_model->get_member_info($idx);
		}
		
		$this->load->view('/register/ajax_infoform',$data);
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
			
			$params['UPDATE_DATE'] = date("Y-m-d H:i:s",time());
			$params['UPDATE_ID'] = $this->session->userdata('user_name');
			unset($params['INSERT_DATE']);
			unset($params['INSERT_ID']);
			$data = $this->bom_model->set_itemsUpdate_item($params,$this->input->post("midx"));
			$msg = "변경되었습니다.";

		}else{
			$data = $this->bom_model->set_itemsInsert_item($params);
			$msg = "등록되었습니다.";
		}
		
		if($data > 0){
			echo $msg;
		}
	}


	public function index_ajax($idx="")
	{

		$idx = $this->input->post("idx");
		$data['bomInfo']  = (!empty($idx))?$this->bom_model->get_items_info_item($idx):"";
		
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
		

		return $this->load->view('/bom/ajax_index_item',$data);
		

		
	}






}
