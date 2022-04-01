<?php

$con = mysqli_connect("localhost", "root", "", "ajax_curd");

$action = $_POST["action"];

if ($action  == "Insert") {
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $gender = mysqli_real_escape_string($con, $_POST["gender"]);
    $contact = mysqli_real_escape_string($con, $_POST["contact"]);

    $sql = "INSERT INTO users (name, gender, contact) VALUES ( '{$name}', '{$gender}', '{$contact}' )";
    if ($con->query($sql)) {
        $id = $con->insert_id;
        echo "
        <tr class='text-center' uid='{$id}'>
            <td>{$name}</td>
            <td>{$gender}</td>
            <td>{$contact}</td>
            <td class='d-flex justify-content-around'>
                <a href='#' class='btn btn-primary w-25 edit'
                data-bs-toggle='modal' data-bs-target='#modal_frm'>Edit</a>
                <a href='#' class='btn btn-danger w-25 delete'>Delete</a>
            </td>
        </tr>";
    } else echo false;
} elseif ($action == "Update") {
    $id = mysqli_real_escape_string($con, $_POST["id"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $gender = mysqli_real_escape_string($con, $_POST["gender"]);
    $contact = mysqli_real_escape_string($con, $_POST["contact"]);

    $sql = "UPDATE users SET name='$name', gender='$gender', contact='$contact' WHERE id='$id'";
    if ($con->query($sql)) {
        echo "
            <td>{$name}</td>
            <td>{$gender}</td>
            <td>{$contact}</td>
            <td class='d-flex justify-content-around'>
                <a href='#' class='btn btn-primary w-25 edit'
                data-bs-toggle='modal' data-bs-target='#modal_frm'>Edit</a>
                <a href='#' class='btn btn-danger w-25 delete'>Delete</a>
            </td>";
    } else echo false;
} elseif ($action == "Delete") {
    $id = $_POST["uid"];
    $sql = "DELETE FROM users WHERE id='$id'";
    if($con->query($sql)) {
        return true;
    } return false;
}
