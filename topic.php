<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topic Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #5c6bc0;
            color: white;
            width: 100%;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .nav-bar {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .nav-bar a {
            padding: 10px 20px;
            margin: 0 5px;
            text-decoration: none;
            color: #555;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .nav-bar a.active {
            background-color: #5c6bc0;
            color: white;
        }
        .topic-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .topic-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .topic-card, .note-card {
            background-color: white;
            border-radius: 8px;
            margin: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: left;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }
        .topic-card h3, .note-card h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .topic-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .topic-card a, .note-card a {
            margin-top: 10px;
            text-align: center;
            padding: 10px;
            background-color: #5c6bc0;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .topic-card a:hover, .note-card a:hover {
            background-color: #3f51b5;
        }
        .note-card .note-item {
            text-align: center;
        }
        .note-card .note-item h4 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .note-card .note-item a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #5c6bc0;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .note-card .note-item a:hover {
            background-color: #3f51b5;
        }
        @media (min-width: 600px) {
            .topic-container {
                flex-direction: column;
            }
        }
    </style>
    <script>
        function showContent(type) {
            document.getElementById('lectures').style.display = type === 'lectures' ? 'block' : 'none';
            document.getElementById('notes').style.display = type === 'notes' ? 'block' : 'none';
            document.getElementById('lectures-tab').classList.toggle('active', type === 'lectures');
            document.getElementById('notes-tab').classList.toggle('active', type === 'notes');
        }
    </script>
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function fetch_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "Error: $error_msg";
    }
    curl_close($ch);
    return $result;
}

function clean_video_name($name) {
    $name = str_replace('||', '', $name);
    $name = str_replace('(', '', $name);
    $name = str_replace(')', '', $name);
    return $name;
}

if (isset($_GET['topic_id']) && isset($_GET['chapterName'])) {
    $topicId = $_GET['topic_id'];
    $chapterName = $_GET['chapterName'];
    $jsonFile = "subtopics/$topicId.json"; // Adjust the path if necessary

    if (!file_exists($jsonFile)) {
        echo "<p>JSON file not found: $jsonFile</p>";
    } else {
        $jsonData = file_get_contents($jsonFile);
        if ($jsonData === false) {
            echo "<p>Failed to read JSON file: $jsonFile</p>";
        } else {
            $topicDetails = json_decode($jsonData, true);
            if ($topicDetails === null) {
                echo "<p>Failed to decode JSON data.</p>";
            } else {
                echo "
                <div class='header'>
                    $chapterName
                </div>
                <div class='nav-bar'>
                    <a id='lectures-tab' href='javascript:void(0)' class='active' onclick=\"showContent('lectures')\">Lectures</a>
                    <a id='notes-tab' href='javascript:void(0)' onclick=\"showContent('notes')\">Notes</a>
                </div>
                <div class='topic-container' id='lectures' style='display: block;'>
                    <div class='topic-grid'>
                ";

                if (isset($topicDetails['videos'])) {
                    foreach ($topicDetails['videos'] as $video) {
                        $videoName = isset($video['videoDetails']['name']) ? $video['videoDetails']['name'] : 'N/A';
                        $videoName = clean_video_name($videoName);
                        $videoImage = isset($video['videoDetails']['image']) ? $video['videoDetails']['image'] : '';
                        $videoUrl = isset($video['videoDetails']['videoUrl']) ? $video['videoDetails']['videoUrl'] : '#';

                        $long_url = urlencode("https://study.alphanetwork.fun/download.php?videoUrl=$videoUrl&videoName=$videoName");
                        $api_token = 'fb3cc0e15e3665e914e4f096477bcedea1091dc8';
                        $api_url = "https://offerlinks.in/api?api={$api_token}&url={$long_url}&format=text";

                        // Fetch the shortened URL using cURL
                        $result = fetch_url($api_url);

                        if (strpos($result, "Error:") === 0) {
                            echo "<p>Failed to fetch shortened URL for video: $videoName. $result</p>";
                        } else {
                            echo "
                            <div class='topic-card'>
                                <img src='$videoImage' alt='$videoName'>
                                <h5>$videoName</h5>
                                <a href=$result target='_blank'>Download Video</a>
                            </div>";
                        }
                    }
                } else {
                    echo "<p>No videos found.</p>";
                }

                echo "
                    </div>
                </div>
                ";
                echo "
                <div class='topic-container' id='notes' style='display: none;'>
                ";

                if (isset($topicDetails['notes'])) {
                    foreach ($topicDetails['notes'] as $note) {
                        foreach ($note['homeworkIds'] as $homework) {
                            $noteName = isset($homework['topic']) ? $homework['topic'] : 'N/A';
                            foreach ($homework['attachmentIds'] as $attachment) {
                                $fileName = isset($attachment['name']) ? $attachment['name'] : 'Download';
                                $fileUrl = isset($attachment['baseUrl']) && isset($attachment['key']) ? $attachment['baseUrl'] . $attachment['key'] : '#';

                                echo "
                                <div class='note-item'>
                                    <h4>$noteName</h4>
                                    <a href='$fileUrl' download>$fileName</a>
                                </div>
                                ";
                            }
                        }
                    }
                } else {
                    echo "<p>No notes found.</p>";
                }

                echo "</div>";
            }
        }
    }
} else {
    echo "<p>No topic ID or chapter name specified.</p>";
}
?>

</body>
</html>
