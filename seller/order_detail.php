<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="bg-soft-black">
    <?php
        require_once("../navbar/nav_user.php");

    ?>


    <?php
        $tran_ID = $_GET['trans_ID'];
        $grandTotal = $_GET['sum'];
        $sql = "SELECT B.productName,A.price,A.quantity,B.imgPath FROM tbl_transaction_items A JOIN tbl_products B ON A.product_ID=B.product_ID WHERE A.trans_ID = ? AND B.shop_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$tran_ID,$_SESSION['shop_ID']);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            //echo $row['productName']."   ".$row['price']."   ".$row['quantity']."<br>"; 
        ?>
        <div class = "bg-white w-fit flex flex-row rounded-xl pt-[10px] pb-[10px] pl-[20px] mb-[10px] ml-auto mr-auto text-xl">
            <div class=""><img src="../<?php echo $row['imgPath'];?>" class="w-40 h-40 object-cover rounded-md mb-1"></div>
            <div class="mt-auto mb-auto pl-[20px] pr-[20px]"><?php echo $row['productName'];?></div>
            <div class="mt-auto mb-auto pl-[20px] pr-[20px]">฿<?php echo $row['price'];?></div>
            <div class="mt-auto mb-auto pl-[20px] pr-[20px]">quantity: <?php echo $row['quantity'];?></div>
        </div>
     
        <?php
        }

    ?>
        <div class = "bg-white w-fit flex flex-row rounded-xl pt-[10px] pb-[10px] pl-[20px] pr-[20px] mb-[10px] ml-auto mr-auto text-2xl font-bold">
        grandTotal : ฿<?php echo $grandTotal; ?>
    </div>

</body>
</html>