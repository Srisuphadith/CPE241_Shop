<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="m-4 bg-soft-black">

<?php

if (isset($_POST['submit'])){
    require_once("../conn.php");
    $cate_ID = $_POST['cate_ID'];
    $productName = $_POST['productName'];
    $description =  $_POST['description'];
    $price =  $_POST['price'];
    $quantity =  $_POST['quantity'];
    $img_name = $_FILES["fileToUpload"]["name"];
    $proID = $_GET['proID'];

    $target_dir = "../img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(!empty($_FILES["fileToUpload"]["name"])){
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
                }
    }
    $stmt = $conn->prepare("UPDATE tbl_products SET cate_ID = ?, productName = ?, description = ?, price = ?, quantity = ?, imgPath = ? WHERE product_ID = ?");
    $stmt->bind_param("sssssss",$cate_ID,$productName,$description,$price,$quantity,$img_name,$proID);
    $stmt->execute();
    $result = $stmt->get_result();
}else{
    $stmt = $conn->prepare("UPDATE tbl_products SET cate_ID = ?, productName = ?, description = ?, price = ?, quantity = ? WHERE product_ID = ?");
    $stmt->bind_param("ssssss",$cate_ID,$productName,$description,$price,$quantity,$proID);
    $stmt->execute();
    $result = $stmt->get_result();
}
    header("Location: /seller/");
    exit();
}else{
require_once("../navbar/nav_user.php");
$proID = $_GET['proID'];
$stmt = $conn->prepare("SELECT * FROM tbl_products WHERE product_ID = ?");
$stmt->bind_param("s",$proID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
// cate_ID,productName,description,price,quantity 
?>

<div class="w-full max-w-xl ml-auto mr-auto">
  <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="post" action="<?php $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
        category_ID
      </label>
      <select name="cate_ID" id="cars" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <?php 
                $stmt = $conn->prepare("SELECT * FROM tbl_categories");
                $stmt->execute();
                $result = $stmt->get_result();
                while($row2 = $result->fetch_assoc()){
                    if($row['cate_ID'] == $row2['cate_ID']){
                        echo "<option value=".$row2['cate_ID']." selected >".$row2['cateName']."</option>";
                    }else{
                        echo "<option value=".$row2['cate_ID'].">".$row2['cateName']."</option>";
                    }
                }
            ?>
        </select>
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      productName
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="<?php echo $row['productName'];?>" name="productName">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      description
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="<?php echo $row['description'];?>" name="description">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      price
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="<?php echo $row['price'];?>" name="price">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      quantity
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="<?php echo $row['quantity'];?>" name="quantity">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      image
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="file" name="fileToUpload" id="fileToUpload">
    </div>


    <div class="flex items-center justify-between">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name ="submit">
        confirm
      </button>
    </div>
  </form>
</div>


<?php } ?>
</body>
</html>


