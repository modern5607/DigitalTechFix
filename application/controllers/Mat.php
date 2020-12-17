<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mat extends CI_Controller {

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
                $this->load->view('/layout/header',$this->data);
                call_user_func_array(array($this,$method), $params);
                $this->load->view('/layout/tail');
            } else {
                show_404();
            }
        }
	}



	public function ajax_setComponent()
	{
		$param['mode'] = $this->input->post("mode");
		$param['component'] = $this->input->post("component");
		

		$data = $this->bom_model->ajax_component_setting($param);
		echo json_encode($data);
	}



	/*	재고실사관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function materials($idx="")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['matCount'] = $this->input->get('matCount');
		$params['MATCOUNT'] = "";

		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		$data['qstr'] = "?P";
		//$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		
		if(!empty($data['str']['matCount'])){
			$params['MATCOUNT'] = $data['str']['matCount'];
			$data['qstr'] .= "&matCount=".$data['str']['matCount'];
		}

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
		
		
		


		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		

		$data['materialList']  = $this->bom_model->get_material_joinlist($params,$start,$config['per_page']);
		$count = $this->bom_model->get_material_excut($params);
		

		$this->data['cnt'] = $count['totnum'];

		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

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
		//$data['nnum'] = $count['nnum'];
		
		$this->load->view('/bom/mlist',$data);
		
	}



	public function materials_excelDown()
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

        
        $headers = array('자재코드','자재명','단뒤','현재고량','비교재고량','차이','입고일','갱신일');
        $last_char = column_char( count($headers) - 1 );
        $widths = array(30, 30, 15, 15, 15, 15, 15, 15);
                                        
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
		$data['str']['matCount'] = $this->input->get('matCount');
		
		$params['MATCOUNT'] = "";
		
		$data['qstr'] = "?P";
		if(!empty($data['str']['matCount'])){
			$params['MATCOUNT'] = $data['str']['matCount'];
			$data['qstr'] .= "&matCount=".$data['str']['matCount'];
		}
		

		$data['perpage'] = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		$pageNum = ($this->input->get('pageNum') > 0)?$this->input->get('pageNum') : 0;		
		$start = $pageNum;
		

		

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
        $this->data['excelList'] = $this->bom_model->get_material_joinlist($params,0,500);
		
		
		$nnn = array();
		if(count($this->data['excelList']) > 0){
		
			foreach ($this->data['excelList'] as $k=>$row) {
				$nnn[$k]['E_COMPONENT'] = $row->E_COMPONENT;
				$nnn[$k]['COMPONENT_NM'] = $row->COMPONENT_NM;
				$nnn[$k]['UNIT'] = $row->UNIT;
				$nnn[$k]['STOCK'] = number_format($row->STOCK);
				$nnn[$k]['E_STOCK'] = $row->E_STOCK;
				$nnn[$k]['STOCKNUM'] = $row->STOCKNUM;
				$nnn[$k]['INTO_DATE'] = substr($row->INTO_DATE,0,10);
				$nnn[$k]['REPL_DATE'] = substr($row->REPL_DATE,0,10);
			}

		}
		
		

        $rows = $nnn;
        $data = array_merge(array($headers), $rows);
        
        $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
        $todate = date("Y-m-d",time());
		$fileName = iconv("UTF-8","EUC-KR","재고실사관리_".$todate.".xls");
        
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        //header('Content-type: application/x-msexcel;charset=utf-8');
		//header("Content-Type:text/html;charset=ISO-8859-1");
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
	}



	/*	재고현황
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stocklist($idx="")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['component'] = $this->input->get('component'); //BL_NO
		$data['str']['comp_name'] = $this->input->get('comp_name'); //ITEM_NAME
		$data['str']['spec'] = $this->input->get('spec'); //GJ_GB
		$data['str']['gjgb'] = $this->input->get('gjgb'); //GJ_GB

		$params['GJ_GB'] = "";
		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['SPEC'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['comp_name'])){
			$params['COMPONENT_NM'] = $data['str']['comp_name'];
			$data['qstr'] .= "&comp_name=".$data['str']['comp_name'];
		}
		if(!empty($data['str']['spec'])){
			$params['SPEC'] = $data['str']['spec'];
			$data['qstr'] .= "&spec=".$data['str']['spec'];
		}

		
		if(!empty($this->input->get('perpage'))){
			$data['perpage'] = $this->input->get('perpage');
			$data['qstr'] .= "&perpage=".$data['perpage'];
		}else{
			$data['perpage'] = 20;
		}

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        
		if(!empty($this->input->get('pageNum'))){
			$pageNum = $this->input->get('pageNum');
			$data['qstr'] .= "&pageNum=".$pageNum;
		}else{
			$pageNum = 0;
		}
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		$data['title'] = "재고현황";
		$data['materialList']  = $this->bom_model->get_material_list_nx($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$this->data['cnt'] = $this->bom_model->get_material_cut_nx($params);
		
		$data['GJ_GB'] = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

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




	public function stocklist_excelDown($idx="")
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

        
        $headers = array('자재코드','자재명','규격','재고량','단위','입고일','갱신일');
        $last_char = column_char( count($headers) - 1 );
        $widths = array(30, 30, 15, 15, 15, 15, 15);
                                        
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
		$data['str']['component'] = $this->input->get('component'); //BL_NO
		$data['str']['comp_name'] = $this->input->get('comp_name'); //ITEM_NAME
		$data['str']['spec'] = $this->input->get('spec'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['SPEC'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['comp_name'])){
			$params['COMPONENT_NM'] = $data['str']['comp_name'];
			$data['qstr'] .= "&comp_name=".$data['str']['comp_name'];
		}
		if(!empty($data['str']['spec'])){
			$params['SPEC'] = $data['str']['spec'];
			$data['qstr'] .= "&spec=".$data['str']['spec'];
		}


		$limit = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		$start = (!empty($this->input->get('pageNum')))?$this->input->get('pageNum'):0;

		$this->data['excelList'] = $this->bom_model->get_material_list_nx($params,$start,$limit);
		
		
		$nnn = array();
		if(count($this->data['excelList']) > 0){
		
			foreach ($this->data['excelList'] as $k=>$row) {
				$nnn[$k]['COMPONENT'] = $row->COMPONENT;
				$nnn[$k]['COMPONENT_NM'] = $row->COMPONENT_NM;
				$nnn[$k]['SPEC'] = $row->SPEC;
				$nnn[$k]['STOCK'] = number_format($row->STOCK);
				$nnn[$k]['UNIT'] = $row->UNIT;
				$nnn[$k]['INTO_DATE'] = substr($row->INTO_DATE,0,10);
				$nnn[$k]['REPL_DATE'] = substr($row->REPL_DATE,0,10);
			}

		}
		
		

        $rows = $nnn;
        $data = array_merge(array($headers), $rows);
        
        $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
        
		$todate = date("Y-m-d",time());
		$fileName = iconv("UTF-8","EUC-KR","재고현황_".$todate.".xls");
        
        
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        //header('Content-type: application/x-msexcel;charset=utf-8');
		//header("Content-Type:text/html;charset=ISO-8859-1");
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
	}


	public function m1()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['component'] = $this->input->get('component'); //COMPONENT
		$data['str']['comp_name'] = $this->input->get('comp_name'); //COMPONENT_NM
		$data['str']['gjgb'] = $this->input->get('gjgb'); //GJ_GB

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['GJ_GB'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['comp_name'])){
			$params['COMPONENT_NM'] = $data['str']['comp_name'];
			$data['qstr'] .= "&comp_name=".$data['str']['comp_name'];
		}
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		

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


		$data['title'] = "안전재고등록";
		$data['componentList']  = $this->bom_model->get_component_mlist($params,$start,$config['per_page']);
		

		$this->data['cnt'] = $this->bom_model->get_component_mcut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		
		

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


		$this->load->view('/mat/m1',$data);
	}



	public function m2()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['component'] = $this->input->get('component'); //COMPONENT
		$data['str']['comp_name'] = $this->input->get('comp_name'); //COMPONENT_NM
		$data['str']['gjgb'] = $this->input->get('gjgb'); //GJ_GB
		$data['str']['quick'] = $this->input->get('quick'); //GJ_GB


		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['GJ_GB'] = "";
		$params['QUICK'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['comp_name'])){
			$params['COMPONENT_NM'] = $data['str']['comp_name'];
			$data['qstr'] .= "&comp_name=".$data['str']['comp_name'];
		}
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		if(!empty($data['str']['quick'])){
			$params['QUICK'] = $data['str']['quick'];
			$data['qstr'] .= "&quick=".$data['str']['quick'];
		}
		

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


		$data['title'] = "안전재고현황";
		$data['componentList']  = $this->bom_model->get_component_mlist2($params,$start,$config['per_page']);
		

		$this->data['cnt'] = $this->bom_model->get_component_mcut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		
		

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


		$this->load->view('/mat/m2',$data);
	}


	public function ajax_saveqty_update()
	{
		$param['IDX']       = $this->input->post("idx");
		$param['SAVE_QTY']  = $this->input->post("sqty");
		$param['SAVE_DATE'] = date("Y-m-d H:i:s",time());

		$data = $this->bom_model->ajax_saveqty_update($param);
		echo $data;
	}

	

	public function matform()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['tdate1'] = $this->input->get('tdate1'); //BL_NO
		$data['str']['tdate2'] = $this->input->get('tdate2'); //BL_NO
		
		$params['TDATE1'] = "";
		$params['TDATE2'] = "";
		
		$data['qstr'] = "?P";
	
		if(!empty($data['str']['tdate1'])){
			$params['TDATE1'] = $data['str']['tdate1'];
			$data['qstr'] .= "&tdate1=".$data['str']['tdate1'];
		}

		if(!empty($data['str']['tdate2'])){
			$params['TDATE2'] = $data['str']['tdate2'];
			$data['qstr'] .= "&tdate2=".$data['str']['tdate2'];
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

		$data['title'] = "자재입고등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		

		$data['transList']  = $this->bom_model->get_matform_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_matform_cut($params);
		
		
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

		
		
		$this->load->view('/mat/matform',$data);
	}




	public function matform_temp()
	{
		$data = $this->bom_model->get_matform_temp_list();
		$this->load->view('/mat/matform_temp',$data);
	}


	public function matform_temp_update()
	{
		$mode = $this->input->post('mode');

		$user_name = $this->session->userdata('user_name');
		
		if($mode == "update"){
			
			//$res = $this->db->get('T_TEMP_COM');
			$tempC = $this->bom_model->get_matform_temp_list();
			
			foreach($tempC['list'] as $row){
				
				$params['C_IDX']     = $row->COMPONENTNO;
				$params['TRANS_DATE']= $row->IPGO_DATE;
				$params['KIND']      = "IN";
				$params['IN_QTY']    = $row->QTY;
				$params['GJ_GB']     = $row->GJ_GB;
				$params['INSERT_DATE']= date('Y-m-d H:i:s',time());
				$params['INSERT_ID']  = $user_name;
				if($row->GUBUN == "입고"){
					$this->bom_model->ajax_matform_temp_insert($params);
					$this->bom_model->ajax_matform_component_update($row->COMPONENTNO,$row->IPGO_DATE);
					
				}

			}

			
			
						
			$this->main_model->delete_matform_ex();

			

		}
		echo 1;
	}



	public function matform_temp_cancel() {
		$this->main_model->delete_matform_ex();
	}




}