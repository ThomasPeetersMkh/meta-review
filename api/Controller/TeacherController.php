<?php

class TeacherController {
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function getTeachers(){
    header("Content-Type: application/json; charset=UTF-8");
    $items = new Teacher($this->conn);
    $stmt = $items->readTeachers();
    $itemCount = $stmt->rowCount();

    if ($itemCount > 0) {
      $teacherArr = [];
      $teacherArr["body"] = [];
      $teacherArr["itemCount"] = $itemCount;
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $teacherVak = [];
        $vakken = new Teacher($this->conn);
        $vakken->id = $lkt_id;
        $courseArr = $vakken->getTeacherCourses();
        foreach ($courseArr as $course){
          $teacherVak[] = $course["vak_naam"];
        }
        //$teacherVak = (object) $teacherVak;
        $e = [
          "lkt_id" => $lkt_id,
          "lkt_naam" => $lkt_naam,
          "lkt_voornaam" => $lkt_voornaam,
          "lkt_emailadres" => $lkt_emailadres,
          "lkt_image_url"=>$lkt_image_url,
          "vakken"=>$teacherVak
        ];
        array_push($teacherArr["body"], $e);
      }
      http_response_code(200);
      echo json_encode($teacherArr);
    }
    else {
      http_response_code(404);
      echo json_encode(
        ["message" => "No teachers found."]
      );
    }
  }
  public function getOneTeacher($id){
    header("Content-Type: application/json; charset=UTF-8");
    $item = new Teacher($this->conn);
    $item->id = $id;
    $item->getSingleTeacher();
    $teacherVak = [];
    $courseArr = $item->getTeacherCourses();
    foreach ($courseArr as $course){
      $teacherVak[] = $course["vak_naam"];
    }
    //$teacherVak = (object) $teacherVak;
    if ($item->firstName != NULL) {
      // create array
      $emp_arr = [
        "lkt_id" => $item->id,
        "lkt_naam" => $item->lastName,
        "lkt_voornaam" => $item->firstName,
        "lkt_emailadres" => $item->email,
        "lkt_image_url"=>$item->imgUrl,
        "vakken"=>$teacherVak
      ];
      http_response_code(200);
      echo json_encode($emp_arr);
    }

    else {
      http_response_code(404);
      echo json_encode(["message" => "Teacher not found."]);
    }
  }
  public function getTeacherLogin(){
    header("Content-Type: application/json; charset=UTF-8");
    $item = new Teacher($this->conn);
    $data = json_decode(file_get_contents("php://input"));
    $item->email = $data->lkt_emailadres;
    $item->password = $data->lkt_paswoord;
    if($item->loginTeacher()){
      $teacherVak = [];
      $courseArr = $item->getTeacherCourses();
      foreach ($courseArr as $course){
        $teacherVak[] = $course["vak_naam"];
      }
      //$teacherVak = (object) $teacherVak;
      if ($item->firstName != NULL) {
        // create array
        $emp_arr = [
          "lkt_id" => $item->id,
          "lkt_naam" => $item->lastName,
          "lkt_voornaam" => $item->firstName,
          "lkt_emailadres" => $item->email,
          "lkt_image_url"=>$item->imgUrl,
          "vakken"=>$teacherVak
        ];
        http_response_code(200);
        echo json_encode($emp_arr);
      }
    }
    else {
      http_response_code(404);
      echo json_encode(["message" => "Something went wrong."]);
    }
  }
}