PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS teams (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    team_name TEXT NOT NULL,
    founded_year INTEGER NOT NULL,
    country TEXT NOT NULL,
    city TEXT NOT NULL,
    logo_path TEXT,
    primary_color TEXT,
    secondary_color TEXT,
    home_stadium TEXT,
    stadium_capacity INTEGER,
    website TEXT,
    email TEXT,
    phone TEXT,
    address TEXT,
    head_coach TEXT,
    assistant_coach TEXT,
    team_manager TEXT,
    physiotherapist TEXT,
    history TEXT
);

CREATE TABLE IF NOT EXISTS tournaments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tournament_name TEXT NOT NULL,
    format TEXT NOT NULL,
    start_date TEXT NOT NULL,
    end_date TEXT NOT NULL,
    location TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'upcoming',
    num_teams INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tournament_teams (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tournament_id INTEGER NOT NULL,
    team_id INTEGER NOT NULL,
    group_name TEXT,
    position INTEGER,
    FOREIGN KEY (tournament_id) REFERENCES tournaments(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    UNIQUE (tournament_id, team_id)
);

CREATE TABLE IF NOT EXISTS countries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    country_name TEXT NOT NULL,
    alpha2_code TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS player_position (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    position_name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS players (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    birth_date TEXT NOT NULL,
    nationality TEXT NOT NULL,
    birth_place TEXT,
    email TEXT,
    phone TEXT,
    social_media TEXT,
    position TEXT NOT NULL DEFAULT '',
    secondary_position TEXT,
    jersey_number INTEGER,
    preferred_foot TEXT,
    team TEXT,
    goals INTEGER DEFAULT 0,
    assists INTEGER DEFAULT 0,
    appearances INTEGER DEFAULT 0,
    height REAL,
    weight REAL,
    bmi REAL,
    fitness_level INTEGER,
    medical_conditions TEXT,
    contract_start TEXT,
    contract_end TEXT,
    agent_name TEXT,
    agent_contact TEXT,
    release_clause REAL,
    market_value REAL,
    contract_notes TEXT,
    player_photo TEXT,
    id_country INTEGER,
    id_position INTEGER,
    FOREIGN KEY (id_position) REFERENCES player_position(id),
    FOREIGN KEY (id_country) REFERENCES countries(id)
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    email TEXT,
    password TEXT,
    role TEXT,
    tournament_id INTEGER,
    FOREIGN KEY (tournament_id) REFERENCES tournaments(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS _match (
    id_match INTEGER PRIMARY KEY AUTOINCREMENT,
    tournament_id INTEGER NOT NULL DEFAULT 0,
    Nombre_spectateur INTEGER,
    date_match TEXT NOT NULL,
    time_match TEXT NOT NULL,
    Nom_match TEXT,
    id_equipe1 INTEGER NOT NULL,
    id_equipe2 INTEGER NOT NULL,
    staduim TEXT,
    id_referee_main INTEGER,
    id_referee_assistant1 INTEGER,
    id_referee_assistant2 INTEGER,
    FOREIGN KEY (id_equipe1) REFERENCES teams(id),
    FOREIGN KEY (id_equipe2) REFERENCES teams(id)
);

CREATE TABLE IF NOT EXISTS but (
    id_but INTEGER PRIMARY KEY AUTOINCREMENT,
    id_match INTEGER NOT NULL,
    id_team INTEGER NOT NULL,
    id_buteur INTEGER NOT NULL,
    id_assisteur INTEGER,
    minute INTEGER,
    goal_type TEXT DEFAULT 'normal',
    FOREIGN KEY (id_match) REFERENCES _match(id_match),
    FOREIGN KEY (id_team) REFERENCES teams(id),
    FOREIGN KEY (id_buteur) REFERENCES players(id),
    FOREIGN KEY (id_assisteur) REFERENCES players(id)
);

CREATE TABLE IF NOT EXISTS goals (
    goal_id INTEGER PRIMARY KEY AUTOINCREMENT,
    match_id INTEGER NOT NULL,
    team_id INTEGER NOT NULL,
    player_id INTEGER NOT NULL,
    assist_player_id INTEGER,
    goal_time INTEGER NOT NULL,
    goal_type TEXT DEFAULT 'normal',
    FOREIGN KEY (match_id) REFERENCES _match(id_match) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (assist_player_id) REFERENCES players(id)
);

CREATE TABLE IF NOT EXISTS match_stats (
    stat_id INTEGER PRIMARY KEY AUTOINCREMENT,
    match_id INTEGER NOT NULL,
    home_possession INTEGER DEFAULT 50,
    away_possession INTEGER DEFAULT 50,
    home_shots INTEGER DEFAULT 0,
    away_shots INTEGER DEFAULT 0,
    home_shots_target INTEGER DEFAULT 0,
    away_shots_target INTEGER DEFAULT 0,
    home_corners INTEGER DEFAULT 0,
    away_corners INTEGER DEFAULT 0,
    home_fouls INTEGER DEFAULT 0,
    away_fouls INTEGER DEFAULT 0,
    home_passes INTEGER DEFAULT 0,
    away_passes INTEGER DEFAULT 0,
    FOREIGN KEY (match_id) REFERENCES _match(id_match) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS composer (
    id_composer INTEGER PRIMARY KEY AUTOINCREMENT,
    id_player INTEGER,
    id_team INTEGER,
    id_position INTEGER,
    d_debut TEXT,
    d_fin TEXT,
    num_maillot INTEGER,
    FOREIGN KEY (id_position) REFERENCES player_position(id),
    FOREIGN KEY (id_team) REFERENCES teams(id),
    FOREIGN KEY (id_player) REFERENCES players(id)
);

CREATE TABLE IF NOT EXISTS refree (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    date_de_naissance TEXT NOT NULL,
    status TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS stadium (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    ville INTEGER NOT NULL,
    date_de_creation TEXT NOT NULL,
    status TEXT NOT NULL,
    id_team INTEGER NOT NULL,
    capacity INTEGER,
    FOREIGN KEY (id_team) REFERENCES teams(id)
);

CREATE TABLE IF NOT EXISTS staff (
    id_staff INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    prenom TEXT,
    role TEXT,
    photo TEXT,
    id_team INTEGER,
    id_country INTEGER,
    d_debut TEXT,
    d_fin TEXT,
    FOREIGN KEY (id_team) REFERENCES teams(id),
    FOREIGN KEY (id_country) REFERENCES countries(id)
);

CREATE TABLE IF NOT EXISTS comments (
    id_comment INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER,
    id_match INTEGER,
    comment TEXT,
    likes INTEGER DEFAULT 0,
    date_comment TEXT,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_match) REFERENCES _match(id_match)
);

CREATE TABLE IF NOT EXISTS notif (
    id_notif INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER,
    msg TEXT,
    date_notif TEXT,
    is_read TEXT DEFAULT 'n',
    event_id INTEGER,
    event_type TEXT,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS follow (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER,
    event_id INTEGER,
    event_type TEXT,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS votes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    match_id INTEGER NOT NULL,
    team_id INTEGER,
    vote_time TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (match_id) REFERENCES _match(id_match),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    UNIQUE (user_id, match_id)
);

CREATE TABLE IF NOT EXISTS publication (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_admin INTEGER,
    image_path TEXT,
    titre TEXT,
    contenue TEXT,
    FOREIGN KEY (id_admin) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS match_lineups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    match_id INTEGER NOT NULL,
    team_id INTEGER NOT NULL,
    formation TEXT,
    FOREIGN KEY (match_id) REFERENCES _match(id_match),
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

CREATE TABLE IF NOT EXISTS lineup_positions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lineup_id INTEGER NOT NULL,
    position_id INTEGER NOT NULL,
    player_id INTEGER NOT NULL,
    FOREIGN KEY (lineup_id) REFERENCES match_lineups(id) ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES player_position(id),
    FOREIGN KEY (player_id) REFERENCES players(id)
);

CREATE VIEW IF NOT EXISTS latest_matches AS
SELECT DISTINCT
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
WHERE m.date_match < date('now')
   OR (m.date_match = date('now') AND m.time_match < time(datetime('now', '+3 hours')))
ORDER BY m.date_match DESC, m.time_match ASC;

CREATE VIEW IF NOT EXISTS score AS
SELECT b.id_match, b.id_team, COUNT(b.id_match) AS butes
FROM latest_matches l
JOIN but b ON l.id_match = b.id_match
GROUP BY b.id_team, b.id_match;

CREATE VIEW IF NOT EXISTS match_info AS
SELECT
    l.Nom_match,
    l.date_match,
    l.team1_name,
    l.team1_logo,
    l.team2_name,
    l.team2_logo,
    l.id_match,
    l.id_team1,
    l.id_team2,
    s1.butes AS butes_team1,
    s2.butes AS butes_team2
FROM latest_matches l
JOIN score s1 ON l.id_match = s1.id_match AND l.id_team1 = s1.id_team
JOIN score s2 ON l.id_match = s2.id_match AND l.id_team2 = s2.id_team;
