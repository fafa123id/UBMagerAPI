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
            min-height: 100vh;
            height: fit-content;
            overflow: auto;
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
            opacity: 0.8;
        }

        h2 {
            margin-top: 30px;
            font-size: 2rem;
        }

        .button-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .route-button {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            padding: 12px 20px;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .route-button:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: #00ffc8;
            transform: scale(1.05);
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
        <ul id="routes-list" class="button-list"></ul>
    </div>

    <script>
        fetch('/api-routes')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('routes-list');
                data.forEach(route => {
                    const button = document.createElement('li');
                    button.className = 'route-button';
                    button.textContent = `${route.method} - ${route.uri}`;
                    const li = document.createElement('li');
                    li.appendChild(button);
                    list.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error fetching API routes:', error);
            });
    </script>
</body>
</html>
