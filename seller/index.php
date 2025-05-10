<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller</title>
</head>
<body class="m-4 bg-soft-black">
    
<?php 
// session_start();

require_once("../navbar/nav_user.php");
?>
<div class="poppins-font grid gap-4 my-4 px-4
            grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
    <?php
    session_start();
    $_SESSION['shop_ID'] = $shopID;
    $stmt = $conn->prepare("SELECT product_ID,productName,price,imgPath FROM tbl_products WHERE shop_ID = ? AND is_delete = 0");
    $stmt->bind_param("s",$shopID);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        //loop show product
    ?>
    
    <div class="bg-white rounded-lg flex flex-col">
    
        <div class="p-[10px]">
            <img src="../<?php echo $row['imgPath']; ?>" class=" w-full h-40 object-cover rounded-md mb-1">
        </div>
    
        <div class="pl-[10px] pb-[20px] felx flex-col">
            <div><?php echo $row['productName']; ?></div>
            <div class = "flex felx-row gap-2 pt-[20px]">
                <div><a href="edit_product.php?proID=<?php echo $row['product_ID']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded">Edit</a></div>
                <div><a href="delete_product.php?proID=<?php echo $row['product_ID']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">Delete</a></div>
                <p class = "text-2xl font-bold">฿<?php echo $row['price']; ?></p>
            </div>
        </div>

    </div>

    <?php
    //loop show product
}
?>
</div>

</body>
</html>


