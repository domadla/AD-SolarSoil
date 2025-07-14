<?php
// View User Modal Content
$requirePath = __DIR__ . '/../../../handlers/user.handler.php';
require_once $requirePath;
$users = UserHandlerDemo::getAllUsers();
$userRows = '';
foreach ($users as $user) {
    $userRows .= '<tr>' .
        '<td>' . htmlspecialchars($user['id']) . '</td>' .
        '<td>' . htmlspecialchars($user['first_name']) . '</td>' .
        '<td>' . htmlspecialchars($user['last_name']) . '</td>' .
        '<td>' . htmlspecialchars($user['username']) . '</td>' .
        '<td>' . htmlspecialchars($user['role']) . '</td>' .
        '</tr>';
}
$userTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Username</th><th>Role</th></tr></thead>'
    . '<tbody>' . $userRows . '</tbody></table>';
echo $userTable;
