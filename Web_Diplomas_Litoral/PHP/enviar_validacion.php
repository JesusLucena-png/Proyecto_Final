<?php
include "MySql_php.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID no válido");
}

$id = intval($_GET["id"]);

$sql = "
    UPDATE student_validations
    SET status = 'Submitted'
    WHERE id = $id
";

echo $conn->query($sql) ? "Validación enviada" : "Error al enviar";
