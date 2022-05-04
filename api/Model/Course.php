<?php

class Course {
  // database connection and table name
  private $conn;
  private $db_table = "vak";

  //variables
  public $id;

  public $name;

  public $description;

  public $teacherFirstName;

  public $teacherlastName;

  public function __construct($db) {
    $this->conn = $db;
  }

  //Get all courses
  public function readCourses() {
    // select all query
    $query = "SELECT vak_id, vak_naam, vak_omschr, lkt_naam, lkt_voornaam from " . $this->db_table . " inner join leerkracht l on vak.vak_lkt_id = l.lkt_id";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
  }

  //Get a single course
  public function getSingleCourse(){
    $sqlQuery = "SELECT vak_id, vak_naam, vak_omschr, lkt_naam, lkt_voornaam FROM " . $this->db_table . " inner join leerkracht l on vak.vak_lkt_id = l.lkt_id WHERE vak_id = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $dataRow['vak_id'];
    $this->name = $dataRow['vak_naam'];
    $this->description = $dataRow['vak_omschr'];
    $this->teacherFirstName = $dataRow["lkt_naam"];
    $this->teacherlastName = $dataRow["lkt_voornaam"];
  }

  //Get students related to a course
  public function getCourseStudents() {
    // select all query
    $query = "select llg_studentnr, llg_naam,llg_voornaam,llg_vak_score  from vak
            inner join `leerling-vak` `l-v` on vak.vak_id = `l-v`.vak_id
            inner join leerling l on `l-v`.llg_id = l.llg_id
            where vak_naam like '".$this->name."'";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
  }
}