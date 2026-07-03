<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
        }

        header {
            background-color: var(--card-bg);
            padding: 1.25rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logout-btn {
            background-color: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-card {
            background-color: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            animation: slideUp 0.5s ease-out;
            margin-bottom: 2rem;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-card h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .welcome-card p {
            color: var(--text-muted);
            line-height: 1.6;
        }

        .control-panel {
            background-color: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            animation: slideUp 0.6s ease-out;
        }

        .control-panel h3 {
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .device-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            background-color: rgba(15, 23, 42, 0.5);
            border-radius: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .device-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }

        .device-info h4 {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .device-info p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Toggle Switch CSS */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #334155;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
</head>
<body>

<header>
    <div class="logo">App Dashboard</div>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<main>
    <div class="welcome-card">
        <h2>Welcome to your Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You have successfully logged in. This is your responsive, modern dashboard area.</p>
    </div>

    <!-- Device Control Panel
    <div class="control-panel">
        <h3>Device Controls</h3>
        <div class="device-card">
            <div class="device-info">
                <h4>RGB Lights</h4>
                <p>Toggle to turn the RGB component ON or OFF</p>
            </div>
            <label class="switch">
                <input type="checkbox" id="rgbToggle" onchange="toggleRGB(this)">
                <span class="slider"></span>
            </label>
        </div>
    </div> -->

    <!-- Button State Monitor -->
    <div class="control-panel" style="margin-top: 2rem;">
        <h3>Button State Monitor</h3>
        <div class="device-card">
            <div class="device-info">
                <h4>Physical Button</h4>
                <p>Real-time state from TinkerIoT</p>
            </div>
            <div id="buttonStateDisplay" style="font-size: 1.1rem; font-weight: bold; padding: 0.5rem 1rem; border-radius: 0.5rem; background-color: rgba(255,255,255,0.1); color: var(--text-main);">Loading...</div>
        </div>
    </div>

    <!-- AI Object Detection Panel -->
    <div class="control-panel" style="margin-top: 2rem;">
        <h3>AI Object Detection (Teachable Machine)</h3>
        <p style="color: var(--text-muted); margin-bottom: 1rem; font-size: 0.9rem;">
            Ensure you have trained a Teachable Machine model with classes "card" and "no card". Paste your model URL below.
        </p>
        <div style="margin-bottom: 1rem;">
            <input type="text" id="modelUrl" value="https://teachablemachine.withgoogle.com/models/VHjEv4e39/" placeholder="https://teachablemachine.withgoogle.com/models/YOUR_MODEL/" style="width: 100%; padding: 0.75rem; border-radius: 0.375rem; border: 1px solid rgba(255, 255, 255, 0.1); background-color: rgba(15, 23, 42, 0.5); color: white; outline: none;">
        </div>
        <button type="button" onclick="initAI()" style="background-color: var(--primary); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500; transition: background-color 0.2s ease;">Start Camera & AI</button>
        <button type="button" onclick="stopAI()" style="background-color: rgba(239, 68, 68, 0.1); color: #fca5a5; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500; margin-left: 0.5rem; transition: background-color 0.2s ease;">Stop Camera</button>
        
        <div id="webcam-container" style="margin-top: 1.5rem; border-radius: 0.5rem; overflow: hidden; display: flex; justify-content: center; background-color: #000;"></div>
        <div id="label-container" style="margin-top: 1rem; font-weight: 500; text-align: center; font-size: 1.1rem;"></div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
<script>
    function toggleRGB(checkbox) {
        // According to requirements: C0=0 is ON, C0=1 is OFF
        // If the switch is checked (ON), send 0. Otherwise, send 1.
        const value = checkbox.checked ? '1' : '0';
        const url = `https://tinkercode.my:8443/tinkeriot/update?token=466d16766c7046158bee007840c7caa8&C0=VALUE=${value}`;
        
        // Use fetch with no-cors mode in case the API doesn't send CORS headers back
        fetch(url, { mode: 'no-cors' })
            .then(response => {
                console.log(`RGB command sent: ${value === '1' ? 'ON' : 'OFF'}`);
            })
            .catch(error => {
                console.error('Error sending RGB command:', error);
            });
    }

    // AI Object Detection Logic
    let model, webcam, labelContainer, maxPredictions;
    let isRunning = false;
    let lastState = ""; // To prevent spamming requests
    let lastDetectionTime = 0;
    const DETECTION_COOLDOWN = 3000; // 3 seconds cooldown

    async function initAI() {
        if (isRunning) return;
        const modelURLInput = document.getElementById("modelUrl").value;
        const URL = modelURLInput.trim();
        
        if (!URL) {
            alert("Please enter a valid Teachable Machine Model URL.");
            return;
        }

        const modelURL = URL + (URL.endsWith('/') ? '' : '/') + "model.json";
        const metadataURL = URL + (URL.endsWith('/') ? '' : '/') + "metadata.json";

        try {
            document.getElementById("label-container").innerText = "Loading model...";
            model = await tmImage.load(modelURL, metadataURL);
            maxPredictions = model.getTotalClasses();

            const flip = true; 
            webcam = new tmImage.Webcam(400, 400, flip); 
            await webcam.setup(); 
            await webcam.play();
            window.requestAnimationFrame(loop);

            document.getElementById("webcam-container").innerHTML = "";
            document.getElementById("webcam-container").appendChild(webcam.canvas);
            labelContainer = document.getElementById("label-container");
            labelContainer.innerHTML = "Camera Started";
            
            isRunning = true;
        } catch (error) {
            console.error(error);
            document.getElementById("label-container").innerText = "Error loading model. Check URL or console.";
        }
    }

    async function loop() {
        if (!isRunning) return;
        webcam.update(); 
        await predict();
        window.requestAnimationFrame(loop);
    }

    async function predict() {
        const prediction = await model.predictTopK(webcam.canvas, 1);
        if (prediction && prediction.length > 0) {
            const className = prediction[0].className.toLowerCase().trim();
            const probability = prediction[0].probability.toFixed(2);
            labelContainer.innerText = `Detected: ${prediction[0].className} (${(probability * 100).toFixed(0)}%)`;

            // Only trigger if confidence is high (e.g., >= 85%)
            if (probability >= 0.85) {
                const now = Date.now();
                if (now - lastDetectionTime > DETECTION_COOLDOWN) {
                    if (className === "card" && lastState !== "card") {
                        lastState = "card";
                        lastDetectionTime = now;
                        sendUpdate("1"); // C0=1
                        saveSnapshot();
                    } else if (className === "no card" && lastState !== "no card") {
                        lastState = "no card";
                        lastDetectionTime = now;
                        sendUpdate("0"); // C0=0
                    }
                }
            }
        }
    }

    function saveSnapshot() {
        if (!webcam || !webcam.canvas) return;
        
        // Create an off-screen canvas to draw the watermark
        const canvas = document.createElement('canvas');
        canvas.width = webcam.canvas.width;
        canvas.height = webcam.canvas.height;
        const ctx = canvas.getContext('2d');
        
        // Draw the original webcam frame
        ctx.drawImage(webcam.canvas, 0, 0);
        
        // Add datetime watermark
        const now = new Date();
        const datetimeStr = now.toLocaleString();
        
        ctx.font = '16px Arial';
        ctx.fillStyle = 'white';
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 3;
        ctx.textAlign = 'right';
        
        const x = canvas.width - 10;
        const y = canvas.height - 15;
        
        // Draw stroke for better visibility, then fill
        ctx.strokeText(datetimeStr, x, y);
        ctx.fillText(datetimeStr, x, y);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        
        fetch('save_image.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ image: dataUrl })
        })
        .then(res => res.json())
        .then(data => console.log('Snapshot saved:', data))
        .catch(err => console.error('Error saving snapshot:', err));
    }

    function sendUpdate(value) {
        const url = `https://tinkercode.my:8443/tinkeriot/update?token=466d16766c7046158bee007840c7caa8&C0=${value}`;
        fetch(url, { mode: 'no-cors' })
            .then(() => console.log(`AI Command sent: C0=${value}`))
            .catch(err => console.error("Error sending AI command:", err));
    }

    function stopAI() {
        isRunning = false;
        if (webcam) {
            webcam.stop();
        }
        document.getElementById("webcam-container").innerHTML = "";
        if (labelContainer) {
            labelContainer.innerHTML = "Camera Stopped";
        }
        lastState = "";
    }

    // Button State Polling
    function fetchButtonState() {
        const url = 'https://tinkercode.my:8443/tinkeriot/get?token=9f6068c6fdb84e17a92a45cf842e6d78&C0';
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const display = document.getElementById('buttonStateDisplay');
                if (data.includes('1')) {
                    display.innerText = 'ON / PRESSED';
                    display.style.backgroundColor = 'rgba(34, 197, 94, 0.2)';
                    display.style.color = '#4ade80';
                } else if (data.includes('0')) {
                    display.innerText = 'OFF / RELEASED';
                    display.style.backgroundColor = 'rgba(239, 68, 68, 0.2)';
                    display.style.color = '#f87171';
                } else {
                    display.innerText = data;
                }
            })
            .catch(error => {
                console.error('Error fetching button state:', error);
                document.getElementById('buttonStateDisplay').innerText = 'Offline';
            });
    }

    // Poll button state every 2 seconds
    setInterval(fetchButtonState, 2000);
    fetchButtonState(); // Initial fetch
</script>

</body>
</html>
