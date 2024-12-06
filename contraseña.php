<?php
$contraseña = 'Dogmax10';
$hash = password_hash($contraseña, PASSWORD_BCRYPT);
echo "Hash generado: " . $hash;
?>
