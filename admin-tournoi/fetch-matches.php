<?php

//fetch maches based on a tournament id , 
// to use this just call it id the front end with 
// the tournament id as a parameter


require '../includes/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tournament_id = $_GET['tournament_id'];

    
    if (!is_numeric($tournament_id)) {
        echo json_encode(['error' => 'Invalid tournament ID']);
        exit;
    }

    // fetch matches for the given tournamnt id
    $sql = "SELECT m.id_match, m.date_match, m.time_match, m.Nom_match, 
                   t1.team_name AS home_team, t2.team_name AS away_team, 
                   m.Nombre_spectateur, m.tournament_id , m.staduim , t1.logo_path AS home_team_logo, t2.logo_path AS away_team_logo,
                   t1.id AS home_team_id, t2.id AS away_team_id,
                   CASE 
                       WHEN datetime(m.date_match || ' ' || m.time_match) > datetime('now') THEN 'scheduled'
                       WHEN datetime(m.date_match || ' ' || m.time_match) <= datetime('now')
                            AND datetime(m.date_match || ' ' || m.time_match) >= datetime('now', '-2 hours') THEN 'in-progress'
                       ELSE 'completed'
                   END AS status
            FROM _match m
            JOIN teams t1 ON m.id_equipe1 = t1.id
            JOIN teams t2 ON m.id_equipe2 = t2.id
            WHERE m.tournament_id = :tournament_id";
    $stmt = $bd->prepare($sql);
    $stmt->execute(['tournament_id' => $tournament_id]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($matches);
}
// echo "<pre>";
// print_r($matches);
// echo "</pre>";
?>