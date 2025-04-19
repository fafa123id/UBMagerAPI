<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>API Route Info</title>
  <style>
    body {
      background-color: #111;
      color: #fff;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .card {
      background: #222;
      border: 1px solid #444;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,255,200,0.2);
      text-align: left;
      max-width: 600px;
    }

    .route {
      font-size: 1.5rem;
      font-weight: bold;
      color: #00ffc8;
      margin-bottom: 10px;
    }

    .description {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      color: #aaa;
      text-decoration: none;
      border-bottom: 1px dashed #aaa;
    }

    .back-link:hover {
      color: #00ffc8;
      border-color: #00ffc8;
    }
  </style>
  <link rel="icon" type="image/png" href="https://img.icons8.com/?size=100&id=Oz14KBnT7lnn&format=png&color=000000">
</head>
<body>
  <div class="card">
    <div class="route">{{ $route }}</div>
    <div class="description">{{ $description }}</div>
    <a class="back-link" href="/">← Back to Home</a>
  </div>
</body>
</html>
