<?php

$metodo = $_SERVER['REQUEST_METHOD'];

$servername = "172.17.0.1:3321";
$user = "root";
$pass = "gio";
$db = "mydb";


$conn = mysqli_connect($servername, $user, $pass, $db) or die("Connessione non riuscita" . mysqli_connect_error());
if(!isset($_POST['action'])){
$page = $_GET['page'];
$size = $_GET['size'];
$num = $_POST['order'][0]['column'];
 $order = $_POST['order'][0]['dir'];
 $search = $_POST['search']['value'];
 $start = $_POST['start'];
 $length = $_POST['length'];
 $where = null;
    if(!is_null($search)){
      $where = "WHERE id LIKE '%{$search}%' OR birth_date LIKE '%{$search}%'OR first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR gender LIKE '%{$search}%' OR hire_date LIKE '%{$search}%'";
    }

$num = $num + 1;
$a = array();
$query = "SELECT id, first_name, last_name, gender, hire_date, birth_date FROM employees {$where} ORDER BY {$num} {$order} LIMIT {$start} , {$length}";
$Selectallr = mysqli_query($conn, $query) or 
    die("Query fallita 0 " . mysqli_error($conn) . " " . mysqli_errno($conn));


$a["data"] = array();
while ($row = mysqli_fetch_array($Selectallr, MYSQLI_NUM)) //solo associativo
{
    $dipendente = array(

        "DT_RowId" => $row['0'],
        "birth_date" => $row['1'],
        "first_name" => $row['2'],
        "last_name" => $row['3'],
        "gender" => $row['4'],
        "hire_date" => $row['5']
    );
    array_push($a["data"], $dipendente);
}

$query = 'select count(id) as count from employees';
$queryb = mysqli_query($conn, $query) or 
    die("Query fallita 0 " . mysqli_error($conn) . " " . mysqli_errno($conn));
    while ($row = mysqli_fetch_array($queryb, MYSQLI_NUM)){
    $a['recordsTotal'] = $row[0];
    }

    $query = "SELECT count(*) FROM employees {$where}";
    $queryb = mysqli_query($conn, $query) or 
    die("Query fallita 0 " . mysqli_error($conn) . " " . mysqli_errno($conn));
    while ($row = mysqli_fetch_array($queryb, MYSQLI_NUM)){
      $a["recordsFiltered"] = $row[0];
    }
    echo json_encode($a);
}



if ($_POST["action"] == "create") {

    $birth = $_POST['data']['0']['users']['birth_date'];
    $first = $_POST['data']['0']['users']['first_name'];
    $last = $_POST['data']['0']['users']['last_name'];
    $gender = $_POST['data']['0']['users']['gender'];
    $hire = $_POST['data']['0']['users']['hire_date'];

    $inserto = "INSERT INTO employees (birth_date,first_name,last_name,gender,hire_date)" . " Values('$birth','$first','$last','$gender','$hire')"; //select 
    $insertor = mysqli_query($conn, $inserto) or //risultato
        die("Query fallita 0 " . mysqli_error($conn) . " " . mysqli_errno($conn));
    echo json_encode(array());
    }

if ($_POST["action"] == "edit") {
    $id = array_keys($_POST['data'])[0];
    if ($_POST['data'][$id]['users']['removed_date'] != "") {
        $inserto = "DELETE from employees WHERE id = '$id'"; //select
        $insertor = mysqli_query($conn, $inserto) or //risultato
            die("Query fallita 0 " . mysqli_error($conn) . " " . mysqli_errno($conn));
    } else {

        $birth = $_POST['data'][$id]['users']['birth_date'];
        $first = $_POST['data'][$id]['users']['first_name'];
        $last = $_POST['data'][$id]['users']['last_name'];
        $gender = $_POST['data'][$id]['users']['gender'];
        $hire = $_POST['data'][$id]['users']['hire_date'];



        $upda = "UPDATE employees
  SET birth_date='$birth', first_name='$first', last_name= '$last', gender ='$gender',hire_date='$hire' where id='$id'"; //select 
        $updar = mysqli_query($conn, $upda) or //risultato
            die("Query fallita 0 " . mysqli_error($conn) . " " . mysqli_errno($conn));
    }
    echo json_encode(array());
}


