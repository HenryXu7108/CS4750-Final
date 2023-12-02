<?php

function getUser($username) {
    global $db;

    $query = "select username, password from Person where username=:username";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":username", $username);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt->closeCursor();
    return $result[0] ?? null;
}

function verifyUserPassword($providedPassword, $storedPasswordHash) {
    return password_verify($providedPassword, $storedPasswordHash);
}
?>
