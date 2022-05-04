<?php

class ScoreController {
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }
  //Update the records of one specific student based on the id in the url, all fields are needed
  public function updateScore(){
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $item = new Score($this->conn);

    $data = json_decode(file_get_contents("php://input"));

    // student values
    if(!($data->llg_id) || !($data->vak_id) || !($data->llg_vak_score)){
      http_response_code(400);
      echo json_encode(
        ["message" => "Body contains wrong keys."]
      );
      exit;
    }
    $item->studentId = $data->llg_id;
    $item->courseId = $data->vak_id;
    $item->score = $data->llg_vak_score;

    if ($item->updateScore()) {
      http_response_code(201);
      echo json_encode(
        ["message" => "Student's data updated."]
      );
    }
    else {
      http_response_code(400);
      echo json_encode(
        ["message" => "Data could not be updated"]
      );
    }
  }
}