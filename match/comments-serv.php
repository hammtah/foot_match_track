<?php
session_start();
require '../includes/db.php';

global $bd;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $id_match = $_POST['id_match'] ;
    $comment = $_POST["comment"] ;
    $id_user = $_SESSION['id']; 
    echo("idmatch: " . $id_match);
    if (isset($id_match) && isset($comment) && isset($id_user) && !empty($comment)) {
        try {
            $sql = "INSERT INTO comments (id_match, comment, id_user, date_comment) VALUES (:id_match, :comment, :id_user, CURRENT_TIMESTAMP)";
            $stmt = $bd->prepare($sql);
            $stmt->execute([
                'id_match' => $id_match,
                'comment' => $comment,
                'id_user' => $id_user
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    }
}else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $id_match = $_GET['id_match'];
    if ($id_match) {
        try {
            $sql = "SELECT comments.id_comment, comments.comment, users.nom, comments.date_comment, comments.likes FROM comments 
                    JOIN users ON comments.id_user = users.id 
                    WHERE comments.id_match = :id_match ORDER BY comments.date_comment DESC";
            $stmt = $bd->prepare($sql);
            $stmt->execute(['id_match' => $id_match]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'comments' => $comments]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    }

}


?>