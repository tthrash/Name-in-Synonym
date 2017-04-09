<?PHP 
  require_once("db_configuration.php");
  require_once("PHPExcel/PHPExcel.php");


/**
 * Class represents at table and include functionality to export the 
 * table to an excel spreadsheet
 */
  class Table {
    /**
     * Constructor for Table class the initializes parameters
     * @param string $tableName of table you want to want to export from database
     * @param array  $fields    specify the exact fields you want to export else exports all
     * @param string $excelName of file you want to export to
     * @param integer $sheetIndex of excel file you are exporting to
     */
    function  Table ($tableName, $fields, $excelName, $sheetIndex) {
      $this->tableName = $tableName;
      if (is_array($fields)) {
        $this->fields = $fields;
        $this->numFields = count($fields);
      } else {
        $this->numFields = 0;
      }
      $this->excelName = $excelName;
      $this->sheetIndex = $sheetIndex;
      $this->objPHPExcel = new PHPEXcel();
      $this->objPHPExcel->setActiveSheetIndex($sheetIndex); 
      $this->abcs = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
    }
    
    /**
     * Generates export sql statement for a table
     */
    function exportSqlStatement() {
      $sql = '';
      if ($this->numFields === 0) {
         $sql = 'SELECT * FROM ' . $this->tableName;
      }
      else {
        $sql = 'SELECT ';
        $array = $this->fields;
        for($i = 0; $i < $this->numFields; $i++) {
          if ($i == $this->numFields-1) {
            $sql .= $array[$i] . ' ';
            break; 
          }
          $sql .= $array[$i] . ', ';
        }
        $sql .= 'FROM ' .$this->tableName;
      }
      $this->exportSql = $sql;
    }
    
    /**
     * Exports table to an excel spreadsheet
     */
    /* FIXME: function needs to be debuged */
    function exportDB() {
      $db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
      $result = $db->query($this->exportSql);
      $num_rows = $result->num_rows;
      $abcs = $this->abcs;
      $objPHPExcel = $this->objPHPExcel;
      $rowIndex = 0;
      
      if ($num_rows > 0) { /* table has elements */
        while($row = $result->fetch_assoc()) {
          $rowIndex++;
          /* NOTE: Not sure if this does anything but we had it on our export for word explorer */
          header("Content-Type: application/vnd.ms-excel; charset=utf-8");
          header("Content-Disposition: attachment; filename=word_list.xlsx");
          /*-------------------------------------------------------------------------------------*/
          $count = count($row);
          for($j = 0; $j < $count; $j++) {
            $objPHPExcel->getActiveSheet()->SetCellValue($abcs . $rowIndex, $row[ '\'' . $j . '\'']);
          }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
      	$objWriter->save($this->excelName);
      }
    }
    
  }
  

  /**
   * Creates instance of table class for all tables to be exported
   * and exports them
   */
  function exportAllTables() { // TODO: need to create the rest of the tables
    /* eg. $userTable = new Table("User", array("username", "isVerified", "password"), 'php://output', 0);
     *  $userTable->exportSqlStatement();
     *  $userTable->exportDB();
     */
    $userTable = new Table("user", array("username", "user_email", "user_password", "id_verified", "activation_token", "role"), 'php://output', 0);
    $userTable->exportSqlStatement();
    $userTable->exportDB();
  }
  
 
?>
