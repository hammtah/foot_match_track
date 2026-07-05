<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $team_id = isset($_GET['team_id']) ? intval($_GET['team_id']) : 0;
    
    if (!$team_id) {
        echo json_encode(['error' => 'Invalid team ID']);
        exit;
    }

    $sql = "SELECT p.id, c.num_maillot as number, (p.first_name || ' ' || p.last_name) AS full_name, pos.position_name as position 
            FROM players p
            INNER JOIN composer c ON p.id = c.id_player
            INNER JOIN player_position pos ON c.id_position = pos.id
            WHERE c.id_team = :team_id
            ORDER BY c.num_maillot ASC";
    

    $stmt = $bd->prepare($sql);
    $stmt->execute(['team_id' => $team_id]);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($players);
}
?>