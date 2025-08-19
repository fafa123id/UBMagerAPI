<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UBMager API Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://img.icons8.com/?size=100&id=Oz14KBnT7lnn&format=png&color=000000">
    <style>
        :root {
            --dark-bg: #11111b;
            --primary-glow: #00ffc8;
            --secondary-glow: #7a00ff;
            --text-primary: #f0f0f5;
            --text-secondary: #a0a0b0;
            --glass-bg: rgba(22, 22, 34, 0.4);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Poppins', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 80px 20px;
            position: relative;
        }

        .background-glow {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-glow), transparent 60%),
                        radial-gradient(circle, var(--secondary-glow), transparent 60%);
            filter: blur(150px);
            opacity: 0.15;
            z-index: -1;
            animation: moveGlow 25s infinite alternate ease-in-out;
            transform-origin: center;
        }

        @keyframes moveGlow {
            0% {
                transform: translate(-50%, -50%) rotate(0deg) scale(1);
            }
            100% {
                transform: translate(-40%, -60%) rotate(180deg) scale(1.4);
            }
        }

        .container {
            width: 100%;
            max-width: 700px;
            text-align: center;
            padding: 40px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            z-index: 1;
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        h1 {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: -1px;
            background: linear-gradient(90deg, #fff, #c0c0d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation-delay: 0.2s;
        }

        p {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
            animation-delay: 0.4s;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 30px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            margin-bottom: 40px;
            animation-delay: 0.6s;
        }

        .cta-button:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 80%);
            border-radius: 50%;
            opacity: 0;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease, opacity 0.4s ease;
            z-index: -1;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0, 255, 200, 0.1);
            border-color: rgba(0, 255, 200, 0.5);
        }
        
        .cta-button:hover:before {
            width: 300px;
            height: 300px;
            opacity: 0.3;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--text-secondary);
            font-weight: 600;
            animation-delay: 0.8s;
        }

        #routes-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: left;
        }
        
        .route-item {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 15px 20px;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 15px;
            font-family: 'Courier New', Courier, monospace;
            transition: all 0.3s ease;
            opacity: 0;
            transform: scale(0.95);
            animation: scaleIn 0.5s ease-out forwards;
        }

        .route-item:hover {
            transform: scale(1.03);
            background: rgba(0, 0, 0, 0.4);
            border-color: var(--primary-glow);
        }

        .method {
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: bold;
            color: var(--dark-bg);
            flex-shrink: 0; 
        }
        
        .method-get { background-color: #4ade80; }
        .method-post { background-color: #38bdf8; }
        .method-put { background-color: #facc15; }
        .method-patch { background-color: #f97316; }
        .method-delete { background-color: #f43f5e; }
        .method-default { background-color: #94a3b8; }

        .uri {
            color: var(--text-primary);
            font-size: 1rem;
            word-break: break-all; 
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 40px 15px; 
            }

            .container {
                padding: 25px; 
            }

            h1 {
                font-size: 2.2rem; 
            }

            .route-item {
                padding: 12px 15px; 
                align-items: flex-start; 
            }

            .uri {
                font-size: 0.9rem; 
            }
        }

    </style>
</head>

<body>
    <div class="background-glow"></div>
    <div class="container">
        <h1 class="fade-in-up">Welcome To Our API Portal</h1>
        <p class="fade-in-up">Published by UBMager</p>
        <a href="{{config("app.url")}}/api/documentation" class="cta-button fade-in-up">
            Go to Documentation
        </a>
        <h2 class="fade-in-up">Available API Routes</h2>
        <ul id="routes-list"></ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/api-routes')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const list = document.getElementById('routes-list');
                    if (!data || data.length === 0) {
                        list.innerHTML = '<li class="route-item" style="justify-content: center; color: var(--text-secondary);">No API routes found.</li>';
                        return;
                    }
                    const methodColors = {
                        'GET': 'method-get', 'POST': 'method-post', 'PUT': 'method-put',
                        'PATCH': 'method-patch', 'DELETE': 'method-delete',
                    };
                    data.forEach((route, index) => {
                        const li = document.createElement('li');
                        li.className = 'route-item';
                        li.style.animationDelay = `${index * 0.1}s`;
                        const methodSpan = document.createElement('span');
                        const methodClass = methodColors[route.method.toUpperCase()] || 'method-default';
                        methodSpan.className = `method ${methodClass}`;
                        methodSpan.textContent = route.method;
                        const uriSpan = document.createElement('span');
                        uriSpan.className = 'uri';
                        uriSpan.textContent = route.uri;
                        li.appendChild(methodSpan);
                        li.appendChild(uriSpan);
                        list.appendChild(li);
                    });
                })
                .catch(error => {
                    console.error('Error fetching API routes:', error);
                    const list = document.getElementById('routes-list');
                    list.innerHTML = `<li class="route-item" style="justify-content: center; color: #f43f5e;">Failed to load API routes.</li>`;
                });
        });
    </script>
</body>

</html>