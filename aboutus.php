<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us Section</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('background.png') no-repeat center center / cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #ffffff;
    }

    .container {
      background-color: rgba(42, 42, 51, 0.95);
      width: 90%;
      max-width: 1100px;
      display: flex;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(5px);
      transition: all 0.3s ease;
    }

    .image-box {
      width: 50%;
      padding-right: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .image-box img {
      width: 100%;
      max-width: 300px;
      border: 6px solid #00ffcc;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
      transition: transform 0.3s ease;
    }

    .image-box img:hover {
      transform: scale(1.05);
    }

    .text-box {
      width: 50%;
    }

    .text-box h3 {
      font-size: 20px;
      letter-spacing: 1px;
      color: #ffffff;
      margin-bottom: 10px;
    }

    .text-box h3 span {
      color: #00ffcc;
      font-weight: bold;
    }

    .text-box h2 {
      font-size: 24px;
      line-height: 1.4;
      margin-bottom: 15px;
      color: #e0e0e0;
    }

    .text-box p {
      font-size: 15px;
      line-height: 1.8;
      color: #cccccc;
      margin-bottom: 10px;
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      margin-right: 10px;
      padding: 10px 20px;
      background-color: #00ffcc;
      color: #1a1a1a;
      text-decoration: none;
      font-weight: bold;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background-color: #2a2a33;
      color: #00ffcc;
      border: 1px solid #00ffcc;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.75);
      animation: fadeIn 0.4s;
    }

    .modal-content {
      background-color: #2a2a33;
      margin: 10% auto;
      padding: 25px;
      border: 1px solid #00ffcc;
      width: 85%;
      max-width: 500px;
      color: white;
      border-radius: 10px;
      position: relative;
      animation: slideUp 0.4s ease-out;
    }

    .modal-content h2 {
      margin-bottom: 10px;
    }

    .modal-content ul {
      padding-left: 18px;
      margin-top: 10px;
    }

    .modal-content li {
      margin-bottom: 8px;
      line-height: 1.6;
    }

    .close {
      color: #00ffcc;
      position: absolute;
      top: 12px;
      right: 16px;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: #ffffff;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        padding: 25px;
      }

      .image-box,
      .text-box {
        width: 100%;
        padding: 0;
      }

      .image-box {
        margin-bottom: 20px;
      }

      .text-box h2 {
        font-size: 20px;
      }

      .btn {
        margin-top: 10px;
        width: 100%;
        text-align: center;
      }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="image-box">
      <img src="menirish.jpg" alt="Team celebrating" />
    </div>
    <div class="text-box">
      <h3>ABOUT <span>US</span></h3>
      <h2>
        Binuya, Martin Josshua G. (Developer, UI Designer) <br><br>
        De Belen, Irish Zabrina B. (Developer, UI Designer)
      </h2>
      <p>BSIT 3B-G1</p>
      <p>
        We are students from Bulacan State University currently enrolled in Bachelor of Science in Information Technology (BSIT), Section 3BG1. This MIRA Account Management System is our final project for Web Systems and Technologies 2 (WST 2).<br><br>
        The system demonstrates our understanding of modern web development and addresses real-world needs in user data management, blending design, backend integration, and user experience.
      </p>
      <a href="dashboard.php" class="btn">⟵ Back</a>
      <a href="#" class="btn" onclick="openModal()">📩 Contact Us</a>
    </div>
  </div>

  <div id="contactModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Contact Us</h2>
      <p>You can reach us via email or Facebook:</p>
      <ul>
        <li><strong>Martin Josshua G. Binuya:</strong> binuya.martin.bsit@gmail.com</li>
        <li><a href="https://www.facebook.com/jzssua" target="_blank" style="color:#00ffcc;">Facebook Profile</a></li>
        <li><strong>Irish Zabrina B. De Belen:</strong> debelen.irishzabrina.bsit@gmail.com</li>
        <li><a href="https://www.facebook.com/irishzabrina.debelen.9" target="_blank" style="color:#00ffcc;">Facebook Profile</a></li>
      </ul>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById("contactModal").style.display = "block";
    }

    function closeModal() {
      document.getElementById("contactModal").style.display = "none";
    }

    window.onclick = function (event) {
      const modal = document.getElementById("contactModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
