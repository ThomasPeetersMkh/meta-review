<?php

class StudentController {
    private $conn;

    public function __construct($conn) {
      $this->conn = $conn;
    }

    //returns all the students
    public function getStudents(){
      header("Content-Type: application/json; charset=UTF-8");
      $items = new Student($this->conn);
      $stmt = $items->readStudents();
      $itemCount = $stmt->rowCount();

      if ($itemCount > 0) {
        $studentArr = [];
        $studentArr["body"] = [];
        $studentArr["itemCount"] = $itemCount;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          $studentVak = [];
          $vakken = new Student($this->conn);
          $vakken->id = $llg_id;
          $courseArr = $vakken->getStudentCourses();
          foreach ($courseArr as $course){
            $studentVak[$course["vak_naam"]] = $course["llg_vak_score"];
          }
          $studentVak = (object) $studentVak;
          $e = [
            "llg_id" => $llg_id,
            "llg_studentnr" => $llg_studentnr,
            "llg_naam" => $llg_naam,
            "llg_voornaam" => $llg_voornaam,
            "llg_email"=>$llg_email,
            "llg_image_url"=>$llg_image_url,
            "vakken"=>$studentVak
          ];
          array_push($studentArr["body"], $e);
        }
        http_response_code(200);
        echo json_encode($studentArr);
      }
      else {
        http_response_code(404);
        echo json_encode(
          ["message" => "No courses found."]
        );
      }
    }
    //creates a new student in the database
    public function postStudents(){
      header("Content-Type: application/json; charset=UTF-8");
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
      $item = new Student($this->conn);
      $data = json_decode(file_get_contents("php://input"));
      if( !($data->llg_studentnr) ||!($data->llg_voornaam) || !($data->llg_naam) || !($data->llg_email) || !($data->llg_image_url)){
        http_response_code(400);
        echo json_encode(
          ["message" => "Body contains wrong keys."]
        );
        exit;
      }
      $item->studentId = $data->llg_studentnr;
      $item->firstName = $data->llg_voornaam;
      $item->lastName = $data->llg_naam;
      $item->email = $data->llg_email;
      $item->imgUrl = $data->llg_image_url;
      $item->courses = $data->vakken;
      if ($item->createStudent()) {
        http_response_code(201);
        echo json_encode(
          ["message" => "Student created successfully"]
        );
      }
      else {
        http_response_code(400);
        echo json_encode(
          ["message" => "Student could not be created."]
        );
      }
    }
    //returns one specific student based on the id in the url
    public function getOneStudent($id){
      header("Content-Type: application/json; charset=UTF-8");
      $item = new Student($this->conn);
      $item->id = $id;
      $item->getSingleStudent();
      $studentVak = [];
      $courseArr = $item->getStudentCourses();
      foreach ($courseArr as $course){
        $studentVak[$course["vak_naam"]] = $course["llg_vak_score"];
      }
      $studentVak = (object) $studentVak;
      if ($item->firstName != NULL) {
        // create array
        $emp_arr = [
          "llg_id" => $item->id,
          "llg_studentnr" => $item->studentId,
          "llg_naam" => $item->lastName,
          "llg_voornaam" => $item->firstName,
          "llg_email"=>$item->email,
          "llg_image_url"=>$item->imgUrl,
          "vakken"=>$studentVak
        ];
        http_response_code(200);
        echo json_encode($emp_arr);
      }

      else {
        http_response_code(404);
        echo json_encode(["message" => "Student not found."]);
      }
    }
    //Update the records of one specific student based on the id in the url, all fields are needed
    public function updateStudent($id){
      header("Content-Type: application/json; charset=UTF-8");
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

      $item = new Student($this->conn);

      $data = json_decode(file_get_contents("php://input"));

      $item->id = $id;

      // student values
      if(!($data->llg_studentnr) || !($data->llg_voornaam) || !($data->llg_naam) || !($data->llg_email)){
        http_response_code(400);
        echo json_encode(
          ["message" => "Body contains wrong keys."]
        );
        exit;
      }
      $item->studentId = $data->llg_studentnr;
      $item->firstName = $data->llg_voornaam;
      $item->lastName = $data->llg_naam;
      $item->email = $data->llg_email;
      $item->imgUrl = $data->llg_image_url;

      if ($item->updateStudent()) {
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
    //deletes a specific student based on the id in the url
    public function deleteStudent($id){
      header("Content-Type: application/json; charset=UTF-8");
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
      $item = new Student($this->conn);
      $item->id = $id;
      if ($item->deleteStudent()) {
        http_response_code(201);
        echo json_encode(["message" => "Student deleted"]);
      }
      else {
        http_response_code(400);
        echo json_encode(["message" => "Student could not be deleted"]);
      }
    }
}