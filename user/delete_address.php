<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="../img/logo.png">
</head>
<body class="m-4 bg-soft-black">

<?php


if(isset($_POST['submit'])){
    require_once("../conn.php");

    $stmt = $conn->prepare("DELETE FROM tbl_address WHERE buildingNumber = ?");
    $stmt->bind_param("s",$_GET['buildingNumber']);
    $stmt->execute();
    $result = $stmt->get_result();
    header("Location: /user/manage_address.php");
    exit();
}else{
    require_once("../navbar/nav_user.php");
?>
    <div class="w-full max-w-xl ml-auto mr-auto bg-white rounded pt-[20px]">
        <div class="font-bold text-red text-center text-3xl pb-[20px]">Are you sure to delete address</div>

        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="flex flex-row gap-10 w-fit ml-auto mr-auto pb-[20px]">
            <a href="/user/manage_address.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded">Cancel</a>
            <input type="submit" name="submit" value="Delete" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded" >
            </div>
        </form>
    </div>
<?php
}
?>
</body>
</html>

