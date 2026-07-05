<?php

$dbPath = __DIR__ . '/../database/football.db';
$needsInit = !file_exists($dbPath);

try {
    $bd = new PDO('sqlite:' . $dbPath);
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $bd->exec('PRAGMA foreign_keys = ON');

    if ($needsInit) {
        require_once __DIR__ . '/../database/init.php';
        initDatabase($bd);
    } else {
        require_once __DIR__ . '/../database/init.php';
        ensureUpcomingMatches($bd);
    }
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

function isFollowing($id_event, $id_user, $event_type)
{
    global $bd;

    $sql = 'SELECT id FROM follow WHERE event_id = :id_event AND id_user = :id_user AND event_type = :event_type';
    $stmt = $bd->prepare($sql);
    $stmt->execute(['id_event' => $id_event, 'id_user' => $id_user, 'event_type' => $event_type]);

    return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}
