<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add New Invoice</h4>
      </div>
      <div class="card-body">
        <!-- Customer + Invoice Info -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Customer Name</label>
            <input type="text" id="cname" class="form-control" placeholder="Enter Customer Name" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Bill No</label>
            <input type="text" id="billno" class="form-control" placeholder="INV-001" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" id="date" class="form-control" required>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-4">
            <label class="form-label">Bill Type</label>
            <select id="billtype" class="form-select" required>
              <option value="">Select Type</option>
              <option value="Cash">Cash</option>
              <option value="Credit">Credit</option>
              <option value="Online">Online</option>
            </select>
          </div>
        </div>

        <!-- Invoice Items Form -->
        <h5 class="mb-3">Invoice Items</h5>
        <div class="row g-3 align-items-end mb-3">
          <div class="col-md-4">
            <label class="form-label">Product</label>
            <input type="text" id="product" class="form-control" placeholder="Product Name">
          </div>
          <div class="col-md-2">
            <label class="form-label">Qty</label>
            <input type="number" id="qty" class="form-control" placeholder="0">
          </div>
          <div class="col-md-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" id="price" class="form-control" placeholder="0.00">
          </div>
          <div class="col-md-2">
            <label class="form-label">GST (%)</label>
            <input type="number" step="0.01" id="gst" class="form-control" placeholder="18">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-success w-100" id="addItem">+</button>
          </div>
        </div>

        <div class="table-responsive mt-4">
          <table class="table table-bordered text-center" id="itemsTable">
            <thead class="table-light">
              <tr>
                <th>Bill_No</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>GST (%)</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <div class="row my-4">
          <div class="col-md-4">
            <label class="form-label">Total</label>
            <input type="number" step="0.01" id="total" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Total GST</label>
            <input type="number" step="0.01" id="total_gst" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Bill Amount</label>
            <input type="number" step="0.01" id="bill_amount" class="form-control" readonly>
          </div>
        </div>

        <button type="button" id="save-btn" class="btn btn-danger px-4">💾 Save Invoice & Generate PDF</button>
      </div>
    </div>
  </div>

<script>
let total = 0, totalGst = 0, billAmount = 0;

// ➕ Add Item
$("#addItem").click(function() {
  let product = $("#product").val();
  let billno = $("#billno").val();
  let qty = parseInt($("#qty").val());
  let price = parseFloat($("#price").val());
  let gst = parseFloat($("#gst").val());

  if (!product || qty <= 0 || price <= 0) {
    alert("Please enter valid product details!");
    return;
  }

  let itemTotal = qty * price;
  let gstAmount = (itemTotal * gst) / 100;
  let finalTotal = itemTotal + gstAmount;

  total += itemTotal;
  totalGst += gstAmount;
  billAmount += finalTotal;

  let row = `<tr>
              <td>${billno}</td>
              <td>${product}</td>
              <td>${qty}</td>
              <td>${price.toFixed(2)}</td>
              <td>${gst}</td>
              <td>${finalTotal.toFixed(2)}</td>
              <td><button type="button" class="btn btn-sm btn-danger removeItem">X</button></td>
            </tr>`;
  $("#itemsTable tbody").append(row);

  $("#total").val(total.toFixed(2));
  $("#total_gst").val(totalGst.toFixed(2));
  $("#bill_amount").val(billAmount.toFixed(2));

  $("#product,#qty,#price,#gst").val("");
});
$("#save-btn").click(function () {
  let cname = $("#cname").val();
  let date = $("#date").val();
  let billtype = $("#billtype").val();
  let billno = $("#billno").val();
  let total = $("#total").val();
  let totalGst = $("#total_gst").val();
  let billAmount = $("#bill_amount").val();

  $.ajax({
    url: "insert1.php",
    method: "POST",
    data: { cname, billno, date, billtype, total, totalGst, billAmount },
    success: function (response) {
      console.log("Server Response insert1.php:", response);

      $("#itemsTable tbody tr").each(function () {
        let product = $(this).find("td:eq(1)").text(); 
        let qty = $(this).find("td:eq(2)").text();
        let price = $(this).find("td:eq(3)").text();
        let gst = $(this).find("td:eq(4)").text();
        let finalTotal = $(this).find("td:eq(5)").text();

        $.ajax({
          url: "insert.php",
          method: "POST",
          data: { product, billno, qty, price, gst, finalTotal },
          success: function (res) {
            console.log("Server Response insert.php:", res);
          }
        });
      });
      window.location.href = "table.php";
    }
  });
});
$(document).on("click", ".removeItem", function() {
  let row = $(this).closest("tr");

  let qty = parseFloat(row.find("td:eq(2)").text());
  let price = parseFloat(row.find("td:eq(3)").text());
  let gst = parseFloat(row.find("td:eq(4)").text());

  let itemTotal = qty * price;
  let gstAmount = (itemTotal * gst) / 100;
  let finalTotal = itemTotal + gstAmount;

  total -= itemTotal;
  totalGst -= gstAmount;
  billAmount -= finalTotal;

  row.remove();

  $("#total").val(total.toFixed(2));
  $("#total_gst").val(totalGst.toFixed(2));
  $("#bill_amount").val(billAmount.toFixed(2));
});
</script>
</body>
</html>
