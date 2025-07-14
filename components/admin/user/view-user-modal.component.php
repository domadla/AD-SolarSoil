<?php
// View User Modal Content
require_once __DIR__ . '/../../../handlers/user.handler.php';
$users = UserHandler::getAllUsers();
$userRows = '';
foreach ($users as $user) {
    $userRows .= '<tr>' .
        '<td>' . htmlspecialchars($user['id']) . '</td>' .
        '<td>' . htmlspecialchars($user['firstname']) . '</td>' .
        '<td>' . htmlspecialchars($user['lastname']) . '</td>' .
        '<td>' . htmlspecialchars($user['username']) . '</td>' .
        '<td>' . htmlspecialchars($user['usertype']) . '</td>' .
        '</tr>';
}
$userTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Username</th><th>Usertype</th></tr></thead>'
    . '<tbody>' . $userRows . '</tbody></table>';
echo $userTable;
