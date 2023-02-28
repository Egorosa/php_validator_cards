<?php
// подключаемся к базе данных
$server = "localhost";
$login = "root";
$pass = "";
$name_db = "rest_api";

$link = mysqli_connect($server, $login, $pass, $name_db);

if (!$link) {
    echo "Could not connect to MySQL: " . mysqli_connect_error();
} 

// обработка GET-запроса на получение коллекции ресурсов

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $resource = $_GET['resource'] ?? '';

    switch ($resource) {
        case 'name':
            $stmt = mysqli_query($link, "
                SELECT c.id, c.name
                FROM city c
            ");
            $names = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
            if ($stmt === false) {
    die('Ошибка выполнения запроса: ' . mysqli_error($link));
}
            $response = ['names' => $names];
            break;
        default:
            $response = ['error' => 'Invalid resource'];
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

// обработка POST-запроса на создание/изменение ресурса
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource = $_GET['resource'] ?? '';

    switch ($resource) {
        case 'name':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = mysqli_prepare($link, "
                INSERT INTO city (name)
                VALUES (?)
            ");
            mysqli_stmt_bind_param($stmt, 's', $data['name']);
            mysqli_stmt_execute($stmt);
            $response = ['id' => mysqli_insert_id($link)];
            break;

        case 'city':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $_GET['id'] ?? '';
            $stmt = mysqli_prepare($link, "
                UPDATE city
                SET name = ?
                WHERE id = ?
            ");
            mysqli_stmt_bind_param($stmt, 'si', $data['name'], $id);
            mysqli_stmt_execute($stmt);
            break;

        default:
            $response = ['error' => 'Invalid resource'];
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

// обработка PUT-запроса на обновление ресурса
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $resource = $_GET['resource'] ?? '';
    $id = $_GET['id'] ?? '';

    switch ($resource) {
        case 'name':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = mysqli_prepare($link, "
                UPDATE city
                SET name = ?
                WHERE id = ?
            ");
            mysqli_stmt_bind_param($stmt, 'si', $data['name'], $id);
            mysqli_stmt_execute($stmt);
            break;

        case 'city':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = mysqli_prepare($link, "
                UPDATE city
                SET name = ?
                WHERE id = ?
            ");
            mysqli_stmt_bind_param($stmt, 'si', $data['name'], $id);
            mysqli_stmt_execute($stmt);
            break;
    }
}
