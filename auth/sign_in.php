<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mongkol | Sign In</title>
  <?php
      require_once("../conn.php");
  ?>
</head>
<body class="pt-12 m-12">
  <?php
  if(isset($_GET["message"])){
    $message = htmlspecialchars($_GET["message"]);
    ?>

    <div id="alert-border-2" class="fixed top-2 left-1/2 transform -translate-x-1/2 z-50 flex items-center p-4 border-t-4 border-green-300 rounded-lg shadow-lg max-w-md w-full">
      <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
      </svg>
      <div class="ms-3 text-sm font-medium">
    
    
        <?php echo "<p class=\"text-green poppins-font text-xs\"> $message </p>"; ?>
    
        </div>
      <button onclick="closeAlert()" class="ms-auto -mx-1.5 -my-1.5 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-50 inline-flex items-center justify-center h-8 w-8 dark:text-green-400 dark:hover:bg-green-100" aria-label="Close">
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
  if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT user_ID, firstName, `role`,password_hash FROM tbl_users WHERE userName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && password_verify($password, $user['password_hash'])) {
      // Correct password
      session_start();
      $_SESSION["userID"] = $user['user_ID'];
      $_SESSION["role"] = $user['role'];
      $_SESSION['firstName'] = $user['firstName'];
      if($user['role'] == "user"){
        header("Location: ../user/market.php");
      }else if($user['role'] == "seller"){
        header("Location: ../seller");
      }
      exit();
  }
  else {
      // Invalid username or password
      ?>
<div id="alert-border-2" class="fixed top-2 left-1/2 transform -translate-x-1/2 z-50 flex items-center p-4 border-t-4 border-red-300 rounded-lg shadow-lg max-w-md w-full">
  <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <div class="ms-3 text-sm font-medium">
          <p class="text-red poppins-font text-xs">Username or password incorrect</p>
        </div>
        <button onclick="closeAlert()" class="ms-auto -mx-1.5 -my-1.5 text-red-500 hover:bg-red-50 p-1.5 rounded-lg">
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
  }
}
?>

  <h2 class="montserrat-font text-5xl text-center pb-8 font-bold">Sign In</h2>
  <form action="#" method="post" class="flex flex-col justify-center items-center">
    <p><label for="username" class="poppins-font text-lg">Username</label><br><input class="poppins-font text-base bg-stone-200 px-4 py-2 rounded-lg" type="text" placeholder="username" name="username" required></p><br>
    <p><label for="password" class="poppins-font text-lg">Password</label><br><input class="poppins-font text-base bg-stone-200 px-4 py-2 rounded-lg" type="password" placeholder="********" name="password" required></p><br>
    <input type="submit" name="submit" value="Sign In" class="font-bold text-2xl montserrat-font bg-dark-orange px-14 py-1 rounded-2xl"><br>
    <p class="poppins-font text-xs my-2">Don't Have an account yet <a href="sign_up.php" class="text-dark-orange">Sign Up!</a></p>
  </form>
</body>
</html>












































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
