<?php

ob_start();

?>




<?php
require 'conn.php';

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM donated_books WHERE id = ?");
$stmt->execute([$id]);

header("Location: donated_books_approval.php"); // or wherever your list is
exit;
?>
<?php

ob_flush();

?>