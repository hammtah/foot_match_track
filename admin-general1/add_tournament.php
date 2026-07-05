<?php
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = $bd;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Prepare and bind
        $sql = "INSERT INTO tournaments (tournament_name, tournament_type, start_date, end_date, 
                location_city, location_country, team_count, status, logo_path, description) 
                VALUES (:tournament_name, :tournament_type, :start_date, :end_date, 
                :location_city, :location_country, :team_count, :status, :logo_path, :description)";
        
        $stmt = $pdo->prepare($sql);

        // Handle logo upload
        $logo_path = "";
        if (isset($_FILES['tournament_logo']) && $_FILES['tournament_logo']['error'] == 0) {
            $target_dir = "uploads/tournaments/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["tournament_logo"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check if image file is actual image
            $check = getimagesize($_FILES["tournament_logo"]["tmp_name"]);
            if ($check !== false) {
                // Generate unique filename
                $logo_path = $target_dir . uniqid() . "." . $imageFileType;
                if (!move_uploaded_file($_FILES["tournament_logo"]["tmp_name"], $logo_path)) {
                    $logo_path = "";
                }
            }
        }

        // Set parameters and execute
        $params = [
            ':tournament_name' => $_POST['tournament_name'],
            ':tournament_type' => $_POST['tournament_type'],
            ':start_date' => $_POST['start_date'],
            ':end_date' => $_POST['end_date'],
            ':location_city' => $_POST['location_city'],
            ':location_country' => $_POST['location_country'],
            ':team_count' => $_POST['team_count'],
            ':status' => $_POST['status'],
            ':logo_path' => $logo_path,
            ':description' => $_POST['description']
        ];

        if ($stmt->execute($params)) {
            $tournament_id = $pdo->lastInsertId();
            
            // Add teams to tournament if any
            if (isset($_POST['teams']) && is_array($_POST['teams'])) {
                $teamSql = "INSERT INTO tournament_teams (tournament_id, team_id) VALUES (:tournament_id, :team_id)";
                $teamStmt = $pdo->prepare($teamSql);
                
                foreach ($_POST['teams'] as $team_id) {
                    $teamStmt->execute([':tournament_id' => $tournament_id, ':team_id' => $team_id]);
                }
            }
            
            header("Location: tour.php?success=1");
            exit;
        } else {
            echo "Error: Could not execute the query.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>