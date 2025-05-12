<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order list</title>
    <link rel="icon" href="../img/logo.png">
</head>
<body class = "bg-soft-black ">
<?php
require_once("../navbar/nav_user.php");
?>
    <div class="bg-white w-fit h-auto p-[20px] rounded-2xl ml-auto mr-auto">
    <table class="text-black font-bold text-2xl w-fits">
    <?php
        
        $sql = "SELECT A.trans_ID,SUM(A.price*A.quantity) sum_price,B.imgPath FROM tbl_transaction_items A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ? GROUP BY A.trans_ID";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$_SESSION['shop_ID']);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){

        ?>
            <tr class="pt-[30px]"><td class="pr-[20px] pt-[20px]">Transaction ID :</td> <td class="pr-[20px] pt-[20px]"><?php echo $row['trans_ID'];?></td> <td class="pr-[20px] pt-[20px]">GrandTotal</td> <td class="pr-[20px] pt-[20px] text-green-500"><?php echo $row['sum_price'];?></td> <td class="pr-[20px] pt-[20px]"><a href="/seller/order_detail.php?trans_ID=<?php echo $row['trans_ID'];?>&sum=<?php echo $row['sum_price'];?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded">Detail</a></td></tr>
        <?php
        }
    ?>
    </table>
    </div>
</body>
</html>