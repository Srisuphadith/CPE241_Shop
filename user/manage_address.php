<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="../img/logo.png">
</head>
<body class="bg-soft-black">
    <?php require_once("../navbar/nav_user.php");
    $sql = "SELECT * FROM tbl_address WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <div class="flex flex-col pl-[100px] pr-[100px] ">
        <div class="text-2xl font-bold text-white mb-[20px]">Manage address</div>
        <?php 
        while($row = $result->fetch_assoc()){
        ?>
        <div class="bg-white h-[100px] rounded-2xl mb-[20px] text-black flex flex-row ">
            <div class=" text-xl font-bold w-full h-full flex items-center justify-cente pl-[30px] pr-[30px] ">
                <div class="flex flex-col">
                    <div>
                         <?php echo $row['buildingNumber'].", ".$row['district'].", ".$row['province'].", ".$row['subdistrict'].", ".$row['country'].", ".$row['zip_code'];?> 
                    </div>
                    <div class="text-gray-600 font-normal">
                         <?php echo $row['txt']; ?>
                    </div>
                </div>
            </div>
            <div class="flex flex-row h-full w-fit">
                <div class="flex items-center justify-cente pl-[30px] pr-[40px] text-xl font-bold">
                    <?php 
                     echo $row['is_primary'] == 1 ? "Primary" : "Secondary";
                    ?>
                </div>
                <div class="flex items-center justify-cente pl-[30px] pr-[40px]">
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded" href ="edit_address.php?buildingNumber=<?php echo $row['buildingNumber'];?>">Edit</a>
                </div>
                <div class="flex items-center justify-cente pr-[40px]">
                    <a class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded"  href ="delete_address.php?buildingNumber=<?php echo $row['buildingNumber'];?>">Delete</a>
                </div>
            </div>
        </div> 
        <?php }?>
        <div> <a class ="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded w-fit" href="add_address.php"> Add address</a></div>
    </div>
</body>
</html>