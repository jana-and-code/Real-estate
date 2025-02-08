<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
  <title>Custom File Input</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: pink;
      background-size: cover;
      background-position: center;
    }
    .w {
      height: 480px;
      width: 420px;
      background-color: rgba(0, 0, 0, 0.822);
      color: white;
      border-radius: 12px;
      padding: 30px 40px;
    }
    .input-bo input {
      width: 100%;
      height: 100%;
      background: transparent;
      border: none;
      outline: none;
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 40px;
      font-size: 19px;
      color: #fff;
      padding: 20px 24px 20px 20px;
    }
    .w .input-bo {
      position: relative;
      width: 100%;
      height: 50px;
      margin: 30px 0;
    }
    .custom-file {
      position: relative;
      width: 340px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: space-between; /* Align contents to right */
      border-radius: 40px;
      border: 2px solid rgba(255, 255, 255, 0.2);
      padding: 0 0px; /* Add padding for better spacing */
    }
    .custom-file input[type="file"] {
      position: absolute;
      opacity: 0;
      right: 1px; /* Adjust position to the right */
      width: 100px; /* Adjust width of input area */
      height: 60%;
    
    }
    .custom-file > span {
      font-family: tahoma, Arial;
      font-size: 19px;
      padding: 0px 0 0 6px;
      display: block;
    
    }
    .custom-file:after {
      content: "Upload";
      background-color: white;
      color: black;
      width: 90px;
      height: 47px;
      line-height: 48px;
      text-align: center;
      font-family: Verdana;
      border-radius: 40px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="w">
  <div class="custom-file">
    <span id="file-name">Choose Your File/s</span>
    <input type="file" name="file[]" id="file-upload" required onchange="updateFileName()" multiple="multiple">
  </div>
  <div class="input-bo">
    <input type="number" name="CustomerPayment" placeholder="Enter Customer Payment" required oninput="calculateRemainingAmount()">
  </div>
</div>

<script>
  function updateFileName() {
    const input = document.getElementById('file-upload');
    const fileName = input.files[0].name;
    document.getElementById('file-name').textContent = fileName;
  }
</script>

</body>
</html>
