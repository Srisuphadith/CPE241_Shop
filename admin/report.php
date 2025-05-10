<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-[#2E282A] text-white px-8">

    <?php require_once("../navbar/nav_admin.php"); ?>

    <div class="text-center mt-8">
        <h1 class="text-4xl font-bold text-orange-400">Report Dashboard</h1>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 my-10">
        <div class="bg-[#3E3A3C] rounded-xl p-6 shadow-lg text-center">
            <h2 class="text-lg font-semibold text-orange-300">User Total</h2>
            <p class="text-3xl font-bold mt-2">120</p>
        </div>
        <div class="bg-[#3E3A3C] rounded-xl p-6 shadow-lg text-center">
            <h2 class="text-lg font-semibold text-orange-300">Admin Total</h2>
            <p class="text-3xl font-bold mt-2">5</p>
        </div>
        <div class="bg-[#3E3A3C] rounded-xl p-6 shadow-lg text-center">
            <h2 class="text-lg font-semibold text-orange-300">Grand Total</h2>
            <p class="text-3xl font-bold mt-2">125</p>
        </div>
        <div class="bg-[#3E3A3C] rounded-xl p-6 shadow-lg text-center">
            <h2 class="text-lg font-semibold text-orange-300">Orders Total</h2>
            <p class="text-3xl font-bold mt-2">312</p>
        </div>
    </div>

    <!-- Power BI Graph -->
    <div class="flex justify-center my-10">
        <iframe title="associate" width="900" height="500"
            src="https://app.powerbi.com/view?r=eyJrIjoiYTM1ZmZmMDctODQ1Ni00YzkzLWEwOGQtYzUxOTJlYzY2YTU2IiwidCI6IjZmNDQzMmRjLTIwZDItNDQxZC1iMWRiLWFjMzM4MGJhNjMzZCIsImMiOjEwfQ%3D%3D"
            frameborder="0" allowFullScreen="true"></iframe>
    </div>

    <!-- Top Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-12">
        <div class="bg-[#3E3A3C] p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-orange-300 mb-4">Top Customers</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>Customer A - 25 Orders</li>
                <li>Customer B - 22 Orders</li>
                <li>Customer C - 20 Orders</li>
            </ul>
        </div>
        <div class="bg-[#3E3A3C] p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-orange-300 mb-4">Top Coupon</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>COUPON50 - Used 50 Times</li>
                <li>NEWUSER20 - Used 35 Times</li>
                <li>SALE10 - Used 30 Times</li>
            </ul>
        </div>
    </div>

</body>
</html>
