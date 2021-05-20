<?php
require_once('RolesModel.php');
require_once('EstablishmentsModel.php');
class UsersModel extends Mysql
{
  public $id;
  public $inId;
  public $document;
  public $user;
  public $email;
  public $password;
  public $phone;
  public $role_id;
  public $establishment_id;
  public $status;
  public $date;

  public function __construct()
  {
    parent::__construct();

    $this->Role = new RolesModel;
    $this->Establishment = new EstablishmentsModel;
  }

  public function selectUsers(int $perPage, array $filter)
  {


    $this->id = $filter['id'];
    $this->document = $filter['document'];
    $this->user = $filter['name'];
    $this->role_id = $filter['role_id'];
    $this->establishment_id = $filter['establishment_id'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;

    $date_range = "";
    if ($this->date != '') {
      if (count($this->date) == 2) {
        $date_range = ' AND u.created_at BETWEEN "' . $this->date[0] . ' 00:00:00" AND "' . $this->date[1] . ' 23:59:59" OR u.created_at BETWEEN "' . $this->date[1] . ' 00:00:00" AND "' . $this->date[0] . ' 23:59:59"';
      }
    }
    $whereAdmin = "";
    if (!$_SESSION['userData']['user_root']) {
      $whereAdmin = "AND u.user_root != 1";
    }
    $where = '
      u.id LIKE "%' . $this->id . '%" AND
      u.document LIKE "%' . $this->document . '%" AND
      (u.name LIKE "%' . $this->user . '%" OR u.last_name LIKE "%' . $this->user . '%") AND
      u.role_id LIKE "%' . $this->role_id . '%" AND 
      u.establishment_id LIKE "%' . $this->establishment_id . '%" AND
      u.status LIKE "%' . $this->status . '%" AND 
      u.status > 0 AND 
      e.company_id = "' . $_SESSION['userData']['establishment']['company_id'] . '"
      ' . $date_range . $whereAdmin . '
    ';

    $info = "SELECT COUNT(*) as count 
      FROM users u
      INNER JOIN establishments e
      ON u.establishment_id = e.id
      WHERE $where 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *, u.id as id 
      FROM users u
      INNER JOIN establishments e
      ON u.establishment_id = e.id
      WHERE $where  
      ORDER BY u.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all($rows);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['role'] = $this->Role->selectRol($items[$i]['role_id']);
      $items[$i]['establishment'] = $this->Establishment->selectEstablishment($items[$i]['establishment_id']);
    }

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectUser(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM users WHERE id = $this->id";
    $request = $this->select($sql);
    $shortName = explode(" ", $request['name']);
    $shortLastName = explode(" ", $request['last_name']);
    $request['short_name'] = $shortName[0] . ' ' . $shortLastName[0];
    $request['role'] = $this->Role->selectRol($request['role_id']);
    $request['establishment'] = $this->Establishment->selectEstablishment($request['establishment_id']);
    return $request;
  }

  public function insertUser(
    string $name,
    string $last_name,
    string $document,
    string $email,
    string $passwordHash,
    string $phone,
    int $role_id,
    int $establishment_id,
    int $status
  ) {
    $return = "";
    $this->name = $name;
    $this->last_name = $last_name;
    $this->document = $document;
    $this->email = $email;
    $this->password = $passwordHash;
    $this->phone = $phone;
    $this->role_id = $role_id;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $sql = "SELECT * FROM users WHERE name = '$this->name' OR document = '$this->document' OR email = '$this->email'";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $query_insert = "INSERT INTO users (name, last_name, document, email, password, phone, role_id, establishment_id, status) VALUES (?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->name,
        $this->last_name,
        $this->document,
        $this->email,
        $this->password,
        $this->phone,
        $this->role_id,
        $this->establishment_id,
        $this->status
      );
      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = 0;
    }
    return $return;
  }

  public function updateUser(
    int $id,
    string $name,
    string $last_name,
    string $document,
    string $email,
    string $passwordHash,
    string $phone,
    int $role_id,
    int $establishment_id,
    int $status
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->last_name = $last_name;
    $this->document = $document;
    $this->email = $email;
    $this->password = $passwordHash;
    $this->phone = $phone;
    $this->role_id = $role_id;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $sql = "SELECT * FROM users WHERE id != '$this->id' AND (name = '$this->name' OR document = '$this->document' OR email = '$this->email')";
    $request = $this->select_all($sql);

    if (empty($request)) {
      if ($this->password !== "") {
        $sql = "UPDATE users SET name = ?, last_name = ?, document = ?, email = ?, password = ?, phone = ?, role_id = ?, establishment_id = ?, status = ? WHERE id = $this->id";
        $arrData = array(
          $this->name,
          $this->last_name,
          $this->document,
          $this->email,
          $this->password,
          $this->phone,
          $this->role_id,
          $this->establishment_id,
          $this->status
        );
      } else {
        $sql = "UPDATE users SET name = ?, last_name = ?, document = ?, email = ?, phone = ?, role_id = ?, establishment_id = ?, status = ? WHERE id = $this->id";
        $arrData = array(
          $this->name,
          $this->last_name,
          $this->document,
          $this->email,
          $this->phone,
          $this->role_id,
          $this->establishment_id,
          $this->status
        );
      }

      $request = $this->update($sql, $arrData);
    } else {
      $request = 0;
    }

    return $request;
  }

  public function deleteUser(int $id)
  {
    $this->id = $id;

    $sql = "UPDATE users SET status = 0 WHERE id = $this->id";
    $request = $this->delete($sql);

    if ($request) {
      $request = 1;
    } else {
      $request = 0;
    }
    return $request;
  }
  public function updateDarkMode($val)
  {
    $sql = 'UPDATE users SET 
      dark_mode = "' . $val . '"
      WHERE id = ' . $_SESSION['userData']['id'];
    $request = $this->delete($sql);

    if ($request) {
      $request = 1;
    } else {
      $request = 0;
    }
    return $request;
  }
}
