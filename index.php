<?php
include_once 'tatil.class.php';
$username = 'imcagri';
$token    = '******';

$tatil_ins = new Tatil($username, $token);
$repo_list = $tatil_ins->getRepoList();
if (php_sapi_name() === 'cli') {
    echo json_encode($repo_list);
    die;
}
?>

<html>
<head></head>
<body>
<table>
    <h1>
        Tatil.com çalışması
    </h1>
    <h3>Github repo listesi</h3>
    <thead>
    <th>Tam Adı</th>
    </thead>
    <tbody>
    <?php foreach ($repo_list as $index => $name): ?>
        <tr>
            <td><?php echo $name; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>