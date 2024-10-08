
<?php
function custom_hash_password($password) {
    return password_hash(trim($password), PASSWORD_DEFAULT);
}

function custom_verify_password($password, $hash) {
    return password_verify(trim($password), $hash);
}
?>