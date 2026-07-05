<?php
$successMessage = '';
$errorMessage = '';
$showSuccessPopup = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        require_once __DIR__ . '/../includes/db.php';
        $pdo = $bd;
        $sql = "INSERT INTO players (first_name, last_name, birth_date, nationality, position, secondary_position, 
                jersey_number, preferred_foot, team, goals, assists, appearances, height, weight, bmi, 
                fitness_level, medical_conditions, contract_start, contract_end, agent_name, agent_contact, 
                release_clause, market_value, contract_notes) 
                VALUES (:first_name, :last_name, :birth_date, :nationality, :position, :secondary_position, 
                :jersey_number, :preferred_foot, :team, :goals, :assists, :appearances, :height, :weight, 
                :bmi, :fitness_level, :medical_conditions, :contract_start, :contract_end, :agent_name, 
                :agent_contact, :release_clause, :market_value, :contract_notes)";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':first_name', $_POST['first_name']);
        $stmt->bindParam(':last_name', $_POST['last_name']);
        $stmt->bindParam(':birth_date', $_POST['birth_date']);
        $stmt->bindParam(':nationality', $_POST['nationality']);
        $stmt->bindParam(':position', $_POST['position']);
        $stmt->bindParam(':secondary_position', $_POST['secondary_position']);
        $stmt->bindParam(':jersey_number', $_POST['jersey_number'], PDO::PARAM_INT);
        $stmt->bindParam(':preferred_foot', $_POST['preferred_foot']);
        $stmt->bindParam(':team', $_POST['team']);
        $stmt->bindParam(':goals', $_POST['goals'], PDO::PARAM_INT);
        $stmt->bindParam(':assists', $_POST['assists'], PDO::PARAM_INT);
        $stmt->bindParam(':appearances', $_POST['appearances'], PDO::PARAM_INT);
        $stmt->bindParam(':height', $_POST['height'], PDO::PARAM_STR);
        $stmt->bindParam(':weight', $_POST['weight'], PDO::PARAM_STR);
        $stmt->bindParam(':bmi', $_POST['bmi'], PDO::PARAM_STR);
        $stmt->bindParam(':fitness_level', $_POST['fitness_level'], PDO::PARAM_INT);
        $stmt->bindParam(':medical_conditions', $_POST['medical_conditions']);
        $stmt->bindParam(':contract_start', $_POST['contract_start']);
        $stmt->bindParam(':contract_end', $_POST['contract_end']);
        $stmt->bindParam(':agent_name', $_POST['agent_name']);
        $stmt->bindParam(':agent_contact', $_POST['agent_contact']);
        $stmt->bindParam(':release_clause', $_POST['release_clause'], PDO::PARAM_STR);
        $stmt->bindParam(':market_value', $_POST['market_value'], PDO::PARAM_STR);
        $stmt->bindParam(':contract_notes', $_POST['contract_notes']);
        
        if ($stmt->execute()) {
            $successMessage = "Player added successfully!";
            $showSuccessPopup = true;
        }
    } catch (PDOException $e) {
        $errorMessage = "Database error: " . $e->getMessage();
    }
}
?>