<?php

class Base_View_Table
{

	private $_tableClass = 'tableDiv';
	private $_tableID = null;
	private $_tableTitle = null;

	private $_trIdPrefix = 'row_';  // prefix for each row id
	private $_trIdField = '';  // field to use in as row id - default '' = $rownum;

	//if set different that class default width in table.css
	private $_tableDivWidth = null;
	private $_tableDivWidthMinus = 17;  // padding for scrollbar in px
	private $_tableWidth = null;
	private $_tableHeight = null;  // only works if setStickyHeader = true

	private $_data = array();
	private $_rowCount = 0;

	private $_columns = array();
	private $_colHeaders = array();

	private $_exportType = 0;
	private $_exportData = null;

	//table extras
	private $_showCalc = false;
	private $_stickyHeader = true;
	private $_tableStripe = true;
	private $_displayEmpty = true;
	private $_displayMsg = 'No Data Found';

	/**
	 *
	 * Create a new table.  Div around table.  Use setStickyHeader to create separate table for col headers and data
	 *
	 * @param array $dataAr array of data
	 * @param array $attrAr attributes of div around table
	 * <br> Ex. array('class' => 'myClass')
	 *  <br> [id]		=>  id of table div
	 *  <br> [class]	=>  class of table div.  **changing will undo all css in table.css
	 *  <br> [width]  	=>  width in px .  Will include 16px margin for scroll bar.  Table is -16 px from this
	 * @param int $exportType  Put Export links above table.  Can use multiple types (ex.  023 ).
	 * - 0 = none, 1 = xls, 2 = pdf, 9 = all
	 * @param string $exportData
	 * @throws Zend_Exception
	 */
	public function __construct($dataAr, $attrAr = array(),$exportType = 0, $exportData = null )
	{

		if(! is_array($dataAr) ){
			throw new Zend_Exception('Data is not of type Array() ');
		}
		$this->_data = $dataAr;

		$cleanTattrAR = self::setTableAttrs($attrAr);


		//TODO calctype for table renderer

	}

	/**
	 *
	 * Show totals on bottom of table
	 * @param boolean $bool -  default false
	 * <br> Totals set by columnHeader attributes
	 */
	public function setShowCalcs($bool = false)
	{
		$this->_showCalc = $bool;
	}

	/**
	 *
	 * Allows float of header.  Puts headers and data in separate column and creates overflow span around data
	 * @param boolean $bool -  default true
	 * <br> Span for float is of class floatTableSpan
	 */
	public function setStickyHeader($bool = true)
	{
		$this->_stickyHeader = $bool;
	}

	/**
	 *
	 * Set table title centered about table
	 * @param String $tableTitle
	 * <br> Title is of class tableTitle
	 */
	public function setTableTitle($tableTitle = null)
	{
		$this->_tableTitle = $tableTitle;
	}

	/**
	 *
	 * Set zebra stripe on table
	 * @param String $tableTitle
	 */
	public function setZebra($bool = true)
	{
		$this->_tableStripe = $bool;
	}

	/**
	 *
	 * Set displayEmpty message if empty dataset
	 * @param Boolean  default true
	 */
	public function setDisplayEmpty($bool = true)
	{
		$this->_displayEmpty = $bool;
	}

	/**
	 *
	 * Set display message if empty dataset
	 * @param String $displayMsg
	 */
	public function setDisplayMsg($displayMsg = null)
	{
		$this->_displayMsg = $displayMsg;
	}

	public function getRowCount(){
		return count($this->_data);
	}

	/**
	 *
	 * Set id field to use as TR id for each row.  Default = "" which shows Rownum - 0 based.
	 * @param String $field
	 */
	public function setTrIdField($field)
	{
		$this->_trIdField = $field;
	}

	/**
	 *
	 * addCol - add column to table.
	 * @param String $dataField
	 * Needs to be field name in query result.  Cannot be null
	 * @param String $colName
	 * Column Header name.  Cannot be null
	 * @param Array<strings> $colAttrAr -  Array of params for columns.  Put attr in string.
	 *  <br> Ex. array('class' => 'myClass')
	 *  <br> [class]	=>  class of column td's
	 *  <br> [link]  	=>  url of link - array( [url], array([param] => [datafield])  )
	 *  <br> [align] 	=>  Align in cell  'L','R', or 'C'  :default = 'C')
	 *  <br> [printf]	=>  printf function call for cell contents
	 *  <br> [altname]  =>  Alternate Display field for data field.  Ex.  'Edit' for link.
	 *  <br> [special]  =>  Special type -
	 *  	'checkbox' - Enabled Checkbox.  Will check based on data value
	 *  	'bool' - Converts Bool (0,1) to No, Yes based on data value
	 * @param Aarry<strings> $colHeadAttrAr  - Array of params for column header.  Put attr in string.
	 *  <br> Ex. array('class' => 'myClass')
	 *  <br> [id]		=>  id of column header
	 *  <br> [class]	=>  class of column header td
	 *  <br> [hidden]	=>  true (not string) ommit to display -  will hide column (header and data)
	 *  <br> [link]  	=>  make cell contents a link.  array('url', array('param','dataField'))§
	 *  <br> [align] 	=>  Align in cell  'L','R', or 'C'  :default = 'C')
	 *  <br> [width]	=>  Width of cell in px  (default to fixed spacing)
	 *  <br> [calcType]	=> how column is calculated, only if setShowCalcs(true) | options {sum, avg, count, sumFields(use array for col names) }
	 * @throws Zend_Exception
	 */
	public function addCol($dataField,$colName, $colAttrAr = array(), $colHeadAttrAr = array())
	{
		if($dataField === null){
			throw new Zend_Exception('addCol - Datafield is null');
		}
		if($colName === null){
			throw new Zend_Exception('addCol - ColName is null');
		}

		if($colAttrAr === null ){
			$colAttrAr = array();
		}
		if($colHeadAttrAr === null ){
			$colHeadAttrAr = array();
		}
		//copy some header fields to data fields
		if(array_key_exists('hidden', $colHeadAttrAr)){
			$colAttrAr['hidden'] = $colHeadAttrAr['hidden'];
		}
		if(array_key_exists('width', $colHeadAttrAr)){
			$colAttrAr['width'] = $colHeadAttrAr['width'];
		}

		$colAttrArClean = self::cleanColAttrs($colAttrAr);
		$colHeadAttrArClean = self::cleanColHeaderAttrs($colHeadAttrAr);


		$this->_columns[] = array(	'dataField' 	=> $dataField,
				'colAttrAr' 	=> $colAttrArClean);
		$this->_colHeaders[] = array(		'colName' 		=> $colName,
				'colHeadAttrAr'	=> $colHeadAttrArClean);



	}

	/**
	 *
	 * Renders table to browser
	 */
	public function render()
	{
		self::renderTableDiv();
	}


	private function renderTableDiv()
	{
		if($this->_tableID === null){
			$tID = '';
		} else {
			$tID = 'id = "' . $this->_tableID . '" ';
		}
		$endSpan = '';

		$tableDivStyle= '';
		$tableTableStyle = '';
		$tableHeightStyle = '';
		//widths of table and tableDiv if specified.  Otherwise, uses tableClass settings in table.css
		if($this->_tableDivWidth != null){
			$tableDivStyle .= 'width:' . $this->_tableDivWidth . 'px;';
			$tableTableStyle .= 'width:' . $this->_tableWidth . 'px;';
		}

		if($this->_tableHeight != null){
			if($this->_stickyHeader){
				$tableHeightStyle .= 'height:' . $this->_tableHeight . 'px;';
			} else {
				$tableDivStyle .= 'height:' . $this->_tableHeight . 'px;';
			}
		}


		echo '<div ' . $tID . ' class="' . $this->_tableClass . '" style="' . $tableDivStyle . '" >';
		if($this->_tableTitle != null ){
			echo '<p class="tableTitle" >' . $this->_tableTitle . '</p>';
		}
		echo '<table cellspacing="0" style="' . $tableTableStyle . '" >';

		//print col headers
		self::printColHeader();

		//makes a separate table for contents and allows floating header.
		if($this->_stickyHeader){
			echo '</table>';
			echo '<span class="floatTableSpan"   style="' . $tableHeightStyle . '">';
			echo '<table cellspacing="0"  style="' . $tableTableStyle . '" >';
			$endSpan = '</span>';
		}
		echo '<tbody>';
		//print row data
		foreach ($this->_data as $dataRow){
			self::printRow($dataRow);
			$this->_rowCount++;
		}

		if($this->_displayEmpty &&  $this->_rowCount == 0){
			echo '<tr><td>' . $this->_displayMsg . '</td></tr>';
		}
		echo '</tbody>';
		//end table
		echo '</table>';
		echo $endSpan;
		echo '</div>';
			
	}


	private function printColHeader(){
		echo '<thead><tr>';
		foreach ($this->_colHeaders as $colHeader)
		{
			echo '<th ' . $colHeader['colHeadAttrAr']['id'] .$colHeader['colHeadAttrAr']['class']  .
			$colHeader['colHeadAttrAr']['style'] . ' >';

			//print link if exists
			if($colHeader['colHeadAttrAr']['link'] === null ){
				echo $colHeader['colName'];
			} else {
				echo '<a href="' . $colHeader['colHeadAttrAr']['link']  . '">' . $colHeader['colName'] . '</a>';
			}
			echo '</th>';
		}
		echo '</tr></thead>';
	}

	private function printRow($row){
		$rowClass = 'even';
		if($this->_tableStripe){
			if($this->_rowCount &1){
				$rowClass= 'odd';
			}
		}

		if(empty($this->_trIdField)){
			$trIdField = $this->_rowCount;
		} else {
			$trIdField = $row[$this->_trIdField];
		}

		echo '<tr id="' . $this->_trIdPrefix . $trIdField . '" class="' . $rowClass . '">';
		foreach ($this->_columns as $col)
		{
			$tagBegin = '';
			$tagEnd = '';
			if($col['dataField'] == ''){
				$cellContents= $col['colAttrAr']['altname'];
			} else {
				$cellContents = $row[$col['dataField']];
			}
			//add link if set
			if($col['colAttrAr']['link'] != null ){
				$paramString = '';

				foreach($col['colAttrAr']['link']['params'] as $param){
					$paramString .= $param[0] . '/' . $row[$param[1]] . '/';
				}

				$tagBegin = '<a href="' . $col['colAttrAr']['link']['url'] . '/' . $paramString . '">';
				$tabEnd = '</a>';
			}

			echo '<td '. $col['colAttrAr']['class']  . $col['colAttrAr']['style'] . ' >';


			if( $col['colAttrAr']['sprintf'] != null){
				$cellContents =  sprintf( $col['colAttrAr']['sprintf'] ,   $cellContents);
			}


			if($col['colAttrAr']['special'] != null){
				switch ($col['colAttrAr']['special']){
					case 'checkbox':  // enabled checkbox
						$checkVal = 0;
						$checked = '';
						if($cellContents == 1){
							$checkVal = 1;
							$checked = ' checked';
						}
						$cellContents = '<input type="checkbox" value="' . $checkVal .'" ' . $checked .'>';

						break;

					case 'bool':  // converts bool (0,1) to No, Yes
						$checkVal = 'No';
						if($cellContents == 1){
							$checkVal = 'Yes';
						}
						$cellContents = $checkVal;

						break;
					case 'date':  // cast to date
						if(date('m/d/Y', strtotime($cellContents))){
							$cellContents = date('m/d/Y', strtotime($cellContents));
						}
						break;
					case 'datetime':  // cast to datetime
						if(date('m/d/Y H:i', strtotime($cellContents))){
							$cellContents = date('m/d/Y  H:i', strtotime($cellContents));
						}
						break;
					default:

				}
			}


			echo $tagBegin;
			//print contents, look for printf
			if( $col['colAttrAr']['sprintf'] != null){
				echo sprintf( $col['colAttrAr']['sprintf'] ,   $cellContents);
					
			} else { echo $cellContents;
			}

			echo $tagEnd;
			echo '</td>';
		}
		echo '</tr>';
	}


	private function setTableAttrs($oldTableAttrs)
	{
		$newTableAttrs = array();

		if(array_key_exists('id', $oldTableAttrs)){
			$this->_tableID = $oldTableAttrs['id'];
		}

		if(array_key_exists('class', $oldTableAttrs)){
			$this->_tableClass = $oldTableAttrs['class'];
		}

		if(array_key_exists('width', $oldTableAttrs)){
			$this->_tableDivWidth = $oldTableAttrs['width'];
			$this->_tableWidth = $oldTableAttrs['width'] - $this->_tableDivWidthMinus;
		}

		if(array_key_exists('height', $oldTableAttrs)){
			$this->_tableHeight = $oldTableAttrs['height'];
		}

		if(array_key_exists('trIdPrefix', $oldTableAttrs)){
			$this->_trIdPrefix = $oldTableAttrs['trIdPrefix'];
		}

		return $newTableAttrs;
	}



	private function cleanColHeaderAttrs($oldColHeadAttrs)
	{
		$newColHeadAttrs = array();
		$newColHeadAttrs['style'] = '';

		if(array_key_exists('id', $oldColHeadAttrs)){
			$newColHeadAttrs['id'] = ' id = "' . $oldColHeadAttrs['id'] . '" ';
		} else {	$newColHeadAttrs['id'] = '';
		}

		if(array_key_exists('class', $oldColHeadAttrs)){
			$newColHeadAttrs['class'] = ' class = "' . $oldColHeadAttrs['class'] . '" ';
		} else {	$newColHeadAttrs['class'] = '';
		}
			

		if(array_key_exists('align', $oldColHeadAttrs)){
			if( $oldColHeadAttrs['align'] == 'L'){
				$newColHeadAttrs['style'] .= 'text-align:left;';
			}
			if( $oldColHeadAttrs['align'] == 'C'){
				$newColHeadAttrs['style'] .= 'text-align:center;';
			}
			if( $oldColHeadAttrs['align'] == 'R'){
				$newColHeadAttrs['style'] .= 'text-align:right;';
			}
		}

		if(array_key_exists('width', $oldColHeadAttrs)){
			$newColHeadAttrs['style'] .= 'width:' . $oldColHeadAttrs['width'] . 'px;';
		}

		if(array_key_exists('hidden', $oldColHeadAttrs)){
			if($oldColHeadAttrs['hidden']){
				$newColHeadAttrs['style'] .= 'display:none;';
			}
		}

		if(array_key_exists('link', $oldColHeadAttrs)){
			$newColHeadAttrs['link'] = $oldColHeadAttrs['link'];
		} else {	$newColHeadAttrs['link'] = null;
		}

		if($newColHeadAttrs['style'] != '') {
			$newColHeadAttrs['style'] = ' style="' . $newColHeadAttrs['style'] . '" ';
		}

		return $newColHeadAttrs;
	}

	private function cleanColAttrs($oldColAttrs)
	{
		$newColAttrs = array();
		$newColAttrs['style'] = '';

		if(array_key_exists('class', $oldColAttrs)){
			$newColAttrs['class'] = ' class = "' . $oldColAttrs['class'] . '" ';
		} else {	$newColAttrs['class'] = '';
		}
			

		if(array_key_exists('align', $oldColAttrs)){
			if( $oldColAttrs['align'] == 'L'){
				$newColAttrs['style'] .= 'text-align:left;';
			}
			if( $oldColAttrs['align'] == 'C'){
				$newColAttrs['style'] .= 'text-align:center;';
			}
			if( $oldColAttrs['align'] == 'R'){
				$newColAttrs['style'] .= 'text-align:right;';
			}
		}

		if(array_key_exists('width', $oldColAttrs)){
			$newColAttrs['style'] .= 'width:' . $oldColAttrs['width'] . 'px;';
		}

		if(array_key_exists('hidden', $oldColAttrs)){
			if($oldColAttrs['hidden']){
				$newColAttrs['style'] .= 'display:none;';
			}
		}

		//if links, find url and parse out all params
		if(array_key_exists('link', $oldColAttrs)){

			$newColAttrs['link']['url'] = $oldColAttrs['link'][0];
			$newColAttrs['link']['params'] = array();
			foreach($oldColAttrs['link'][1] as $key=> $value){
				$newColAttrs['link']['params'][] = array($key, $value);
			}

		} else { $newColAttrs['link'] = null;
		}


		if(array_key_exists('altname', $oldColAttrs)){
			$newColAttrs['altname'] = $oldColAttrs['altname'];
		} else {	$newColAttrs['altname'] = '';
		}

		if(array_key_exists('sprintf', $oldColAttrs)){
			$newColAttrs['sprintf'] = $oldColAttrs['sprintf'];
		} else { $newColAttrs['sprintf'] = null;
		}

		if($newColAttrs['style'] != '') {
			$newColAttrs['style'] = ' style="' . $newColAttrs['style'] . '" ';
		}

		if(array_key_exists('special', $oldColAttrs)){
			$newColAttrs['special'] = $oldColAttrs['special'];
		} else {	$newColAttrs['special'] = '';
		}
			
		return $newColAttrs;
	}


}