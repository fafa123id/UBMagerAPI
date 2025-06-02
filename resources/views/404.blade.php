<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Portal UBMAGER</title>
    <link rel="icon" href="https://ubmagerbucket.s3.ap-southeast-1.amazonaws.com/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
            --card-background: #ffffff;
            --text-color: #343a40;
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--background-color) 0%, #e0e7ee 100%);
            color: var(--text-color);
            overflow: hidden;
            position: relative;
        }

        .container {
            text-align: center;
            background: var(--card-background);
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow-medium);
            max-width: 900px;
            width: 90%;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInScale 0.8s ease-out forwards;
            position: relative;
            z-index: 2;
        }

        @keyframes fadeInScale {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 30px;
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            70% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            font-size: 3em;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-weight: 700;
            opacity: 0;
            transform: translateY(10px);
            animation: slideInText 0.8s ease-out forwards 0.3s;
        }

        @keyframes slideInText {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        p {
            font-size: 1.2em;
            margin-bottom: 40px;
            color: var(--secondary-color);
            opacity: 0;
            transform: translateY(10px);
            animation: slideInText 0.8s ease-out forwards 0.5s;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        /* Styling untuk img.logo */
        .logo {
            max-width: 80px;
            /* Batasi lebar maksimum logo */
            height: auto;
            /* Pertahankan rasio aspek */
            display: block;
            /* Menghilangkan margin bawah default pada img */
            margin: 0 auto 20px auto;
            /* Pusatkan logo dan beri jarak bawah */
        }

        /* Opsional: Styling untuk div.logo-wrapper */
        .logo-wrapper {
            text-align: center;
            /* Memastikan img di tengah jika display:block */
            margin-bottom: 20px;
            /* Jarak antara wrapper logo dan container */
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px var(--shadow-light);
            opacity: 0;
            transform: translateY(10px);
            animation: slideInButton 0.8s ease-out forwards 0.7s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: #ffffff;
            border: 2px solid var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px var(--shadow-medium);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px var(--shadow-medium);
        }

        @keyframes slideInButton {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Background particle animation */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background-color: rgba(0, 123, 255, 0.1);
            border-radius: 50%;
            animation: floatAndFade 10s infinite ease-in-out;
            opacity: 0;
        }

        @keyframes floatAndFade {
            0% {
                transform: translateY(0) scale(0);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) scale(1.2);
                opacity: 0;
            }
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 30px 40px;
            }

            h1 {
                font-size: 2.5em;
            }

            p {
                font-size: 1em;
            }

            .logo {
                width: 120px;
            }

            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 25px;
            }

            h1 {
                font-size: 2em;
            }

            p {
                font-size: 0.9em;
            }

            .logo {
                width: 100px;
            }
        }
    </style>
</head>

<body>
    <div class="particles" id="particles-js"></div>
    <div class="container">
        <div class="logo-wrapper">
            <img src="https://ubmagerbucket.s3.ap-southeast-1.amazonaws.com/favicon.png" alt="UBMAGER Logo" class="logo">
        </div>


        <h1>File not found</h1>
        <p>Published by UBMAGER</p>
    </div>

    <script>
        const particlesContainer = document.getElementById('particles-js');
        const numParticles = 30;

        function createParticle() {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particlesContainer.appendChild(particle);

            const size = Math.random() * 20 + 10; // size between 10px and 30px
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.bottom = `-${size}px`; // Start from below the viewport

            const animationDuration = Math.random() * 10 + 10; // 10 to 20 seconds
            const animationDelay = Math.random() * 5; // 0 to 5 seconds delay
            particle.style.animationDuration = `${animationDuration}s`;
            particle.style.animationDelay = `${animationDelay}s`;
            particle.style.animationIterationCount = 'infinite';
            particle.style.animationTimingFunction = 'ease-in-out';
            particle.style.animationName = 'floatAndFade';
        }

        for (let i = 0; i < numParticles; i++) {
            createParticle();
        }
    </script>
</body>

</html>