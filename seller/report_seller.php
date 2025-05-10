<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>
<body class="m-4 bg-soft-black">
    <?php
        require_once("../navbar/nav_user.php");

        $sql_1 = "";
    ?>
    <div class="text-white ml-[20px] text-3xl pb-[10px] font-bold">Dashboard</div>
    <div class="grid grid-cols-2 gap-3 ml-[20px] mr-[20px]">
        <div class="w-full h-[400px] flex flex-col gap-3">
                <div class = "flex flex-col gap-3 bg-white w-full h-[150px] rounded">
                    <div class = "font-bold text-xl pt-[10px] pl-[20px] h-fit">ยอดขายรวมของร้านค้า (Total spend) </div>
                    <div class = "h-fit pt-[10px] pl-[20px] font-bold text-2xl text-orange-500">฿ 582,600.23</div>
                </div>
                <div class = "w-full h-[250px] flex flex-row gap-3">
                        <div class ="bg-white w-full h-[250px] rounded">
                            <div  class = "font-bold text-xl pt-[10px] pl-[20px]" >สินค้าที่ขายได้จำนวนมากที่สุด</div>
                            <div class="text-orange-500 h-fit pt-[10px] pl-[20px] font-bold text-2xl">Holy water</div>
                        </div>
                        <div class ="w-full h-[250px] flex flex-col gap-3">
                                <div class = "bg-white w-full h-[120px] rounded">
                                    <div class = "font-bold text-xl pt-[10px] pl-[20px]">ประเภทสินค้าที่ขายได้มากที่สุด</div>
                                    <div class="text-orange-500 h-fit pt-[10px] pl-[20px] font-bold text-2xl">รถเครื่อง</div>
                                </div>
                                <div class = "bg-white w-full h-[120px] rounded">
                                    <div class = "font-bold text-xl pt-[10px] pl-[20px]">สินค้าที่ขายได้รายได้มากที่สุด</div>
                                    <div class="text-orange-500 h-fit pt-[10px] pl-[20px] font-bold text-2xl">รถตัดหญ้า</div>
                                </div>
                        </div>


                </div>

        </div>
        <div class="bg-white w-full h-[400px] rounded">
            
            <div  class = "font-bold text-xl pt-[10px] pl-[20px]" >ยอดขายรวมในร้าานรายหมวด (Total spend by category)</div>
            <canvas id="myChart" style="width:100%;max-width:600px" class="ml-auto mr-auto mt-[30px]"></canvas>
        </div>
  
    </div>


    <script>
var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
var yValues = [55, 49, 44, 24, 15];
var barColors = ["red", "green","blue","orange","brown"];

new Chart("myChart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "World Wine Production 2018"
    }
  }
});
</script>
</body>
</html>