<?php


$match_id=$_GET['match_id'];
print_r($match_id);

// require '../admin-tournoi/fetch-matches.php?${match_id}' ;



?>

<!DOCTYPE html>
<html lang="en">





<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details | BOTOLA</title>
    <link rel="stylesheet" href="match-details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <?php require('../includes/header.php') ?>

    <div class="container">
        <div class="main-content">
            <!-- Match Header Card -->
            <div class="card match-header-card">
                <div class="card-body">
                    <div class="back-button">
                        <i class="fas fa-arrow-left"></i>
                        <span>Matches</span>
                    </div>
                    <div class="competition">
                        <div class="competition-logo">
                            <img src="/placeholder.svg?height=24&width=24" alt="BOTOLA">
                        </div>
                        <span>BOTOLA</span>
                    </div>
                    <div class="follow-button">
                        <button>Follow</button>
                    </div>
                </div>
            </div>

            <!-- Match Info Card -->
            <div class="card match-info-card">
                <div class="card-body">
                    <div class="match-details">
                        <div class="detail">
                            <i class="far fa-calendar"></i>
                            <span>Sat, March 29, 8:00 PM</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Estadio Santiago Bernabéu</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-user"></i>
                            <span>Pablo González Fuertes</span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-users"></i>
                            <span>73,041</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Score Card -->
            <div class="card match-score-card">
                <div class="card-body">
                    <div class="team home-team">
                        <div class="team-name">Real Madrid</div>
                        <div class="team-logo">
                            <img src="/placeholder.svg?height=70&width=70" alt="Real Madrid">
                        </div>
                    </div>
                    <div class="score-display">
                        <div class="score">3 - 2</div>
                        <div class="match-status">Full time</div>
                    </div>
                    <div class="team away-team">
                        <div class="team-logo">
                            <img src="/placeholder.svg?height=70&width=70" alt="Leganes">
                        </div>
                        <div class="team-name">Leganes</div>
                    </div>
                </div>
            </div>

            <!-- Goal Scorers Card -->
            <div class="card goal-scorers-card">
                <div class="card-body">
                    <div class="home-scorers">
                        <div class="scorer">
                            <span class="player">Mbappé 32' (Pen)</span>
                        </div>
                        <div class="scorer">
                            <span class="player">Mbappé 70'</span>
                        </div>
                        <div class="scorer">
                            <span class="player">Bellingham 47'</span>
                        </div>
                    </div>
                    <div class="away-scorers">
                        <div class="scorer">
                            <span class="player">García 33'</span>
                        </div>
                        <div class="scorer">
                            <span class="player">Raba 41'</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Navigation Card -->
            <div class="card match-navigation-card">
                <div class="card-body">
                    <div class="nav-item active">Facts</div>
                    <div class="nav-item">Commentary</div>
                    <div class="nav-item">Lineup</div>
                    <div class="nav-item">Table</div>
                    <div class="nav-item">Stats</div>
                    <div class="nav-item">Head-to-Head</div>
                    <div class="nav-item">Classement</div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card stats-card">
                <div class="card-header">
                    <h2 class="card-title">Top stats</h2>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-label">Ball possession</div>
                        <div class="stat-bar">
                            <div class="home-bar" style="width: 71%;">
                                <span class="stat-value">71%</span>
                            </div>
                            <div class="away-bar" style="width: 29%;">
                                <span class="stat-value">29%</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-label">Expected goals (xG)</div>
                        <div class="stat-values">
                            <div class="home-value">2.87</div>
                            <div class="away-value">2.87</div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-label">Total shots</div>
                        <div class="stat-values">
                            <div class="home-value">24</div>
                            <div class="away-value">10</div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-label">Big chances</div>
                        <div class="stat-values">
                            <div class="home-value">5</div>
                            <div class="away-value">4</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="view-all-stats">
                        <button>All stats</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Competition Card -->
            <div class="card sidebar-card">
                <div class="card-header">
                    <div class="competition-logo">
                        <img src="/placeholder.svg?height=24&width=24" alt="BOTOLA">
                    </div>
                    <div class="sidebar-competition-info">
                        <div class="sidebar-competition-name">BOTOLA</div>
                        <div class="sidebar-competition-round">Round 29</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="match-list">
                        <div class="match-item">
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Real Sociedad">
                                    <span>Real Sociedad</span>
                                </div>
                                <div class="match-result">
                                    <span>2</span>
                                </div>
                            </div>
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Real Valladolid">
                                    <span>Real Valladolid</span>
                                </div>
                                <div class="match-result">
                                    <span>1</span>
                                </div>
                            </div>
                            <div class="match-status">FT</div>
                        </div>

                        <div class="match-item">
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Espanyol">
                                    <span>Espanyol</span>
                                </div>
                                <div class="match-result">
                                    <span>1</span>
                                </div>
                            </div>
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Atletico Madrid">
                                    <span>Atletico Madrid</span>
                                </div>
                                <div class="match-result">
                                    <span>1</span>
                                </div>
                            </div>
                            <div class="match-status">FT</div>
                        </div>

                        <div class="match-item highlighted">
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Real Madrid">
                                    <span>Real Madrid</span>
                                </div>
                                <div class="match-result">
                                    <span>3</span>
                                </div>
                            </div>
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Leganes">
                                    <span>Leganes</span>
                                </div>
                                <div class="match-result">
                                    <span>2</span>
                                </div>
                            </div>
                            <div class="match-status">FT</div>
                        </div>

                        <div class="match-item upcoming">
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Getafe">
                                    <span>Getafe</span>
                                </div>
                            </div>
                            <div class="match-teams">
                                <div class="team">
                                    <img src="/placeholder.svg?height=20&width=20" alt="Villarreal">
                                    <span>Villarreal</span>
                                </div>
                            </div>
                            <div class="match-time">
                                <div>Today</div>
                                <div>12:00 PM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php require('comments.php') ?>
    <?php require('../includes/classement.php') ?>


    
    <script src="match-details.js"></script>
</body>

</html>