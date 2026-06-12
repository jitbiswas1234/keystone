<?php
// Replace 'admin123' with the actual password you want to use
$password = 'admin123'; 
echo "Copy this hash: " . password_hash($password, PASSWORD_DEFAULT);
?>