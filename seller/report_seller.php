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
        //ยอดขายรวมของร้านค้า (Total spend)
        $sql_1 = "SELECT SUM(A.price) sum_price FROM `tbl_transaction_items` A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ?";
        //สินค้าที่ขายได้จำนวนมากที่สุด
        $sql_2 = "SELECT A.product_ID,COUNT(A.product_ID) sell_amount,B.productName FROM `tbl_transaction_items` A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ? GROUP BY A.product_ID ORDER BY sell_amount DESC";
        //ประเภทสินค้าที่ขายได้มากที่สุด
        $sql_3 = "SELECT R.cate_amount,W.cateName FROM tbl_categories W JOIN (SELECT B.cate_ID ,COUNT(B.cate_ID) cate_amount FROM `tbl_transaction_items` A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ? GROUP BY B.cate_ID ORDER BY cate_amount DESC) R ON W.cate_ID = R.cate_ID";
        //สินค้าที่ขายได้รายได้มากที่สุด
        $sql_4 = "SELECT SUM(A.price) sum_of_price,B.productName FROM tbl_transaction_items A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ? GROUP BY A.product_ID ORDER BY sum_of_price DESC";
        //ยอดขายรวมในร้าานรายหมวด (Total spend by category)
        $sql_5 = "SELECT R.price_amount,W.cateName FROM tbl_categories W JOIN (SELECT B.cate_ID ,SUM(A.price) price_amount FROM `tbl_transaction_items` A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ? GROUP BY B.cate_ID ORDER BY price_amount DESC) R ON W.cate_ID = R.cate_ID";
    ?>
    <div class="text-white ml-[20px] text-3xl pb-[10px] font-bold">Dashboard</div>
    <div class="grid grid-cols-2 gap-3 ml-[20px] mr-[20px]">
        <div class="w-full h-[400px] flex flex-col gap-3">
                <div class = "flex flex-col gap-3 bg-white w-full h-[150px] rounded">
                    <div class = "font-bold text-xl pt-[10px] pl-[20px] h-fit">ยอดขายรวมของร้านค้า (Total spend) </div>
                    <div class = "h-fit pt-[10px] pl-[20px] font-bold text-2xl text-orange-500">฿ 
                    <?php 
                        $stmt = $conn->prepare($sql_1);
                        $stmt->bind_param("s",$_SESSION['shop_ID']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        echo $row['sum_price'];
                    ?>
                    </div>
                </div>
                <div class = "w-full h-[250px] flex flex-row gap-3">
                        <div class ="bg-white w-full h-[250px] rounded">
                            <div  class = "font-bold text-xl pt-[10px] pl-[20px]" >สินค้าที่ขายได้จำนวนมากที่สุด</div>
                            <div class="text-orange-500 h-fit pt-[10px] pl-[20px] font-bold text-2xl">
                            <?php 
                        $stmt = $conn->prepare($sql_2);
                        $stmt->bind_param("s",$_SESSION['shop_ID']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        echo $row['productName'];
                    ?>
                            </div>
                        </div>
                        <div class ="w-full h-[250px] flex flex-col gap-3">
                                <div class = "bg-white w-full h-[120px] rounded">
                                    <div class = "font-bold text-xl pt-[10px] pl-[20px]">ประเภทสินค้าที่ขายได้มากที่สุด</div>
                                    <div class="text-orange-500 h-fit pt-[10px] pl-[20px] font-bold text-2xl">
                                    <?php 
                        $stmt = $conn->prepare($sql_3);
                        $stmt->bind_param("s",$_SESSION['shop_ID']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        echo $row['cateName'];
                    ?>
                                    </div>
                                </div>
                                <div class = "bg-white w-full h-[120px] rounded">
                                    <div class = "font-bold text-xl pt-[10px] pl-[20px]">สินค้าที่ขายได้รายได้มากที่สุด</div>
                                    <div class="text-orange-500 h-fit pt-[10px] pl-[20px] font-bold text-2xl">
                                    <?php 
                        $stmt = $conn->prepare($sql_4);
                        $stmt->bind_param("s",$_SESSION['shop_ID']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        echo $row['productName'];
                    ?>
                                    </div>
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

var xmlhttp = new XMLHttpRequest();
xmlhttp.onload = function() {
  const myObj = JSON.parse(this.responseText);
  const x = myObj.map(myObj => myObj[0]);
  const y = myObj.map(myObj => myObj[1]);
  const yy = y.map(s => parseInt(s, 10));
  console.log(x);
  console.log(y);
  var barColors = ["red", "green","blue","orange","brown","red", "green","blue","orange","brown"];
  new Chart("myChart", {
  type: "bar",
  data: {
    labels: x,
    datasets: [{
      backgroundColor: barColors,
      data: y
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: ""
    }
  }
});
}
xmlhttp.open("GET", "graph_data.php", true);
xmlhttp.send();




</script>
</body>
</html>