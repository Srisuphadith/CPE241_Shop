<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="../img/logo.png">
</head>
<body class = "bg-soft-black">
    <?php
        

        if(isset($_POST['submit'])){
            require_once('../conn.php');
            session_start();
            $sql = "INSERT INTO tbl_address(user_ID,buildingNumber,district,province,subdistrict,country,zip_code,is_primary,txt) VALUES(?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss",$_SESSION['userID'],$_POST['buildingNumber'],$_POST['district'],$_POST['province'],$_POST['subdistrict'],$_POST['country'],$_POST['zip_code'],$_POST['is_primary'],$_POST['txt']);
            $stmt->execute();
            header("Location: /user/manage_address.php");

        }else{
            require_once("../navbar/nav_user.php");
    
    ?>



<div class="w-full max-w-xl ml-auto mr-auto text-left" >
  <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="post" action="<?php $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
  <div class="mb-4">
  <label class="block text-gray-700 text-sm font-bold mb-2 text-center">
         Add address
      </label>
  </div>
  <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      buildingNumber
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="" name="buildingNumber">
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      district
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="" name="district">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      province
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="" name="province">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      subdistrict
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="text" value="" name="subdistrict">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      country
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="" name="country">
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      zip code
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="" name="zip_code">
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2">
      text description
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="" name="txt">
    </div>

    <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
      select is primary
      </label>
    <select name="is_primary" id="is_primary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option disabled selected value> -- select an option -- </option>
        <option value= "1" >primary</option>
        <option value= "0" >secondary</option>
    </select>
    </div>

    <div class="flex items-center justify-between">
      <a href="manage_address.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Cancel
        </a>
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name ="submit">
        confirm
      </button>
    </div>
  </form>
</div>








<?php } ?>
</body>
</html>