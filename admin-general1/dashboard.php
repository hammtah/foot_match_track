<?php
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = $bd;
    
    // Fetch teams
    $teamsStmt = $pdo->query("SELECT * FROM teams ORDER BY team_name");
    $teams = $teamsStmt->fetchAll();
    
    // Fetch players
    $playersStmt = $pdo->query("SELECT * FROM players ORDER BY last_name, first_name");
    $players = $playersStmt->fetchAll();
    
    // Count items for dashboard stats
    $teamCount = count($teams);
    $playerCount = count($players);
    $matchesStmt = $pdo->query("SELECT COUNT(*) AS count FROM _match");
    $matchCount = $matchesStmt->fetch()['count'];
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Management Dashboard</title>
    <link rel="stylesheet" href="team_css.css">
    <link rel="stylesheet" href="player_css.css">
    <link rel="stylesheet" href="dashboard_css.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="3" y1="9" x2="21" y2="9"></line>
                    <line x1="9" y1="21" x2="9" y2="9"></line>
                </svg>
            </div>
            <h1>Football Management Dashboard</h1>
        </div>

        <!-- Dashboard Stats -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $teamCount; ?></div>
                <div class="stat-label">Teams</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $playerCount; ?></div>
                <div class="stat-label">Players</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $matchCount; ?></div>
                <div class="stat-label">Matches</div>
            </div>
        </div>

        <div class="tabs">
            <div class="tab active" data-tab="teams">Teams</div>
            <div class="tab" data-tab="players">Players</div>
        </div>

        <!-- Teams Tab -->
        <div class="tab-content active" id="teams">
            <div class="add-new">
                <a href="team.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-small">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add New Team
                </a>
            </div>

            <div class="search-container">
                <input type="text" id="team-search" placeholder="Search teams...">
                <button id="team-search-btn" class="search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>

            <div class="view-toggle">
                <button class="toggle-btn active" id="team-grid-view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </button>
                <button class="toggle-btn" id="team-list-view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div id="team-grid-container" class="card-grid">
                <?php if (isset($teams) && count($teams) > 0): ?>
                    <?php foreach ($teams as $team): ?>
                        <div class="card team-card">
                            <div class="card-team-info">
                                <?php if ($team['logo_path']): ?>
                                    <img src="../uploads/logos/<?php echo htmlspecialchars($team['logo_path']); ?>" alt="<?php echo htmlspecialchars($team['team_name']); ?>" class="team-logo">
                                <?php else: ?>
                                    <div class="logo-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <h3 class="card-team-name"><?php echo htmlspecialchars($team['team_name']); ?></h3>
                                <p class="card-team-location"><?php echo htmlspecialchars($team['city'] . ', ' . $team['country']); ?></p>
                            </div>
                            <div class="card-footer">
                                <span class="founded-year">Founded: <?php echo htmlspecialchars($team['founded_year']); ?></span>
                                <div class="action-buttons">
                                    <a href="view_team.php?id=<?php echo $team['id']; ?>" class="action-btn" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="edit_team.php?id=<?php echo $team['id']; ?>" class="action-btn" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <a href="delete_team.php?id=<?php echo $team['id']; ?>" class="action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this team?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <h3>No teams found</h3>
                        <p>Start by adding a new team</p>
                    </div>
                <?php endif; ?>
            </div>

            <div id="team-list-container" class="table-container" style="display: none;">
                <?php if (isset($teams) && count($teams) > 0): ?>
                    <table class="table-view">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Country</th>
                                <th>City</th>
                                <th>Stadium</th>
                                <th>Founded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($teams as $team): ?>
                                <tr>
                                    <td class="team-name-cell">
                                        <?php if ($team['logo_path']): ?>
                                            <img src="../uploads/logos/<?php echo htmlspecialchars($team['logo_path']); ?>" alt="Logo" class="table-logo">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($team['team_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($team['country']); ?></td>
                                    <td><?php echo htmlspecialchars($team['city']); ?></td>
                                    <td><?php echo htmlspecialchars($team['home_stadium']); ?></td>
                                    <td><?php echo htmlspecialchars($team['founded_year']); ?></td>
                                    <td class="action-cell">
                                        <a href="view_team.php?id=<?php echo $team['id']; ?>" class="action-btn" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <a href="edit_team.php?id=<?php echo $team['id']; ?>" class="action-btn" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <a href="delete_team.php?id=<?php echo $team['id']; ?>" class="action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this team?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No teams found</h3>
                        <p>Start by adding a new team</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Players Tab -->
        <div class="tab-content" id="players">
            <div class="add-new">
                <a href="player.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-small">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add New Player
                </a>
            </div>

            <div class="search-container">
                <input type="text" id="player-search" placeholder="Search players...">
                <button id="player-search-btn" class="search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>

            <div class="view-toggle">
                <button class="toggle-btn active" id="player-grid-view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </button>
                <button class="toggle-btn" id="player-list-view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div id="player-grid-container" class="card-grid">
                <?php if (isset($players) && count($players) > 0): ?>
                    <?php foreach ($players as $player): ?>
                        <div class="card player-card">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo htmlspecialchars($player['first_name'] . ' ' . $player['last_name']); ?></h3>
                                <span class="position-badge"><?php echo htmlspecialchars($player['position']); ?></span>
                            </div>
                            <div class="card-body">
                                <div class="player-info">
                                    <?php if ($player['player_photo']): ?>
                                        <img src="../uploads/players/<?php echo htmlspecialchars($player['player_photo']); ?>" alt="Player" class="avatar">
                                    <?php else: ?>
                                        <div class="avatar-placeholder">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="player-details">
                                        <p><strong>Team:</strong> <?php echo htmlspecialchars($player['team'] ?: 'Not assigned'); ?></p>
                                        <p><strong>Age:</strong> <?php echo date_diff(date_create($player['birth_date']), date_create('today'))->y; ?> years</p>
                                        <p><strong>Nationality:</strong> <?php echo htmlspecialchars($player['nationality']); ?></p>
                                    </div>
                                </div>
                                <div class="player-stats">
                                    <div class="stat">
                                        <span class="stat-value"><?php echo htmlspecialchars($player['goals'] ?: '0'); ?></span>
                                        <span class="stat-label">Goals</span>
                                    </div>
                                    <div class="stat">
                                        <span class="stat-value"><?php echo htmlspecialchars($player['assists'] ?: '0'); ?></span>
                                        <span class="stat-label">Assists</span>
                                    </div>
                                    <div class="stat">
                                        <span class="stat-value"><?php echo htmlspecialchars($player['appearances'] ?: '0'); ?></span>
                                        <span class="stat-label">Apps</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="jersey-number"><?php echo $player['jersey_number'] ? '#' . $player['jersey_number'] : ''; ?></span>
                                <div class="action-buttons">
                                    <a href="view_player.php?id=<?php echo $player['id']; ?>" class="action-btn" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="edit_player.php?id=<?php echo $player['id']; ?>" class="action-btn" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <a href="delete_player.php?id=<?php echo $player['id']; ?>" class="action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this player?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <h3>No players found</h3>
                        <p>Start by adding a new player</p>
                    </div>
                <?php endif; ?>
            </div>

            <div id="player-list-container" class="table-container" style="display: none;">
                <?php if (isset($players) && count($players) > 0): ?>
                    <table class="table-view">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Team</th>
                                <th>Nationality</th>
                                <th>Date of Birth</th>
                                <th>Jersey #</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($players as $player): ?>
                                <tr>
                                    <td class="player-name-cell">
                                        <?php if ($player['player_photo']): ?>
                                            <img src="../uploads/players/<?php echo htmlspecialchars($player['player_photo']); ?>" alt="Photo" class="table-avatar">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($player['first_name'] . ' ' . $player['last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($player['position']); ?></td>
                                    <td><?php echo htmlspecialchars($player['team'] ?: 'Not assigned'); ?></td>
                                    <td><?php echo htmlspecialchars($player['nationality']); ?></td>
                                    <td><?php echo htmlspecialchars($player['birth_date']); ?></td>
                                    <td><?php echo htmlspecialchars($player['jersey_number'] ?: '-'); ?></td>
                                    <td class="action-cell">
                                        <a href="view_player.php?id=<?php echo $player['id']; ?>" class="action-btn" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <a href="edit_player.php?id=<?php echo $player['id']; ?>" class="action-btn" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <a href="delete_player.php?id=<?php echo $player['id']; ?>" class="action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this player?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No players found</h3>
                        <p>Start by adding a new player</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Navigation
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            function setActiveTab(tabId) {
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Remove active class from all tabs
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Set active tab and content
                document.getElementById(tabId).classList.add('active');
                document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
            }
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    setActiveTab(tabId);
                });
            });
            
            // Search functionality for teams
            const teamSearch = document.getElementById('team-search');
            const teamSearchBtn = document.getElementById('team-search-btn');
            
            function searchTeams() {
                const searchTerm = teamSearch.value.toLowerCase();
                const teamCards = document.querySelectorAll('.team-card');
                const teamRows = document.querySelectorAll('#team-list-container tr:not(:first-child)');
                
                // Search in grid view
                teamCards.forEach(card => {
                    const teamName = card.querySelector('.card-team-name').textContent.toLowerCase();
                    const teamLocation = card.querySelector('.card-team-location').textContent.toLowerCase();
                    
                    if (teamName.includes(searchTerm) || teamLocation.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Search in list view
                teamRows.forEach(row => {
                    const teamName = row.cells[0].textContent.toLowerCase();
                    const country = row.cells[1].textContent.toLowerCase();
                    const city = row.cells[2].textContent.toLowerCase();
                    
                    if (teamName.includes(searchTerm) || country.includes(searchTerm) || city.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            teamSearchBtn.addEventListener('click', searchTeams);
            teamSearch.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchTeams();
                }
            });
            
            // Search functionality for players
            const playerSearch = document.getElementById('player-search');
            const playerSearchBtn = document.getElementById('player-search-btn');
            
            function searchPlayers() {
                const searchTerm = playerSearch.value.toLowerCase();
                const playerCards = document.querySelectorAll('.player-card');
                const playerRows = document.querySelectorAll('#player-list-container tr:not(:first-child)');
                
                // Search in grid view
                playerCards.forEach(card => {
                    const playerName = card.querySelector('.card-title').textContent.toLowerCase();
                    const position = card.querySelector('.position-badge').textContent.toLowerCase();
                    const playerDetails = card.querySelector('.player-details').textContent.toLowerCase();
                    
                    if (playerName.includes(searchTerm) || position.includes(searchTerm) || playerDetails.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Search in list view
                playerRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            playerSearchBtn.addEventListener('click', searchPlayers);
            playerSearch.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchPlayers();
                }
            });
            
            // View toggle for teams
            const teamGridView = document.getElementById('team-grid-view');
            const teamListView = document.getElementById('team-list-view');
            const teamGridContainer = document.getElementById('team-grid-container');
            const teamListContainer = document.getElementById('team-list-container');
            
            teamGridView.addEventListener('click', function() {
                teamGridView.classList.add('active');
                teamListView.classList.remove('active');
                teamGridContainer.style.display = '';
                teamListContainer.style.display = 'none';
            });
            
            teamListView.addEventListener('click', function() {
                teamListView.classList.add('active');
                teamGridView.classList.remove('active');
                teamListContainer.style.display = '';
                teamGridContainer.style.display = 'none';
            });
            
            // View toggle for players
            const playerGridView = document.getElementById('player-grid-view');
            const playerListView = document.getElementById('player-list-view');
            const playerGridContainer = document.getElementById('player-grid-container');
            const playerListContainer = document.getElementById('player-list-container');
            
            playerGridView.addEventListener('click', function() {
                playerGridView.classList.add('active');
                playerListView.classList.remove('active');
                playerGridContainer.style.display = '';
                playerListContainer.style.display = 'none';
            });
            
            playerListView.addEventListener('click', function() {
                playerListView.classList.add('active');
                playerGridView.classList.remove('active');
                playerListContainer.style.display = '';
                playerGridContainer.style.display = 'none';
            });
        });
    </script>
</body>
</html>
