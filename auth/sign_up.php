<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mongkol | Sign Up</title>
  <?php
    require_once("../conn.php");
  ?>
</head>
<body class = 'pt-12 m-12'>
  <?php
  if(isset($_GET["message"])){
    $message = htmlspecialchars($_GET["message"]);
    ?>

<div id="alert-border-2" class="fixed top-2 left-1/2 transform -translate-x-1/2 z-50 flex items-center p-4 border-t-4 border-red-300 rounded-lg shadow-lg max-w-md w-full">
  <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <div class="ms-3 text-sm font-medium">


    <?php echo "<p class=\"text-red ibm-plex-sans-thai-semibold text-xs\">$message</p>"; ?>

    </div>
  <button onclick="closeAlert()" class="ms-auto -mx-1.5 -my-1.5 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-50 inline-flex items-center justify-center h-8 w-8 dark:text-red-400 dark:hover:bg-red-100" aria-label="Close">
    <span class="sr-only">Dismiss</span>
    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
    </svg>
  </button>
</div>

<script>
    const url = new URL(window.location);
    if (url.searchParams.has("message")) {
      url.searchParams.delete("message");
      window.history.replaceState({}, document.title, url.toString());
    }
  </script>
    <?php
  }
  if(isset($_POST['submit'])) {
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $phonenumber = $_POST["phone"];
    $password = $_POST["password"];
    $passwordHash = password_hash($password , PASSWORD_DEFAULT);
    $error = array();
    if(strlen($password) <= 9 or strlen($password) >= 30){
        array_push($error , "Password must be about 9 - 30 words long.");
    }
    if(strlen($username) >= 31){
        array_push($error , "username must less than 31 words long.");
    }
    if(strlen($phonenumber) != 10){
      array_push($error , "Phone Number must be 10 numbers.");
    }
    if(!ctype_digit($phonenumber)){
      array_push($error , "Contain only numbers.");
    }
    
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_users WHERE userName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    // echo $row['countMatch']; 
    $count = $row['count'];
    if($count >= 1){
      array_push($error , "Username already exist!");
    }
    if(count($error) > 0){ ?>


<div id="alert-border-2" class="fixed top-2 left-1/2 transform -translate-x-1/2 z-50 flex items-center p-4 border-t-4 border-red-300 rounded-lg shadow-lg max-w-md w-full">
  <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <div class="ms-3 text-sm font-medium">
    <?php
      foreach ($error as $err) {
        echo "<p class=\"text-red ibm-plex-sans-thai-semibold text-xs\">$err</p>";
      }
    ?>
  </div>
  <button onclick="closeAlert()" class="ms-auto -mx-1.5 -my-1.5 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-50 inline-flex items-center justify-center h-8 w-8 dark:text-red-400 dark:hover:bg-red-100" aria-label="Close">
    <span class="sr-only">Dismiss</span>
    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
    </svg>
  </button>
</div>

<script>
  function closeAlert() {
    document.getElementById('alert-border-2').style.display = 'none';
  }
</script>


  <?php
    }else{


      

      $fullname = trim($_POST["fullname"]); // Remove leading/trailing whitespace
      $parts = preg_split('/\s+/', $fullname); // Split by spaces
      
      $firstname = "";
      $middlename = "";
      $lastname = "";
      
      if (count($parts) == 2) {
          // Case: First name + Last name
          $firstname = $parts[0];
          $lastname = $parts[1];
          $middlename = NULL;
      } elseif (count($parts) >= 3) {
          // Case: First name + Middle name(s) + Last name
          $firstname = $parts[0];
          $lastname = $parts[count($parts) - 1]; // Get the last element as lastname
          
          // Combine all middle parts into middlename
          $middlename = "";
          for ($i = 1; $i < count($parts) - 1; $i++) {
              $middlename .= $parts[$i] . " ";
          }
          $middlename = trim($middlename);
      } else {
          // Case: Only one word entered
          $firstname = $fullname;
      }




      if (!empty($middlename)) {
        $stmt = $conn->prepare("INSERT INTO tbl_users (`firstName`, `midName`, `lastName`, `userName`, `password_hash`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $middlename, $lastname, $username, $passwordHash);
    } else { 
        $stmt = $conn->prepare("INSERT INTO tbl_users (`firstName`, `lastName`, `userName`, `password_hash`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $username, $passwordHash);
    }
      if($stmt->execute()){
        header("Location: sign_in.php?message=" . urlencode("Registration successful!"));
      }else{
        die("something went wrong.");
        header("Location: sign_up.php?message=" . urlencode("something went wrong! Please Try again"));
      }
    }
  }

  ?>
  <h2 class="montserrat-font text-5xl text-center pb-8 font-bold">Sign Up</h2>  
  <form action="sign_up.php" method="post" class="flex flex-col justify-center items-center">
    <p><label for="Full Name" class="ibm-plex-sans-thai-semibold text-lg">Full Name</label><br><input class="ibm-plex-sans-thai-regular text-base bg-stone-200 px-4 py-2 rounded-lg" type="text" placeholder="e.g. Siddhartha Emerald" name="fullname"></p><br>
    <p><label for="Phone" class="ibm-plex-sans-thai-semibold text-lg">Phone</label><br><input class="ibm-plex-sans-thai-regular text-base bg-stone-200 px-4 py-2 rounded-lg" type="text" placeholder="Main Phone Number" name="phone"></p><br>
    <p><label for="User Name" class="ibm-plex-sans-thai-semibold text-lg">Username</label><br><input class="ibm-plex-sans-thai-regular text-base bg-stone-200 px-4 py-2 rounded-lg" type="text" placeholder="Enter your name" name="username" required></p><br>
    <p><label for="Password" class="ibm-plex-sans-thai-semibold text-lg">Password</label><br><input class="ibm-plex-sans-thai-regular text-base bg-stone-200 px-4 py-2 rounded-lg" type="password" placeholder="Enter your password" name="password" required></p><br>
    <input type="submit" name="submit" value="Sign Up" class="ibm-plex-sans-thai-bold text-xl bg-dark-orange px-14 py-1 rounded-2xl"><br>
    <p class="ibm-plex-sans-thai-regular text-xs my-2">Already have an account <a href="sign_in.php" class="text-dark-orange">Sign In!</a></p>
  </form>

</body>
</html>

<?php 
// Get form data
// $firstName = $_POST['firstName'];
// $username = $_POST['username'];
// $password = $_POST['password'];

// // Hash the password
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// // Insert into tbl_users
// $sql = "INSERT INTO tbl_users (firstName, userName, password_hash)
//         VALUES (?, ?, ?)";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sss", $firstName, $username, $hashed_password);

// if ($stmt->execute()) {
//   echo "✅ Registration successful! <a href='login.html'>Login here</a>";
// } else {
//   echo "❌ Error: " . $stmt->error;
// }

// $stmt->close();
// $conn->close();
?>






































<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Register</h2>
    <form action="#" method="POST" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input type="text" id="name" name="name" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
      </div>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
      </div>
      <button type="submit"
              class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
        Register
      </button>
    </form>
    <p class="text-center text-sm text-gray-600 mt-4">
      Already have an account? <a href="#" class="text-blue-500 hover:underline">Login</a>
    </p>
  </div>
</body>
</html> -->
