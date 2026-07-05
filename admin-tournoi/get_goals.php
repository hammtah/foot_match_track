<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $match_id = isset($_GET['match_id']) ? intval($_GET['match_id']) : 0;
    
    if (!$match_id) {
        echo json_encode(['success' => false, 'error' => 'Invalid match ID']);
        exit;
    }
    
    try {
        // Query to get all goals for this match with player names
        // Adjusted for your actual table structure
        $sql = "SELECT b.id_but as goal_id, b.id_match as match_id, 
                b.id_team as team_id, b.id_buteur as player_id, 
                b.id_assisteur as assist_player_id, b.minute as goal_time, 
                b.goal_type,
                (scorer.first_name || ' ' || scorer.last_name) AS scorer_name,
                (assister.first_name || ' ' || assister.last_name) AS assist_name
                FROM but b
                LEFT JOIN players scorer ON b.id_buteur = scorer.id
                LEFT JOIN players assister ON b.id_assisteur = assister.id
                WHERE b.id_match = :match_id
                ORDER BY b.minute ASC";
                
        $stmt = $bd->prepare($sql);
        $stmt->execute(['match_id' => $match_id]);
        $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'goals' => $goals]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>