<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | billing</title>
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>
    <form action="#" method="post" class="max-w-6xl mx-auto mt-8 grid grid-cols-[60%_40%] gap-4 h-[600px]">
    <!-- Left Column (60%) -->
    <div class="space-y-4 bg-soft-white p-4 overflow-auto">
        <p class="poppins-font text-2xl font-bold">Payment Method</p>
        <div class="bg-white px-2 py-4"></div>

        <p class="poppins-font text-2xl font-bold">Shipping Address</p>
        <div class="bg-white px-2 py-4">
            <?php
            $userID = intval($_SESSION['userID']);
            $sql = "SELECT * FROM tbl_address WHERE user_ID = $userID ORDER BY `type`, is_primary DESC;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $shownTypes = [];
                $first = true;

                while ($row = mysqli_fetch_assoc($result)) {
                    $type = $row['type'];
                    $label = $type === 'house' ? 'House' : 'Office';

                    // Show section heading only once per type
                    if (!in_array($type, $shownTypes)) {
                        echo '<h2 class="text-xl font-bold mb-2 mt-6 popins-font">' . $label . '</h2>';
                        $shownTypes[] = $type;
                    }

                    $fullAddress = "{$row['buildingNumber']} {$row['sub_province']} {$row['province']} {$row['city']} {$row['country']} {$row['zip_code']}";
                    $isChecked = ($row['is_primary'] == 1 || $first) ? 'checked' : '';
                    $first = false;
                    ?>
                    <label class="block mb-4 p-4 border border-gray-600 rounded-lg cursor-pointer hover:bg-gray-200">
                        <input type="radio" name="address_ID" value="<?php echo $row['address_ID']; ?>" class="mr-3" <?php echo $isChecked; ?>>
                        <span class="popins-font text-base"><?php echo $fullAddress; ?></span><br>
                        <span class="popins-font text-xs text-gray-400"><?php echo htmlspecialchars($row['txt']); ?></span>
                    </label>
                    <?php
                }
            } else {
                echo '<p class="text-white">No addresses found.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Right Column (40%) -->
    <div class="grid grid-rows-[90%_10%] bg-soft-white p-4">
        <div class="overflow-auto">
            <!-- You can put summary or order info here -->
        </div>

        <!-- Confirm Button at bottom (20%) -->
        <div class="flex items-end">
            <button type="submit" class="w-full h-full bg-blue-500 text-white rounded text-lg font-semibold hover:bg-blue-600 transition">
                Confirm Address
            </button>
        </div>
    </div>
</form>


</body>
</html>