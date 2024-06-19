
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
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
        .subject-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .subject-card {
            background-color: white;
            border-radius: 8px;
            margin: 10px;
            padding: 20px;
            width: 100%;
            max-width: 250px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .subject-card h3 {
            margin: 10px 0;
            font-size: 18px;
        }
        .subject-card p {
            margin: 5px 0;
            color: #555;
        }
        .subject-card span {
            font-weight: bold;
            color: #5c6bc0;
        }
        .subject-card a {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #5c6bc0;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .subject-card a:hover {
            background-color: #3f51b5;
        }
        @media (min-width: 600px) {
            .subject-container {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="subject-container">
        <?php
        if (isset($_GET['batch_id'])) {
            $batchId = $_GET['batch_id'];
            $jsonFile = 'batches_un.json';
            $jsonData = file_get_contents($jsonFile);
            $batches = json_decode($jsonData, true);

            if ($batches !== null) {
                $batchFound = false;
                foreach ($batches as $batchList) {
                    if (is_array($batchList)) {
                        foreach ($batchList as $batch) {
                            if (isset($batch['id']) && $batch['id'] == $batchId) {
                                $batchFound = true;
                                $name = isset($batch['details']['name']) ? $batch['details']['name'] : 'N/A';
                                $subjects = isset($batch['details']['subjects']) ? $batch['details']['subjects'] : [];

                                echo "
                                <div class='header'>
                                    $name
                                </div>
                                ";
                                // <p><span>$chapterCount</span> Chapters</p>
                                if (!empty($subjects)) {
                                    foreach ($subjects as $subject) {
                                        $subjectId = isset($subject['_id']) ? $subject['_id'] : [];
                                        $subjectName = isset($subject['subject']) ? $subject['subject'] : [];
                                        $chapterCount = isset($subject['chapters']) ? $subject['chapters'] : 0;

                                        echo "
                                        <div class='subject-card'>
                                            <h3>$subjectName</h3>
                                            
                                            <a href='chapter.php?subject_id=$subjectId&subject_name=".urlencode($subjectName)."'>View Chapters</a>
                                        </div>
                                        ";
                                    }
                                } else {
                                    echo "<p>No subjects found for this batch.</p>";
                                }
                                break;
                            }
                        }
                    }
                    if ($batchFound) break;
                }
                if (!$batchFound) {
                    echo "<p>Batch not found.</p>";
                }
            } else {
                echo "<p>Failed to load batch data.</p>";
            }
        } else {
            echo "<p>No batch ID specified.</p>";
        }
        ?>
    </div>
</body>
</html>
