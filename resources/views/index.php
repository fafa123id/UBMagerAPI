<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal UBMager API</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            animation: fadeIn 2s ease-in-out;
        }
        h1 {
            font-size: 3rem;
            margin: 0;
        }
        p {
            font-size: 1.2rem;
            margin-top: 10px;
            opacity: 0.7;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        li {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <link rel="icon" type="image/png" href="https://img.icons8.com/?size=100&id=Oz14KBnT7lnn&format=png&color=000000">
</head>
<body>
    <div class="container">
        <h1>Welcome To Our API Portal</h1>
        <p>Published by UBMager</p>
        <h2>Available API Routes</h2>
        <ul id="routes-list"></ul>
    </div>

    <script>
        fetch('/api-routes')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('routes-list');
                data.forEach(route => {
                    const li = document.createElement('li');
                    li.textContent = `${route.method} - ${route.uri}`;
                    list.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error fetching API routes:', error);
            });
    </script>
</body>
</html>