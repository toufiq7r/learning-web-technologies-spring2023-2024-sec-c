<?php

require_once 'database_model.php';

function login($username, $password) {
    $conn = getDatabaseConnection();
    $sql_stmt = "SELECT * FROM users WHERE username='{$username}' and password='{$password}'";
    $result = mysqli_query($conn, $sql_stmt);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        return true;
    }
    return false;
}

function getUserByUsername($username) {
    $conn = getDatabaseConnection();
    $sql_stmt = "SELECT * FROM users WHERE username = '{$username}'";
    $result = mysqli_query($conn, $sql_stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        return $user;
    }
    return null;
}

function getAllUsers() {
    $conn = getDatabaseConnection();
    $sql_stmt = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql_stmt);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    return $users;
}

function registerUser($user) {
    $conn = getDatabaseConnection();

    foreach ($user as $key => $value) {
        $newValue = validateTextData($value);
        $post[$key] = $newValue;
    }

    $sql_stmt = "INSERT INTO users (name, email, headline, username, password, org, role, gender, dob) 
                 VALUES ('{$user['name']}', '{$user['email']}', '{$user['headline']}', '{$user['username']}', 
                         '{$user['password']}', '{$user['org']}', '{$user['role']}', '{$user['gender']}', '{$user['dob']}')";
    if (mysqli_query($conn, $sql_stmt)) {
        return true;
    }
    return false;
}


function deleteUser($username) {
    $conn = getDatabaseConnection();
    $sql_stmt = "DELETE FROM users WHERE username = '{$username}'";
    if (mysqli_query($conn, $sql_stmt)) {
        return true;
    }
    return false;
}

function updateUser($user) {
    $conn = getDatabaseConnection();

    foreach ($user as $key => $value) {
        $newValue = validateTextData($value);
        $post[$key] = $newValue;
    }

    $userId = getUserIdFromUsername($user['username']);
    if (!$userId) {
        header('location: ../views/error.php?err=Error(updating user info): user update failed');
        exit();
    };

    $sql_stmt = "UPDATE users SET name = '{$user['name']}', email = '{$user['email']}', username = '{$user['username']}', password = '{$user['password']}', headline = '{$user['headline']}', org = '{$user['org']}', role = '{$user['role']}', gender = '{$user['gender']}', dob = '{$user['dob']}' WHERE id = '{$userId}'";
    if (mysqli_query($conn, $sql_stmt)) {
        return true;
    }
    return false;
}

function getUserIdFromUsername($username) {
    $conn = getDatabaseConnection();
    $username = validateTextData($username);
    $sql = "SELECT id FROM users WHERE username = '{$username}'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $userId = mysqli_fetch_assoc($result);
        return $userId['id'];
    }
    return null;
}

?>