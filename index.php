
<!DOCTYPE html>
<html lang="en">
<head>
        <!-- Google tag (gtag.js) -->
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batches</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .batch-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .batch-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 10px;
            padding: 10px;
            width: 200px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            background-color: #fff;
        }
        .batch-card img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .batch-card h3 {
            margin: 10px 0;
            font-size: 18px;
        }
        .batch-card p {
            margin: 5px 0;
            color: #555;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
            max-width: 100%;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        @media (max-width: 400px) {
            .batch-card {
                width: 340px;
                height: 250px;
            }
        
            .search-form input[type="text"] {
                width: 60%;
            }
            .search-form input[type="submit"] {
                width: 30%;
                margin-left: 5px;
            }
        }
        @media (max-width: 480px) {
            .search-form input[type="text"] {
                width: 50%;
            }
            .search-form input[type="submit"] {
                width: 30%;
                margin-left: 5px;
            }
        }
    </style>
</head>
<body>
    <h1>Team_AlphaNetwork</h1>

    <!-- Search Form -->
    <form action="index.php" method="GET" class="search-form">
        <input type="text" id="search" name="search" placeholder="Enter name or exam">
        <input type="submit" value="Search">
    </form>

    <div class="batch-container">
        <?php
        $jsonFile = 'batches_un.json';
        $jsonData = file_get_contents($jsonFile);
        $batches = json_decode($jsonData, true);

        // Function to filter batches based on search query
        function filterBatches($batches, $query) {
            $filteredBatches = [];
            foreach ($batches as $batchList) {
                if (is_array($batchList)) {
                    foreach ($batchList as $batch) {
                        $name = isset($batch['details']['name']) ? strtolower($batch['details']['name']) : '';
                        $exam = isset($batch['details']['exam']) ? implode(", ", $batch['details']['exam']) : '';

                        // Filter by name or exam containing the query
                        if (strpos(strtolower($name), $query) !== false || strpos(strtolower($exam), $query) !== false) {
                            $filteredBatches[] = $batch;
                        }
                    }
                }
            }
            return $filteredBatches;
        }

        // Display filtered batches if search query is provided
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchQuery = strtolower($_GET['search']);
            $filtered = filterBatches($batches, $searchQuery);

            if (!empty($filtered)) {
                foreach ($filtered as $batch) {
                    $id = isset($batch['id']) ? $batch['id'] : 'N/A';
                    $name = isset($batch['details']['name']) ? $batch['details']['name'] : 'N/A';
                    $imageBaseUrl = isset($batch['previewImage']['baseUrl']) ? $batch['previewImage']['baseUrl'] : '';
                    $imageKey = isset($batch['previewImage']['key']) ? $batch['previewImage']['key'] : 'coomingsoon.png';
                    $exam = isset($batch['details']['exam']) ? implode(", ", $batch['details']['exam']) : 'N/A';
                    $imageUrl = $imageBaseUrl ? $imageBaseUrl . $imageKey : 'coomingsoon.png';

                    echo "
                    <div class='batch-card'>
                        <img src='$imageUrl' alt='$name'>
                        <h3>$name</h3>
                        <p>Category: $exam</p>
                        <a href='subject.php?batch_id=$id'>View Subjects</a>
                    </div>
                    ";
                }
            } else {
                echo "<p>No batches found matching '$searchQuery'.</p>";
            }

        } else {
            // Display all batches if no search query
            if ($batches !== null) {
                foreach ($batches as $batchList) {
                    if (is_array($batchList)) {
                        foreach ($batchList as $batch) {
                            $id = isset($batch['id']) ? $batch['id'] : 'N/A';
                            $name = isset($batch['details']['name']) ? $batch['details']['name'] : 'N/A';
                            $imageBaseUrl = isset($batch['previewImage']['baseUrl']) ? $batch['previewImage']['baseUrl'] : '';
                            $imageKey = isset($batch['previewImage']['key']) ? $batch['previewImage']['key'] : 'coomingsoon.png';
                            $exam = isset($batch['details']['exam']) ? implode(", ", $batch['details']['exam']) : 'N/A';
                            $imageUrl = $imageBaseUrl ? $imageBaseUrl . $imageKey : 'coomingsoon.png';

                            echo "
                            <div class='batch-card'>
                                <img src='$imageUrl' alt='$name'>
                                <h3>$name</h3>
                                <p>Category: $exam</p>
                                <a href='subject.php?batch_id=$id'>View Subjects</a>
                            </div>
                            ";
                        }
                    }
                }
            } else {
                echo "<p>Failed to load batch data.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
