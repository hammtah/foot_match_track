<?php
session_start();
require '../includes/db.php';

global $bd;

// Follow a team
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if the event type is "team"
    if ($_POST["event_type"] === "team") {
        $id_team = $_POST['id_event']; // Team ID
        $event_type = $_POST["event_type"]; // Event type
        $id_user = $_SESSION['id']; // User ID from session

        // Validate required fields and check if the user is already following the team
        if (isset($id_team) && isset($event_type) && isset($id_user) && (isFollowing($id_team, $id_user, $event_type) === false)) {
            try {
                // Insert a new follow record into the database
                $sql = "INSERT INTO follow (event_id, id_user, event_type) VALUES (:id_event, :id_user, :event_type)";
                $stmt = $bd->prepare($sql);
                $stmt->execute([
                    'id_event' => $id_team,
                    'event_type' => $event_type,
                    'id_user' => $id_user
                ]);

                // Follow matches where the team is involved
                $match_info = getLatestUnplayedMatch($id_team);
                // if ($match_info) {
                //     $sql = "INSERT INTO follow (event_id, id_user, event_type) VALUES (:id_event, :id_user, 'match')";
                //     $stmt = $bd->prepare($sql);
                //     $stmt->execute([
                //         'id_event' => $match_info['id_match'],
                //         'id_user' => $id_user
                //     ]);
                // }

                // Insert a notification for match followers
                $sql = "INSERT INTO notif (msg, event_id, event_type, date_notif, id_user) VALUES (:msg, :event_id, 'match', CURRENT_TIMESTAMP, :id_user)";
                $stmt = $bd->prepare($sql);
                $msg = "{$match_info['team1_name']} vs. {$match_info['team2_name']} kicks off on {$match_info['date_match']} at {$match_info['time_match']}!";
                $stmt->execute([
                    ':msg' => $msg,
                    ':event_id' => $match_info['id_match'],
                    ':id_user' => $id_user
                ]);

                // Return success response
                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                // Handle database errors
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
            }
        } else {
            // Return error if required fields are missing or the user is already following
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        }
    }
}



















//follow matche for users who are following match teams
function followMatchForTeamFollower($id_match, $id_team1, $id_team2)
{
    global $bd;
    // Fetch users who follow either team and does not follow the match
    $sql = "SELECT follow.id_user from follow 
                WHERE ((follow.event_id = :team1_id OR follow.event_id = :team2_id) AND follow.event_type = 'team' )
                and follow.id_user not in (
                            select follow.id_user from follow 
                            where follow.event_type = 'match' and follow.event_id= :match_id
                            )";
    $stmt = $bd->prepare($sql);
    $stmt->execute([':team1_id' => $id_team1, ':team2_id' => $id_team2, ':match_id' => $id_match]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //for each uer, follow the match
    foreach ($users as $user) {
        $sql = "INSERT INTO follow (id_user, event_id, event_type) VALUES (:id_user, :event_id, 'match')";
        $stmt = $bd->prepare($sql);
        $stmt->execute([
            ':id_user' => $user['id_user'],
            ':event_id' => $id_match
        ]);
    }
}



//get the latest unplayed match info for the team
function getLatestUnplayedMatch($id_team)
{
    global $bd;
    $sql = "SELECT 
            m.id_match,
            m.Nom_match,
            m.date_match,
            m.time_match,
            t1.id AS id_team1, 
            t2.id AS id_team2, 
            t1.team_name AS team1_name,
            t1.logo_path AS team1_logo,
            t2.team_name AS team2_name,
            t2.logo_path AS team2_logo
        FROM _match m
        JOIN teams t1 ON m.id_equipe1 = t1.id
        JOIN teams t2 ON m.id_equipe2 = t2.id
        WHERE (t1.id = :id_team OR t2.id = :id_team)
        AND (
            m.date_match > CURRENT_DATE OR 
            (m.date_match = CURRENT_DATE AND m.time_match > CURRENT_TIME )
        )
        ORDER BY m.date_match DESC, m.time_match ASC
    ";
    $stmt = $bd->prepare($sql);
    $stmt->execute([
        ':id_team' => $id_team
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
} 
?>