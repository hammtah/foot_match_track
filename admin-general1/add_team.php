<?php
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = $bd;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Prepare and bind
        $sql = "INSERT INTO teams (team_name, founded_year, country, city, logo_path, primary_color, secondary_color, home_stadium, stadium_capacity, website, email, phone, address, head_coach, assistant_coach, team_manager, physiotherapist, history) 
                VALUES (:team_name, :founded_year, :country, :city, :logo_path, :primary_color, :secondary_color, :home_stadium, :stadium_capacity, :website, :email, :phone, :address, :head_coach, :assistant_coach, :team_manager, :physiotherapist, :history)";
        
        $stmt = $pdo->prepare($sql);

        // Handle logo upload
        $logo_path = "";
        if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] == 0) {
            $target_dir = "uploads/teams/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["team_logo"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check if image file is a actual image
            $check = getimagesize($_FILES["team_logo"]["tmp_name"]);
            if ($check !== false) {
                // Generate unique filename
                $logo_path = $target_dir . uniqid() . "." . $imageFileType;
                if (!move_uploaded_file($_FILES["team_logo"]["tmp_name"], $logo_path)) {
                    $logo_path = "";
                }
            }
        }

        // Set parameters and execute
        $params = [
            ':team_name' => $_POST['team_name'],
            ':founded_year' => $_POST['founded_year'],
            ':country' => $_POST['country'],
            ':city' => $_POST['city'],
            ':logo_path' => $logo_path,
            ':primary_color' => $_POST['primary_color'],
            ':secondary_color' => $_POST['secondary_color'],
            ':home_stadium' => $_POST['home_stadium'],
            ':stadium_capacity' => $_POST['stadium_capacity'],
            ':website' => $_POST['website'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':address' => $_POST['address'],
            ':head_coach' => $_POST['head_coach'],
            ':assistant_coach' => $_POST['assistant_coach'],
            ':team_manager' => $_POST['team_manager'],
            ':physiotherapist' => $_POST['physiotherapist'],
            ':history' => $_POST['history']
        ];

        if ($stmt->execute($params)) {
            echo "Team added successfully!";
        } else {
            echo "Error: Could not execute the query.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>