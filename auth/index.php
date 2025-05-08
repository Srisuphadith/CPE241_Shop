<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol</title>
    <?php require_once("../conn.php"); ?>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col md:flex-row justify-center items-center gap-10 p-6">

    <!-- Image and Title -->
    <div class="text-center">
        <img src="../img/mongkol1.jpg" alt="Mongkol Img" class="w-72 h-72 rounded-xl mx-auto ">
        <!-- <p class="text-5xl font-bold text-orange-500">MONGKOL</p> -->
    </div>

    <!-- Sign Up / Sign In Box -->
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-md w-full h-72 text-center">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Welcome to Mongkol!</h2>
        
        <a href="sign_up.php" class="block bg-orange-500 text-white text-lg font-bold py-2 rounded-full mb-2 hover:bg-orange-600 transition">Sign Up</a>
        
        <p class="text-sm text-gray-500 my-2">------------------- or -------------------</p>
        
        <a href="sign_in.php" class="block border border-orange-500 text-orange-500 text-lg font-bold py-2 rounded-full hover:bg-orange-100 transition">Sign In</a>
        
        <p class="text-xs text-gray-600 mt-4">
            By creating an account, I accept Mongkol's <br>
            <a href="#" class="text-orange-500 underline">Terms of Service</a> and 
            <a href="#" class="text-orange-500 underline">Privacy Policy</a>.
        </p>
    </div>
</body>
</html>
