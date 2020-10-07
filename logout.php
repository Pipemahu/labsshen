<? session_start();

unset($_SESSION['admin']);
unset($_SESSION['id']);
session_destroy();
header("location: index.php")

?>
