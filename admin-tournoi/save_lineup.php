<?php
header('Content-Type: application/json');

// Database configuration
require_once '../includes/db.php';

try {
    $conn = $bd;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get data from the request
    $data = json_decode(file_get_contents("php://input"));

    // Validate data
    if (!isset($data->match_id) || !isset($data->home_team_id) || !isset($data->away_team_id) 
        || !isset($data->formation) || !isset($data->home_lineup) || !isset($data->away_lineup)) {
        throw new Exception("Missing required data.");
    }

    // Check if lineups are empty
    if (empty((array)$data->home_lineup) || empty((array)$data->away_lineup)) {
        throw new Exception("Lineups cannot be empty.");
    }

    // Begin transaction for atomicity
    $conn->beginTransaction();

    // Helper function to save a team's lineup
    function saveTeamLineup($conn, $match_id, $team_id, $formation, $lineup) {
        // Check if lineup already exists for this match and team
        $checkStmt = $conn->prepare("SELECT id FROM match_lineups WHERE match_id = :match_id AND team_id = :team_id");
        $checkStmt->execute([':match_id' => $match_id, ':team_id' => $team_id]);
        
        if ($checkStmt->rowCount() > 0) {
            // Delete existing lineup
            $lineupId = $checkStmt->fetchColumn();
            $deleteStmt = $conn->prepare("DELETE FROM lineup_positions WHERE lineup_id = :lineup_id");
            $deleteStmt->execute([':lineup_id' => $lineupId]);
            
            $deleteLineupStmt = $conn->prepare("DELETE FROM match_lineups WHERE id = :id");
            $deleteLineupStmt->execute([':id' => $lineupId]);
        }
        
        // Insert new lineup
        $stmt = $conn->prepare("INSERT INTO match_lineups (match_id, team_id, formation) VALUES (:match_id, :team_id, :formation)");
        $stmt->execute([
            ':match_id' => $match_id,
            ':team_id' => $team_id,
            ':formation' => $formation
        ]);

        $lineup_id = $conn->lastInsertId();

        // Insert lineup positions
        $posStmt = $conn->prepare("INSERT INTO lineup_positions (lineup_id, position_id, player_id) VALUES (:lineup_id, :position_id, :player_id)");
        
        foreach ($lineup as $position_id => $player_id) {
            $posStmt->execute([
                ':lineup_id' => $lineup_id, 
                ':position_id' => $position_id, 
                ':player_id' => $player_id
            ]);
        }
        
        return true;
    }

    // Save home team lineup
    saveTeamLineup($conn, $data->match_id, $data->home_team_id, $data->formation, (array)$data->home_lineup);

    // Save away team lineup
    saveTeamLineup($conn, $data->match_id, $data->away_team_id, $data->formation, (array)$data->away_lineup);

    // Commit transaction
    $conn->commit();

    // Return success message
    echo json_encode(['success' => true, 'message' => 'Lineup saved successfully.']);

} catch (Exception $e) {
    // Roll back transaction on error
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Return error message
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null; // Close connection
?>