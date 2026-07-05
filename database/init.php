<?php

function initDatabase(PDO $bd): void
{
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $bd->exec($schema);

    $count = (int) $bd->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count > 0) {
        return;
    }

    importMysqlSeed($bd, __DIR__ . '/../my_db.sql');
    ensureUpcomingMatches($bd);
}

function ensureUpcomingMatches(PDO $bd): void
{
    $count = (int) $bd->query(
        "SELECT COUNT(*) FROM _match WHERE datetime(date_match || ' ' || time_match) > datetime('now')"
    )->fetchColumn();

    if ($count > 0) {
        return;
    }

    $upcoming = [
        ['id' => 30, 'offset' => '+1 day',  'time' => '20:30:00', 'name' => 'Raja vs Wydad'],
        ['id' => 25, 'offset' => '+2 days', 'time' => '19:00:00', 'name' => 'Derbi du Nord'],
        ['id' => 32, 'offset' => '+4 days', 'time' => '17:00:00', 'name' => 'RS Berkane vs Mouloudia Oujda'],
        ['id' => 33, 'offset' => '+6 days', 'time' => '21:00:00', 'name' => 'Rapide Oued Zem vs Racing'],
        ['id' => 34, 'offset' => '+8 days', 'time' => '18:00:00', 'name' => 'Mouloudia Oujda vs AS Sale'],
    ];

    $stmt = $bd->prepare(
        "UPDATE _match
         SET date_match = date('now', :offset),
             time_match = :time,
             Nom_match = :name,
             tournament_id = CASE WHEN tournament_id = 0 THEN 1 ELSE tournament_id END
         WHERE id_match = :id"
    );

    foreach ($upcoming as $match) {
        $stmt->execute([
            ':offset' => $match['offset'],
            ':time' => $match['time'],
            ':name' => $match['name'],
            ':id' => $match['id'],
        ]);
    }

    $bd->exec("UPDATE tournaments SET end_date = date('now', '+6 months'), status = 'active' WHERE id = 1");
}

function importMysqlSeed(PDO $bd, string $dumpPath): void
{
    if (!file_exists($dumpPath)) {
        seedMinimalData($bd);
        return;
    }

    $sql = file_get_contents($dumpPath);
    preg_match_all('/INSERT INTO `[^`]+`[^;]+;/s', $sql, $matches);

    $order = [
        'countries', 'player_position', 'teams', 'tournaments', 'players',
        'users', '_match', 'refree', 'stadium', 'staff', 'composer',
        'but', 'goals', 'match_stats', 'tournament_teams',
    ];

    $byTable = [];
    foreach ($matches[0] as $statement) {
        $statement = str_replace('`', '', trim($statement));
        if (preg_match('/INSERT INTO (\w+)/i', $statement, $m)) {
            $byTable[strtolower($m[1])][] = $statement;
        }
    }

    $bd->exec('PRAGMA foreign_keys = OFF');

    foreach ($order as $table) {
        foreach ($byTable[$table] ?? [] as $statement) {
            try {
                $bd->exec($statement);
            } catch (PDOException $e) {
                // Skip individual rows that fail due to dump quirks
            }
        }
    }

    foreach ($byTable as $table => $statements) {
        if (in_array($table, $order, true)) {
            continue;
        }
        foreach ($statements as $statement) {
            try {
                $bd->exec($statement);
            } catch (PDOException $e) {
            }
        }
    }

    $bd->exec('PRAGMA foreign_keys = ON');
}

function seedMinimalData(PDO $bd): void
{
    $bd->exec("INSERT INTO player_position (id, position_name) VALUES
        (1, 'Goalkeeper'), (2, 'Defender'), (3, 'Midfielder'), (4, 'Attacker')");

    $bd->exec("INSERT INTO users (id, nom, email, password, role) VALUES
        (1, 'Alice Dupont', 'alice@example.com', '1', 'g'),
        (2, 'Thomas Bernard', 'thomas@example.com', 'motdepasse3', 't')");

    $bd->exec("INSERT INTO tournaments (id, tournament_name, format, start_date, end_date, location, status, num_teams) VALUES
        (1, 'BOTOLA', 'LEAGUE', '2024-09-01', '2025-07-20', 'MOROCCO', 'active', 20)");
}
