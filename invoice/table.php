<?php
session_start(); 
$conn = mysqli_connect("localhost", "root", "", "invoice");
if (isset($_SESSION['billno'])) {
    $billno = $_SESSION['billno'];
    $user = "SELECT * FROM table_list WHERE billno='$billno'";
    $user_result = mysqli_query($conn, $user);
    // $user_row = mysqli_fetch_assoc($user_result);
    $race = "SELECT * FROM table_value WHERE billno='$billno'";
    $race_result = mysqli_query($conn, $race);
    $race_row = mysqli_fetch_assoc($race_result);
} else {
    echo "No Bill Number found in session!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <style>
    body { background-color: #f8f9fa; }
    .invoice { background:#fff; padding:30px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1); }
    .invoice-header { border-bottom:2px solid #dee2e6; margin-bottom:20px; padding-bottom:10px; }
    .invoice-footer { border-top:2px solid #dee2e6; margin-top:20px; padding-top:10px; font-size:0.9rem; text-align:center; }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="text-end mb-3">
  <button class="btn btn-danger" id="download">Download PDF</button>
</div>
    <div class="invoice">
      <!-- Header -->
      <div class="invoice-header d-flex justify-content-between align-items-center">
        <h2>INVOICE BILL </h2>
        <div>
          <h4 class="mb-0">Info Tech</h4>
          <small>123, Main Street, Chennai</small><br>
          <small>Email: info@company.com</small>
        </div>
      </div>

      <!-- Invoice info -->
      <div class="row mb-4">
        <div class="col-sm-6">
          <h6>Bill No: <?php echo $race_row['billno']; ?></h6>
          <p>
            <strong>Customer Name:</strong><?php echo $race_row['cname'];?><br>
            <strong>Date:</strong><?php echo $race_row['date'];?><br>
            <strong>Bill Type:</strong> <?php echo $race_row['billtype'];?>
          </p>
        </div>
        <div class="col-sm-6 text-end">
          <p><strong>Invoice #:</strong> <?php echo $billno; ?></p>
          <p><strong>Date:</strong> <?php echo $race_row['date'];?></p>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>GST (%)</th>
              <th>Total</th>
            </tr>
          </thead>
         <tbody>

<?php
$i = 1;
while ($row = mysqli_fetch_assoc($user_result)) {
    echo "<tr>";
    echo "<td>" . $i++ . "</td>"; 
    echo "<td>" . $row['product'] . "</td>";
    echo "<td>" . $row['qty'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";
    echo "<td>" . $row['gst'] . "</td>";
    echo "<td>" . $row['total'] . "</td>";
    echo "</tr>";
}
?>
</tbody>
        </table>
      </div>

      <!-- Totals -->
      <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
          <table class="table table-borderless">
            <tr>
              <th>Grand Total:</th>
              <td class="text-end"><strong>$<?php echo $race_row['billAmount'];?></strong></td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="invoice-footer">
        <p>Thank you for your business!</p>
      </div>
    </div>
  </div>
</body>
<script>
document.getElementById("download").addEventListener("click", function () {
    const { jsPDF } = window.jspdf;
    const invoice = document.querySelector(".invoice");

    html2canvas(invoice, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "mm", "a4");
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
        pdf.save("invoice.pdf");
    });
});
</script>

</html>
