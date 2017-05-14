<?php
namespace common\components;

use Yii;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Settings;
use \PHPExcel_Style_Fill;
use \PHPExcel_Writer_IWriter;
use \PHPExcel_Worksheet;
use \PHPExcel_Style;
use \PHPExcel_Style_Alignment;

/**
 * Export2Excel Model
 * 导出到EXCEL 模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class Export2Excel
{
	/**
	 * @var $path
	 * 文件保存的路径
	 * --------------
	 * @method Config
	 * @author Verdient。
	 */
	public $path;

	/**
	 * @var $format
	 * 文件格式
	 * --------------
	 * @method Config
	 * @author Verdient。
	 */
	public $format = 'xlsx';

	/**
	 * @var $enabeldFormat
	 * 允许的格式
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabeldFormat = ['xlsx'];

	/**
	 * excelDataFormat(Array $data)
	 * excel数据格式化
	 * ----------------------------
	 * @param Array $data 数据
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public static function excelDataFormat($data){
		$excel_title = null;
		$excel_ceils = [];
		foreach($data as $key => $value){
			if(!$excel_title){
				$excel_title = array_keys($value);
			}
			$excel_ceils[] = array_values($value);
		}
		return ['title' => $excel_title, 'ceils' => $excel_ceils];
	}

	/**
	 * getCssClass([String $code])
	 * 获取样式配置信息
	 * ---------------------------
	 * @param String $code css代码
	 * ---------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public static function getCssClass($code = null){
		$cssClass = [
			'red' => ['color' => 'FFFFFF', 'background' => 'FF0000'],
			'pink' => ['color' => '', 'background' => 'FFCCCC'],
			'green' => ['color' => '', 'background' => 'CCFF99'],
			'lightgreen' => ['color' => '', 'background' => 'CCFFCC'],
			'yellow' => ['color' => '', 'background' => 'FFFF99'],
			'white' => ['color' => '', 'background' => 'FFFFFF'],
			'grey' => ['color' => '000000', 'background' => '808080'],
			'greywhite' => ['color' => 'FFFFFF', 'background' => '808080'],
			'blue' => ['color' => 'FFFFFF', 'background' => 'blue'],
			'blueblack' => ['color' => '000000', 'background' => 'blue'],
			'lightblue' => ['color' => 'FFFFFF', 'background' => '6666FF'],
			'notice' => ['color' => '514721', 'background' => 'FFF6BF'],
			'header' => ['color' => 'FFFFFF', 'background' => '519CC6'],
			'headerblack' => ['color' => '000000', 'background' => '519CC6'],
			'odd' => ['color' => '', 'background' => 'E5F1F4'],
			'even' => ['color' => '', 'background' => 'F8F8F8'],
		];
		return empty($code) ? $cssClass : (isset($cssClass[$code]) ? $cssClass[$code] : []);
	}

	/**
	 * export2excel(Array $excel_content, String $excel_file[, Array $excel_props])
	 * 导出到excel
	 * ----------------------------------------------------------------------------
	 * @param Array $excel_content excel内容
	 * @param String $excel_file excel文件名称
	 * @param Array $excel_props excel署名信息
	 * ---------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function export2excel($excel_content, $excel_file, $excel_props = ['creator' => 'PHPExcel', 'title' => 'PHPExcel', 'subject' => 'PHPExcel', 'desc' => 'PHPExcel', 'keywords' => 'PHPExcel, Author: Verdient。', 'category' => 'PHPExcel']){
		if(!is_array($excel_content)){
			return false;
		}
		if(empty($excel_file)){
			return false;
		}
		$excelName = $this->save2Excel($excel_content, $excel_file, $excel_props);
		if($excelName){
			return $excelName;
		}
	}

	/**
	 * excelColumnName(Interger $index)
	 * excel列名称
	 * --------------------------------
	 * @param Interger $index 列数
	 * ---------------------------
	 * @return Array
	 * @author Verdient。
	 */
	protected static function excelColumnName($index){
		if($index >= 0 && $index < 26){
			return chr(ord('A') + $index);
		}else if ($index > 25){
			return (self::excelColumnName($index / 26)) . (self::excelColumnName($index % 26 + 1));
		}else{
			throw new \Exception("Invalid Column # " . ($index + 1));
		}
	}

	/**
	 * save2Excel(Array $excel_content, String $excel_file[, Array $excel_props])
	 * 保存到Excel
	 * ------------------------------------------------------------------
	 * @param Array $excel_content excel内容
	 * @param String $excel_file excel文件名称
	 * @param Array $excel_props excel署名信息
	 * ---------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	protected function save2Excel($excel_content, $excel_file, $excel_props = ['creator' => 'PHPExcel', 'title' => 'PHPExcel', 'subject' => 'PHPExcel', 'desc' => 'PHPExcel', 'keywords' => 'PHPExcel, Author: Verdient。', 'category' => 'PHPExcel']){
		if(!is_array($excel_content)){
			return false;
		}
		if(empty($excel_file)){
			return false;
		}
		$objPHPExcel = new PHPExcel();
		$objProps = $objPHPExcel->getProperties();
		$objProps->setCreator($excel_props['creator']);
		$objProps->setLastModifiedBy($excel_props['creator']);
		$objProps->setTitle($excel_props['title']);
		$objProps->setSubject($excel_props['subject']);
		$objProps->setDescription($excel_props['desc']);
		$objProps->setKeywords($excel_props['keywords']);
		$objProps->setCategory($excel_props['category']);
		$style_obj = new PHPExcel_Style();
		$style_array = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'wrap' => true
			],
		];
		$style_obj->applyFromArray($style_array);
		for($i = 0; $i < count($excel_content); $i++){
			$each_sheet_content = $excel_content[$i];
			if($i == 0){
				$objPHPExcel->setActiveSheetIndex(intval(0));
				$current_sheet = $objPHPExcel->getActiveSheet();
			}else{
				$objPHPExcel->createSheet();
				$current_sheet = $objPHPExcel->getSheet($i);
			}
			$current_sheet->setTitle(str_replace(array('/', '*', '?', '\\', ':', '[', ']'), array('_', '_', '_', '_', '_', '_', '_'), substr($each_sheet_content['sheet_name'], 0, 30))); //add by Scott
			$current_sheet->getColumnDimension()->setAutoSize(true);//设置宽度
			$_columnIndex = 'A';
			$lineRange = "A1:" . self::excelColumnName(count($each_sheet_content['sheet_title'])) . "1";
			$current_sheet->setSharedStyle($style_obj, $lineRange);
			if(array_key_exists('sheet_title', $each_sheet_content) && !empty($each_sheet_content['sheet_title'])){
				if (array_key_exists('headerColor', $each_sheet_content) && is_array($each_sheet_content['headerColor']) and !empty($each_sheet_content['headerColor'])) {
					if (isset($each_sheet_content['headerColor']["color"]) and $each_sheet_content['headerColor']['color'])
						$current_sheet->getStyle($lineRange)->getFont()->getColor()->setARGB($each_sheet_content['headerColor']['color']);

					if (isset($each_sheet_content['headerColor']["background"]) and $each_sheet_content['headerColor']['background']) {
						$current_sheet->getStyle($lineRange)->getFill()->getStartColor()->setRGB($each_sheet_content['headerColor']["background"]);
						$current_sheet->getStyle($lineRange)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					}
				}
				for($j = 0; $j < count($each_sheet_content['sheet_title']); $j++){
					$current_sheet->setCellValueByColumnAndRow($j, 1, $each_sheet_content['sheet_title'][$j]);
					if (array_key_exists('headerColumnCssClass', $each_sheet_content)) {
						if (isset($each_sheet_content["headerColumnCssClass"][$each_sheet_content['sheet_title'][$j]])) {
							$tempStyle = $each_sheet_content["headerColumnCssClass"][$each_sheet_content['sheet_title'][$j]];
							$tempColumn = self::excelColumnName($j + 1) . "1";
							if (isset($tempStyle["color"]) and $tempStyle['color'])
								$current_sheet->getStyle($tempColumn)->getFont()->getColor()->setARGB($tempStyle['color']);
							if (isset($tempStyle["background"]) and $tempStyle['background']) {
								$current_sheet->getStyle($tempColumn)->getFill()->getStartColor()->setRGB($tempStyle["background"]);
								$current_sheet->getStyle($tempColumn)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							}
						}
					}
					$current_sheet->getColumnDimension($_columnIndex)->setAutoSize(true);
					$_columnIndex++;
				}
			}
			if(array_key_exists('freezePane', $each_sheet_content) && !empty($each_sheet_content['freezePane'])){
				$current_sheet->freezePane($each_sheet_content['freezePane']);
			}
			if(array_key_exists('ceils', $each_sheet_content) && !empty($each_sheet_content['ceils'])){
				for ($row = 0; $row < count($each_sheet_content['ceils']); $row++) {
					$lineRange = "A" . ($row + 2) . ":" . self::excelColumnName(count($each_sheet_content['ceils'][$row])) . ($row + 2);
					if(($row + 1) % 2 == 1 and isset($each_sheet_content["oddCssClass"])){
						if($each_sheet_content["oddCssClass"]["color"]) {
							$current_sheet->getStyle($lineRange)->getFont()->getColor()->setARGB($each_sheet_content["oddCssClass"]["color"]);
						}
						if($each_sheet_content["oddCssClass"]["background"]){
							$current_sheet->getStyle($lineRange)->getFill()->getStartColor()->setRGB($each_sheet_content["oddCssClass"]["background"]);
							$current_sheet->getStyle($lineRange)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						}
					}else if(($row + 1) % 2 == 0 and isset($each_sheet_content["evenCssClass"])){
						if($each_sheet_content["evenCssClass"]["color"]){
							$current_sheet->getStyle($lineRange)->getFont()->getColor()->setARGB($each_sheet_content["evenCssClass"]["color"]);
						}
						if($each_sheet_content["evenCssClass"]["background"]){
							$current_sheet->getStyle($lineRange)->getFill()->getStartColor()->setRGB($each_sheet_content["evenCssClass"]["background"]);
							$current_sheet->getStyle($lineRange)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						}
					}
					for($l = 0; $l < count($each_sheet_content['ceils'][$row]); $l++){
						$current_sheet->setCellValueExplicitByColumnAndRow($l, $row + 2, $each_sheet_content['ceils'][$row][$l]);
					}
				}
			}

		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$path = $this->path . DIRECTORY_SEPARATOR . date('Ym');
		$tempDir = Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $path;
		if(!is_dir($tempDir)){
			mkdir($tempDir, 0755, true);
		}
		$file_name = str_replace(['/', '*', '?', '\\', ':', '[', ']'], ['_', '_', '_', '_', '_', '_', '_'], $excel_file) . '.xlsx';
		$objWriter->save($tempDir . DIRECTORY_SEPARATOR . $file_name);
		return  $path . DIRECTORY_SEPARATOR . $file_name;
	}
}