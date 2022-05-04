<?php

class Teacher {
  // database connection and table name
  private $conn;
  private $db_table = "leerkracht";

  //variables
  public $id;


  public $firstName;

  public $lastName;

  public $email;

  public $password;

  public $imgUrl;

  public $courses;

  public function __construct($db) {
    $this->conn = $db;
  }
  //get all teachers
  public function readTeachers() {
    // select all query
    $query = "SELECT * from " . $this->db_table;
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
  }

  public function loginTeacher(){
    $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE lkt_emailadres = '".$this->email."'";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(":email", $this->email);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if(password_verify($this->password,$dataRow["lkt_paswoord"])){
      $this->id = $dataRow['lkt_id'];
      $this->firstName = $dataRow['lkt_voornaam'];
      $this->lastName = $dataRow['lkt_naam'];
      $this->email = $dataRow["lkt_emailadres"];
      $this->imgUrl = $dataRow["lkt_image_url"];
      return TRUE;
    }else{
      return FALSE;
    }
  }

  //get single teacher
  public function getSingleTeacher() {
    $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE lkt_id = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $dataRow['lkt_id'];
    $this->firstName = $dataRow['lkt_voornaam'];
    $this->lastName = $dataRow['lkt_naam'];
    $this->email = $dataRow["lkt_emailadres"];
    $this->password = $dataRow["lkt_paswoord"];
    $this->imgUrl = $dataRow["lkt_image_url"];
  }

  //used to display courses related to specific teacher
  public function getTeacherCourses(){
    $sqlQuery = "SELECT vak_naam from ". $this->db_table ." inner join vak v on leerkracht.lkt_id = v.vak_lkt_id where lkt_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $vakken = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $vakken;
  }
}