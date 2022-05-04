<?php

class Student {

  // database connection and table name
  private $conn;

  private $db_table = "leerling";

  // object properties
  public $id;

  public $studentId;

  public $firstName;

  public $lastName;

  public $email;

  public $imgUrl;

  public $courses;

  // constructor with $db as database connection
  public function __construct($db) {
    $this->conn = $db;
  }

  // read students
  public function readStudents() {
    // select all query
    $query = "SELECT * from " . $this->db_table;
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
  }

  // CREATE
  public function createStudent() {
    //prepare query for leerling table
    $sqlQuery = "INSERT INTO " . $this->db_table . " SET llg_studentnr = :studentId, llg_voornaam = :firstName, llg_naam = :lastName, llg_email = :email, llg_image_url = :imgUrl";
    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->studentId = htmlspecialchars(strip_tags($this->studentId));
    $this->firstName = htmlspecialchars(strip_tags($this->firstName));
    $this->lastName = htmlspecialchars(strip_tags($this->lastName));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->imgUrl = htmlspecialchars(strip_tags($this->imgUrl));

    // bind data
    $stmt->bindParam(":studentId", $this->studentId);
    $stmt->bindParam(":firstName", $this->firstName);
    $stmt->bindParam(":lastName", $this->lastName);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":imgUrl", $this->imgUrl);

    if ($stmt->execute()) {
      $this->id =$this->conn->lastInsertId();
      foreach ($this->courses as $course){
        $courseId = $course;
        $sqlQueryCourse = "INSERT INTO `leerling-vak` SET llg_id = :id, vak_id = :courseId";
        $stmtCourse = $this->conn->prepare($sqlQueryCourse);

        // sanitize
        $this->id =  htmlspecialchars(strip_tags($this->id));
        $courseId =  htmlspecialchars(strip_tags($courseId));

        $stmtCourse->bindParam(":id", $this->id);
        $stmtCourse->bindParam(":courseId", $courseId);
        $stmtCourse->execute();
      }
      return TRUE;
    }
    return FALSE;
  }

  // READ single
  public function getSingleStudent() {
    $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE llg_id = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $dataRow['llg_id'];
    $this->studentId = $dataRow['llg_studentnr'];
    $this->firstName = $dataRow['llg_voornaam'];
    $this->lastName = $dataRow['llg_naam'];
    $this->email = $dataRow["llg_email"];
    $this->imgUrl = $dataRow["llg_image_url"];
  }

  // UPDATE
  public function updateStudent() {
    $sqlQuery = "UPDATE " . $this->db_table . " SET llg_studentnr = :studentId llg_voornaam = :firstName, llg_naam = :lastName, llg_email = :email, llg_image_url = :imgUrl WHERE llg_id = :id";
    $stmt = $this->conn->prepare($sqlQuery);

    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->studentId = htmlspecialchars(strip_tags($this->studentId));
    $this->firstName = htmlspecialchars(strip_tags($this->firstName));
    $this->lastName = htmlspecialchars(strip_tags($this->lastName));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->imgUrl = htmlspecialchars(strip_tags($this->imgUrl));

    // bind data
    $stmt->bindParam(":studentId", $this->studentId);
    $stmt->bindParam(":firstName", $this->firstName);
    $stmt->bindParam(":lastName", $this->lastName);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":imgUrl", $this->imgUrl);
    $stmt->bindParam(":id", $this->id);

    if ($stmt->execute()) {
      return TRUE;
    }
    return FALSE;
  }

  // DELETE
  public function deleteStudent() {
    $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE llg_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);

    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(1, $this->id);
    if ($stmt->execute()) {
      return TRUE;
    }
    return FALSE;
  }

  //assignCourses
  public function getStudentCourses(){
    $sqlQuery = "SELECT vak_naam, llg_vak_score from ". $this->db_table ." inner join `leerling-vak` `l-v` on leerling.llg_id = `l-v`.llg_id inner join vak v on `l-v`.vak_id = v.vak_id where leerling.llg_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $vakken = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $vakken;
  }
}