
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapters</title>
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
        .chapter-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .chapter-card {
            background-color: white;
            border-radius: 8px;
            margin: 10px;
            padding: 20px;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: left;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }
        .chapter-card h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .chapter-card p {
            margin: 0;
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }
        .chapter-card span {
            font-weight: bold;
            color: #5c6bc0;
        }
        .chapter-details {
            display: flex;
            gap: 10px;
        }
        .chapter-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            cursor: pointer;
        }
        @media (min-width: 600px) {
            .chapter-container {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php
    if (isset($_GET['subject_id'])) {
        $subjectId = $_GET['subject_id'];
        $jsonFile = "topics/$subjectId.json"; // Updated to include the topics folder
        $jsonData = file_get_contents($jsonFile);
        $chapters = json_decode($jsonData, true);

        if ($chapters !== null) {
            $subjectName = isset($_GET['subject_name']) ? $_GET['subject_name'] : 'Unknown Subject';
            echo "
            <div class='header'>
                $subjectName
            </div>
            <div class='chapter-container'>
            ";

            foreach ($chapters as $chapter) {
                $chapterName = isset($chapter['name']) ? $chapter['name'] : 'N/A';
                $notesCount = isset($chapter['notes']) ? $chapter['notes'] : 0;
                $exercisesCount = isset($chapter['exercises']) ? $chapter['exercises'] : 0;
                $videosCount = isset($chapter['videos']) ? $chapter['videos'] : 0;
                $topicId = isset($chapter['_id']) ? $chapter['_id'] : '';

                echo "
                <a href='topic.php?topic_id=$topicId&chapterName=" . urlencode($chapterName) . "' class='chapter-card'>
                    <h3>$chapterName</h3>
                    <div class='chapter-details'>
                        <p><span>$notesCount</span> Notes,</p>
                        <p><span>$exercisesCount</span> Exercises,</p>
                        <p><span>$videosCount</span> Videos</p>
                    </div>
                </a>
                ";
            }

            echo "</div>";
        } else {
            echo "<p>Failed to load chapter data.</p>";
        }
    } else {
        echo "<p>No subject ID specified.</p>";
    }
    ?>
</body>
</html>
