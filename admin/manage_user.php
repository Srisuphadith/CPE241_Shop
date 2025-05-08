<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manage_user</title>
</head>
  <body class = "bg-w-full bg-[#2E282A] mr-8 ml-8" >
    <?php require_once("../navbar/nav_admin.php"); ?>
    <div class="container mx-auto px-4 py-8">

    <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Manage User</h2> 
    <!-- 🔍 Search Form -->
    <form method="GET" class="mb-6 flex justify-center">
      <input type="text" name="username" placeholder="กรอก Username"
             class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-400"
             value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '' ?>">
      <button type="submit"
              class="px-4 py-2 bg-orange-500 text-white rounded-r-md hover:bg-orange-600 transition">
        ค้นหา
      </button>
    </form>

    <!-- 📋 Table -->
    <div class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-700 bg-white border border-gray-200">
        <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
          <tr>
            <th class="px-4 py-3">User ID</th>
            <th class="px-4 py-3">First Name</th>
            <th class="px-4 py-3">Middle Name</th>
            <th class="px-4 py-3">Last Name</th>
            <th class="px-4 py-3">Username</th>
            <th class="px-4 py-3">role</th>
            <th class="px-4 py-3">Action</th>
          </tr>
        </thead>
      <tbody>
        <?php
          $searchUsername = isset($_GET['username']) ? $_GET['username'] : '';
          $searchUsername = $conn->real_escape_string($searchUsername);
          
          $sql = "SELECT * FROM tbl_users";
          if (!empty($searchUsername)) {
              $sql .= " WHERE userName LIKE '$searchUsername'";
          }
          
          $result = $conn->query($sql);
          if ($result && $result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo "<tr class='border-b hover:bg-gray-100'>";
          
                  // ID
                  echo "<td class='px-4 py-2'>{$row['user_ID']}</td>";
          
                  // First Name
                  echo "<td class='px-4 py-2'>
                          <input type='text' value=\"{$row['firstName']}\" id='fname-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                        </td>";
          
                  // Middle Name
                  $midName = $row['midName'] !== NULL ? $row['midName'] : '';
                  echo "<td class='px-4 py-2'>
                          <input type='text' value=\"$midName\" id='mname-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                        </td>";
          
                  // Last Name
                  echo "<td class='px-4 py-2'>
                          <input type='text' value=\"{$row['lastName']}\" id='lname-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                        </td>";
          
                  // Username
                  echo "<td class='px-4 py-2'>{$row['userName']}</td>";

                  // role
                  echo "<td class='px-4 py-2'>
                          <input type='text' value=\"{$row['role']}\" id='role-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                        </td>";

                  // Buttons
                  echo "<td class='px-4 py-2 flex gap-2'>
                          <button onclick='editUser({$row['user_ID']})' class='bg-orange-400 hover:bg-orange-500 text-white px-3 py-1 rounded text-xs'>Edit</button>
                          <button onclick='saveUser({$row['user_ID']})' class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs'>Save</button>
                          <button onclick='deleteUser({$row['user_ID']})' class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs'>Delete</button>
                        </td>";
          
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6' class='text-center px-4 py-3 text-gray-500'>ไม่พบข้อมูล</td></tr>";
          }
          ?>
          
        </tbody>
      </table>
    </div>
  </div>
  <div class="container mx-auto px-4 py-8 ">
        <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Admin User List</h2>

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full text-sm text-left text-gray-700 bg-white border border-gray-200">
                <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">User ID</th>
                        <th class="px-4 py-3">First Name</th>
                        <th class="px-4 py-3">Middle Name</th>
                        <th class="px-4 py-3">Last Name</th>
                        <th class="px-4 py-3">Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM user_public WHERE role = 'admin'" );
                    while($row = $result->fetch_assoc()) {
                        echo "<tr class='border-b hover:bg-gray-100'>";
                        echo "<td class='px-4 py-2'>{$row['user_ID']}</td>";
                        echo "<td class='px-4 py-2'>{$row['firstName']}</td>";
                        echo "<td class='px-4 py-2'>{$row['midName']}</td>";
                        echo "<td class='px-4 py-2'>{$row['lastName']}</td>";
                        echo "<td class='px-4 py-2'>{$row['userName']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>         
    <div class="container mx-auto px-4 py-8 ">
        <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Users List</h2>

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full text-sm text-left text-gray-700 bg-white border border-gray-200">
                <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">User ID</th>
                        <th class="px-4 py-3">First Name</th>
                        <th class="px-4 py-3">Middle Name</th>
                        <th class="px-4 py-3">Last Name</th>
                        <th class="px-4 py-3">Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM user_public WHERE role = 'user'" );
                    while($row = $result->fetch_assoc()) {
                        echo "<tr class='border-b hover:bg-gray-100'>";
                        echo "<td class='px-4 py-2'>{$row['user_ID']}</td>";
                        echo "<td class='px-4 py-2'>{$row['firstName']}</td>";
                        echo "<td class='px-4 py-2'>{$row['midName']}</td>";
                        echo "<td class='px-4 py-2'>{$row['lastName']}</td>";
                        echo "<td class='px-4 py-2'>{$row['userName']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
  </body>
<script>
  function editUser(userID) {
    document.getElementById(`fname-${userID}`).disabled = false;
    document.getElementById(`mname-${userID}`).disabled = false;
    document.getElementById(`lname-${userID}`).disabled = false;
    document.getElementById(`role-${userID}`).disabled = false;
  }

  function saveUser(userID) {
    const firstName = document.getElementById(`fname-${userID}`).value;
    const midName = document.getElementById(`mname-${userID}`).value;
    const lastName = document.getElementById(`lname-${userID}`).value;
    const role = document.getElementById(`role-${userID}`).value;
    

    fetch('update.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${userID}&firstName=${encodeURIComponent(firstName)}&midName=${encodeURIComponent(midName)}&lastName=${encodeURIComponent(lastName)}&role=${encodeURIComponent(role)}`

    })
    .then(response => response.text())
    .then(result => {
      alert(result);
      location.reload(); // อัปเดตข้อมูลใหม่หลังเซฟ
    });
  }

  function deleteUser(userID) {
    if (confirm("Are you sure you want to delete this user?")) {
      fetch(`delete.php?id=${userID}`, { method: 'GET' })
        .then(response => {
          if (response.ok) {
            location.reload();
          } else {
            alert('Error deleting user.');
          }
        });
    }
  }
</script>

</html>