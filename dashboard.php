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

    <!-- Device Control Panel -->
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
    </div>
</main>

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
                // Optional: revert switch if there is a network error
                // checkbox.checked = !checkbox.checked;
            });
    }
</script>

</body>
</html>
