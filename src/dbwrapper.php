<?php




class db
{
  public $connection;
  public $query;
  public $sql;

  public function __construct()
  {
    $this->connection = mysqli_connect("localhost", "root", "", "cms");
  }





  // first: we will make the select in the database rubber

  public function select($table, $column)
  {
    $this->sql = "SELECT $column FROM `$table`";
    return $this;
  }

  public function where($column, $compair, $value)
  {
    $this->sql .= " WHERE $column $compair '$value'";
    // echo $this->sql;die;    // we use this line for debugging
    return $this;
  }
  public function andWhere($column, $compair, $value)
  {
    $this->sql .= " AND `$column` $compair '$value'";
    // echo $this->sql;die;    
    return $this;
  }
  public function orWhere($column, $compair, $value)
  {
    $this->sql .= " OR `$column` $compair '$value'";
    // echo $this->sql;die;    
    return $this;
  }

  public function join($tablename, $first, $second)
  {
    $this->sql .= " INNER JOIN $tablename ON $first = $second";
    return $this;
  }


  public function getAll()
  {
    $this->query();
    // echo $this->sql;die;    
    while ($row = mysqli_fetch_assoc($this->query)) {
      $data[] = $row;
    }
    return $data;
  }

  public function getRow()
  {
    // echo $this->sql;die;    
    $this->query();
    $row = mysqli_fetch_assoc($this->query);
    return $row;
  }















  // second: we will make the insert in the database rubber

  public function insert($table, $data)
  {
    $row = $this->prepareData($data);

    $this->sql = "INSERT INTO `$table` SET $row";
    // echo $this->sql;die;    

    return $this;
  }









  //third: we will make the update in the database rubber

  public function update($table, $data)
  {
    $row = $this->prepareData($data);
    $this->sql = "UPDATE `$table` SET $row";
    // echo $this->sql;die;
    return $this;
  }









  //fourth: we will make the update in the database rubber


  public function delete($table)
  {
    $this->sql = "DELETE FROM `$table`";
    return $this;
  }

  public function excu()
  {
    try {
      $this->query();

      if (mysqli_affected_rows($this->connection) > 0) {
        return true;
      }
    } catch (Exception $ex) {
      // echo $ex->getMessage();
      return $this->showError();
    }
  }


  public function prepareData($data)
  {

    // echo "<pre>";
    // print_r($data);
    // die;
    // echo "</pre>";

    $row = "";
    foreach ($data as $key => $value) {
      $row .= " `$key` = " . ((gettype($value) == 'string') ? "'$value'" : "$value") . ",";
    }
    $row = rtrim($row, ",");
    // echo $row;die;
    return  $row;
  }


  public function query()
  {
    // echo $this->sql;die;
    $this->query = mysqli_query($this->connection, $this->sql);
    // $connection->query("$this->sql");   
  }

  public function showError()
  {
    // return mysqli_error($this->connection);
    $errors = mysqli_error_list($this->connection);
    // print_r($errors);
    foreach ($errors as $error) {
      echo "<h2 style='color:red'>Error :</h2>" . $error['error'] . "<br> <h3 style='color:red'>Error Code : </h3>" . $error['errno'];
    }
  }

  public function __destruct()
  {
    mysqli_close($this->connection);
  }
}
