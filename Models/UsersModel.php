<?php
require_once('RolesModel.php');
require_once('EstablishmentsModel.php');
require_once('SystemModel.php');

class UsersModel extends Mysql
{
  public $id;
  public $idUser;
  public $document;
  public $user;
  public $email;
  public $password;
  public $phone;
  public $role_id;
  public $establishment_id;
  public $company_id;
  public $cost_access;
  public $pvp_manual;
  public $discount_manual;
  public $status;
  public $date;
  public $note;
  public $color;
  public $issued_by;
  public $description;
  public $url;
  public $expiration_date;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Role = new RolesModel;
    $this->Establishment = new EstablishmentsModel;
    $this->System = new SystemModel;
  }

  public function selectUsers(int $perPage, array $filter)
  {


    $user_access = ($_SESSION['userData']['user_access'] == 2) ?
      " AND u.establishment_id = '{$_SESSION['userData']['establishment_id']}'"
      : "";

    $this->id = $filter['id'];
    $this->document = $filter['document'];
    $this->user = $filter['name'];
    $this->role_id = $filter['role_id'];
    $this->establishment_id = $filter['establishment_id'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;
    $this->company_id = $_SESSION['userData']['establishment']['company_id'];

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND u.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    //BLOQUEO DE VISTA USUARIOS ROOT
    $whereAdmin = (!$_SESSION['userData']['user_root']) ? " AND u.user_root != 1" : "";

    $where = "
      u.id LIKE '%$this->id%' AND
      u.document LIKE '%$this->document%' AND
      (u.name LIKE '%$this->user%' OR u.last_name LIKE '%$this->user%') AND
      u.role_id LIKE '%$this->role_id%' AND 
      u.establishment_id LIKE '%$this->establishment_id%' AND
      u.status LIKE '%$this->status%' AND 
      u.status > 0 AND 
      e.company_id = $this->company_id
      $user_access
      $date_range 
      $whereAdmin
    ";

    $info = "SELECT COUNT(*) as count 
      FROM users u
      INNER JOIN establishments e
      ON u.establishment_id = e.id
      WHERE $where 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *, u.id as id, u.phone as phone, u.created_at as created_at, u.status as status
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
      $items[$i]['gender'] = $this->selectGender($items[$i]['gender_id']);
    }

    return [
      'items' => $items,
      'dates' => $this->select_dates('created_at', 'users'),
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
    $request['gender'] = $this->selectGender($request['gender_id']);
    return $request;
  }

  public function insertUser(
    string $name,
    string $last_name,
    string $document,
    string $email,
    string $passwordHash,
    string $phone,
    int $gender_id,
    string $date_of_birth,
    int $role_id,
    int $insurance,
    int $establishment_id,
    int $user_access,
    int $access_boxes,
    int $create_notifications_users,
    int $cost_access,
    int $pvp_manual,
    int $discount_manual,
    int $status
  ) {
    $return = "";
    $this->name = $name;
    $this->last_name = $last_name;
    $this->document = $document;
    $this->email = $email;
    $this->password = $passwordHash;
    $this->phone = $phone;
    $this->gender_id = $gender_id;
    $this->date_of_birth = $date_of_birth;
    $this->role_id = $role_id;
    $this->insurance = $insurance;
    $this->establishment_id = $establishment_id;
    $this->user_access = $user_access;
    $this->access_boxes = $access_boxes;
    $this->create_notifications_users = $create_notifications_users;
    $this->cost_access = $cost_access;
    $this->pvp_manual = $pvp_manual;
    $this->discount_manual = $discount_manual;
    $this->status = $status;

    $sql = "SELECT * FROM users WHERE name = '$this->name' OR document = '$this->document' OR email = '$this->email'";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $query_insert = "INSERT INTO users (name, last_name, document, email, password, phone, gender_id, date_of_birth, role_id, establishment_id, insurance, create_notifications_users, user_access, access_boxes, cost_access, pvp_manual, discount_manual, status) 
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->name,
        $this->last_name,
        $this->document,
        $this->email,
        $this->password,
        $this->phone,
        $this->gender_id,
        $this->date_of_birth,
        $this->role_id,
        $this->establishment_id,
        $this->insurance,
        $this->create_notifications_users,
        $this->user_access,
        $this->access_boxes,
        $this->cost_access,
        $this->pvp_manual,
        $this->discount_manual,
        $this->status
      );
      $return = $this->insert($query_insert, $arrData);
    } else {
      $return = -2;
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
    int $gender_id,
    string $date_of_birth,
    int $role_id,
    int $insurance,
    int $establishment_id,
    int $user_access,
    int $access_boxes,
    int $create_notifications_users,
    int $cost_access,
    int $pvp_manual,
    int $discount_manual,
    int $status
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->last_name = $last_name;
    $this->document = $document;
    $this->email = $email;
    $this->password = $passwordHash;
    $this->phone = $phone;
    $this->gender_id = $gender_id;
    $this->date_of_birth = $date_of_birth;
    $this->role_id = $role_id;
    $this->insurance = $insurance;
    $this->establishment_id = $establishment_id;
    $this->user_access = $user_access;
    $this->access_boxes = $access_boxes;
    $this->create_notifications_users = $create_notifications_users;
    $this->cost_access = $cost_access;
    $this->pvp_manual = $pvp_manual;
    $this->discount_manual = $discount_manual;
    $this->status = $status;

    $sql = "SELECT * FROM users WHERE id != $this->id AND (name = '$this->name' OR document = '$this->document' OR email = '$this->email')";
    $request = $this->select_all($sql);

    if (empty($request)) {
      if ($this->password !== "") {
        $sql = "UPDATE users SET name = ?, last_name = ?, document = ?, email = ?, password = ?, phone = ?, gender_id = ?, date_of_birth = ?, role_id = ?, establishment_id = ?, insurance = ?, create_notifications_users = ?, user_access = ?, access_boxes = ?, cost_access = ?, pvp_manual = ?, discount_manual = ?, status = ? WHERE id = $this->id";
        $arrData = array(
          $this->name,
          $this->last_name,
          $this->document,
          $this->email,
          $this->password,
          $this->phone,
          $this->gender_id,
          $this->date_of_birth,
          $this->role_id,
          $this->establishment_id,
          $this->insurance,
          $this->create_notifications_users,
          $this->user_access,
          $this->access_boxes,
          $this->cost_access,
          $this->pvp_manual,
          $this->discount_manual,
          $this->status
        );
      } else {
        $sql = "UPDATE users SET name = ?, last_name = ?, document = ?, email = ?, phone = ?, gender_id = ?, date_of_birth = ?, role_id = ?, establishment_id = ?, insurance = ?, create_notifications_users = ?, user_access = ?, access_boxes = ?, cost_access = ?, pvp_manual = ?, discount_manual = ?, status = ? WHERE id = $this->id";
        $arrData = array(
          $this->name,
          $this->last_name,
          $this->document,
          $this->email,
          $this->phone,
          $this->gender_id,
          $this->date_of_birth,
          $this->role_id,
          $this->establishment_id,
          $this->insurance,
          $this->create_notifications_users,
          $this->user_access,
          $this->access_boxes,
          $this->cost_access,
          $this->pvp_manual,
          $this->discount_manual,
          $this->status
        );
      }

      $request = $this->update($sql, $arrData);
    } else {
      $request = -2;
    }

    return $request;
  }

  public function deleteUser(int $id)
  {
    $this->id = $id;

    $sql = "UPDATE users SET status = 0 WHERE id = $this->id";
    $request = $this->delete($sql);

    return $request;
  }

  public function updateDarkMode($val)
  {
    $sql = "UPDATE users SET 
      dark_mode = $val
      WHERE id = " . $_SESSION['userData']['id'];
    $request = $this->delete($sql);

    return $request;
  }

  public function selectGender(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM genders WHERE id = $this->id";
    $request = $this->select($sql);

    return $request;
  }

  public function selectPayRoll(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM payroll WHERE user_id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    return $request;
  }

  public function selectPayRecord(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "
      SELECT * FROM payments_records 
      WHERE id = $this->id
    ";
    $request = $this->select_company($sql, $this->db_company);

    $request['payment_method'] = $this->System->selectPaymentMethod($request['payment_method_id']);
    return $request;
  }

  public function selectPayRecords(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND amount_date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $info = "SELECT COUNT(*) as count FROM payments_records 
      WHERE user_id = $this->id $date_range";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT * FROM payments_records 
      WHERE user_id = $this->id $date_range 
      ORDER BY id DESC LIMIT 0, $this->perPage";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['payment_method'] = $this->System->selectPaymentMethod($items[$i]['payment_method_id']);
    }

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectMyNote(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "
      SELECT * FROM notes 
      WHERE id = $this->id AND status = 1
    ";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function selectMyNotes(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $info = "SELECT COUNT(*) as count FROM notes 
      WHERE user_id = $this->id AND status = 1";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT * FROM notes 
      WHERE user_id = $this->id AND status = 1
      ORDER BY id DESC";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function insertNote(
    int $idUser,
    string $name,
    string $note,
    string $color,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $return = "";
    $this->id = $idUser;
    $this->name = $name;
    $this->note = $note;
    $this->color = $color;

    $query_insert = "INSERT INTO notes (user_id, name, note, color) VALUES (?,?,?,?)";
    $arrData = array(
      $this->id,
      $this->name,
      $this->note,
      $this->color
    );
    $request_insert = $this->insert_company($query_insert, $arrData, $this->db_company);
    $return = $request_insert;

    return $return;
  }

  public function updateNote(
    int $id,
    int $idUser,
    string $name,
    string $note,
    string $color,
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->idUser = $idUser;
    $this->name = $name;
    $this->note = $note;
    $this->color = $color;

    $sql = "UPDATE notes SET name = ?, note = ?, color = ? WHERE id = $this->id AND user_id = $this->idUser";
    $arrData = array(
      $this->name,
      $this->note,
      $this->color
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function deleteNote(int $id, int $idUser)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->idUser = $idUser;

    $sql = "UPDATE notes SET status = 0 WHERE id = $this->id AND user_id = $this->idUser";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function selectNotification(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "
      SELECT * FROM notifications 
      WHERE id = $this->id AND status = 1
    ";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function selectNotifications(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $info = "SELECT COUNT(*) as count FROM notifications 
      WHERE (user_id = $this->id OR issued_by = $this->id) AND status = 1";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT * FROM notifications 
      WHERE (user_id = $this->id OR issued_by = $this->id) AND status = 1
      ORDER BY id DESC";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $issued_by = intval($items[$i]['issued_by']);
      $items[$i]['issued_by'] = $issued_by == 0 ?
        array('id' => 0, 'name' => $items[$i]['issued_by']) :
        array(
          'id' => $this->selectUser($issued_by)['id'],
          'name' => $this->selectUser($issued_by)['short_name']
        );
      $items[$i]['userData'] = $this->selectUser($items[$i]['user_id'])['short_name'];
    }

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function insertNotification(
    int $idUser,
    string $issued_by,
    string $name,
    string $description,
    string $url,
    string $icon,
    string $expiration_date
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $return = "";
    $this->idUser = $idUser;
    $this->issued_by = $issued_by;
    $this->name = $name;
    $this->description = $description;
    $this->url = $url;
    $this->icon = $icon;
    $this->expiration_date = $expiration_date;

    $query_insert = "INSERT INTO notifications (user_id, issued_by, name, description, url, icon, expiration_date) VALUES (?,?,?,?,?,?,?)";
    $arrData = array(
      $this->idUser,
      $this->issued_by,
      $this->name,
      $this->description,
      $this->url,
      $this->icon,
      $this->expiration_date,
    );
    $request_insert = $this->insert_company($query_insert, $arrData, $this->db_company);
    $return = $request_insert;

    return $return;
  }

  public function deleteNotification(int $id, int $idUser)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->idUser = $idUser;

    $sql = "UPDATE notifications SET status = 0 WHERE id = $this->id AND issued_by = $this->idUser";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
