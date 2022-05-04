<?php

class Score {

  // database connection and table name
  private $conn;

  private $db_table = "`leerling-vak`";

  // variables
  public $studentId;

  public $courseId;

  public $score;

  // constructor with $db as database connection
  public function __construct($db) {
    $this->conn = $db;
  }

  // UPDATE
  public function updateScore() {
    $sqlQuery = "UPDATE " . $this->db_table . " SET llg_vak_score = :score WHERE llg_id = :studentId and vak_id = :courseId";
    $stmt = $this->conn->prepare($sqlQuery);

    $this->studentId = htmlspecialchars(strip_tags($this->studentId));
    $this->firstName = htmlspecialchars(strip_tags($this->courseId));
    $this->lastName = htmlspecialchars(strip_tags($this->score));

    // bind data
    $stmt->bindParam(":score", $this->score);
    $stmt->bindParam(":studentId", $this->studentId);
    $stmt->bindParam(":courseId", $this->courseId);

    if ($stmt->execute()) {
      return TRUE;
    }
    return FALSE;
  }
}