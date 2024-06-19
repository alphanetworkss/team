<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Video</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .url-container {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .quality-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 10px;
        }
        .quality-buttons button {
            padding: 10px 20px;
            background-color: #5c6bc0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .quality-buttons button:hover {
            background-color: #3f51b5;
        }
        .url-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 16px;
            display: none; /* Hide input initially */
        }
        .url-container button {
            padding: 10px 20px;
            background-color: #5c6bc0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .url-container button:hover {
            background-color: #3f51b5;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f1f1f1;
            color: #555;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #ddd;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .quality-buttons button {
                flex: 1 1 calc(50% - 10px);
                font-size: 14px;
            }
        }
        @media (max-width: 400px) {
            .quality-buttons button {
                flex: 1 1 100%;
                font-size: 14px;
            }
        }
    </style>
    <script>
        function copyUrl() {
            var urlField = document.getElementById("videoUrl");
            urlField.select();
            urlField.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            alert("Copied the URL: " + urlField.value);
        }

        function getRandomPrefix() {
            // var prefixes = ['/yl', '/yl1', '/yl2'];
            var prefixes = ['/yl', '/yl1', '/yl2'];
            var randomIndex = Math.floor(Math.random() * prefixes.length);
            return prefixes[randomIndex];
        }

        function updateUrl(quality) {
            var videoUrl = "<?php echo isset($_GET['videoUrl']) ? htmlspecialchars($_GET['videoUrl'], ENT_QUOTES, 'UTF-8') : ''; ?>";
            var videoName = "<?php echo isset($_GET['videoName']) ? htmlspecialchars($_GET['videoName'], ENT_QUOTES, 'UTF-8') : ''; ?>";
            var randomPrefix = getRandomPrefix();
            var modifiedUrl = `${randomPrefix} https://pw.pwjarvis.tech?v=${videoUrl}&quality=${quality} -n ${videoName} -By(@Team_AlphaNetwork)`;
            var urlField = document.getElementById("videoUrl");
            urlField.value = modifiedUrl;
            urlField.style.display = 'block'; // Show the input field
        }

    </script>
</head>
<body>
    <?php
    if (isset($_GET['videoUrl']) && isset($_GET['videoName'])) {
        $videoUrl = htmlspecialchars($_GET['videoUrl'], ENT_QUOTES, 'UTF-8');
        $videoName = htmlspecialchars($_GET['videoName'], ENT_QUOTES, 'UTF-8');
        echo "
        <div class='container'>
            <h3>Download Video</h3>
            <p><strong>Video Name:</strong> $videoName</p>
            <div class='url-container'>
                <div class='quality-buttons'>
                    <button onclick='updateUrl(240)'>240p</button>
                    <button onclick='updateUrl(360)'>360p</button>
                    <button onclick='updateUrl(480)'>480p</button>
                    <button onclick='updateUrl(720)'>720p</button>
                </div>
                <input type='text' id='videoUrl' value='' readonly>
                <button onclick='copyUrl()'>Copy URL</button>
            </div>
            <a href='javascript:history.back()' class='back-button'>Back</a>
        </div>
        ";
    } else {
        echo "
        <div class='container'>
            <p>No video URL provided.</p>
            <a href='javascript:history.back()' class='back-button'>Back</a>
        </div>
        ";
    }
    ?>
</body>
</html>
