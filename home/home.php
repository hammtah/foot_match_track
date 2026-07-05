<!-- user-select: none; -->

<?php
session_start();
require '../includes/db.php';
function getFollowedTeams()
{
  $id_user = $_SESSION["id"] ?? null;
  global $bd;
  $teamsSql = "select * from teams join follow on teams.id = follow.event_id and follow.event_type = 'team' where follow.id_user = :id_user";
  $teams = $bd->prepare($teamsSql);
  $teams->bindValue(':id_user', $id_user, PDO::PARAM_INT);
  $teams->execute();
  $teams = $teams->fetchAll(PDO::FETCH_ASSOC);
  return $teams;
}
// $teams = getFollowedTeams();
function getUnFollowedTeams()
{
  $id_user = $_SESSION["id"] ?? null;
  global $bd;
  $teamsSql = "select * from teams where teams.id not in(select teams.id from teams join follow on teams.id = follow.event_id and follow.event_type = 'team' where follow.id_user = :id_user)";
  $teams = $bd->prepare($teamsSql);
  $teams->bindValue(':id_user', $id_user, PDO::PARAM_INT);
  $teams->execute();
  $teams = $teams->fetchAll(PDO::FETCH_ASSOC);
  return $teams;
}

$followedTeams = getFollowedTeams();
$unFollowedTeams = getUnFollowedTeams();



//get the latest played matches
// function getLatestMatches($limit = 10, $tournament_id){
//     global $bd;
//     //create a view of the latest matches
//     $LatestMatchesView = " CREATE OR REPLACE VIEW latest_matches_tournament_:tournament_id AS SELECT DISTINCT 
//             m.id_match AS id_match, 
//             m.Nom_match, 
//             m.date_match, 
//             m.time_match, 
//             m.tournament_id,
//             t1.id AS id_team1, 
//             t2.id AS id_team2, 
//             t1.team_name AS team1_name, 
//             t1.logo_path AS team1_logo, 
//             t2.team_name AS team2_name, 
//             t2.logo_path AS team2_logo
//         FROM _match m
//         JOIN teams t1 ON id_equipe1 = t1.id
//         JOIN teams t2 ON id_equipe2 = t2.id
//         WHERE m.tournament_id = :tournament_id
//         AND 
//             (date_match < CURRENT_DATE) 
//             OR 
//             (date_match = CURRENT_DATE AND time_match < (CURRENT_TIME + INTERVAL 3 HOUR))
//         ORDER BY date_match DESC, time_match ASC
//     ";
//     $LatestMatchesView = str_replace(':tournament_id', $tournament_id, $LatestMatchesView);	
//     $bd->query($LatestMatchesView)->execute();


//     //Get scores table
//     $ScoreView = "CREATE OR REPLACE VIEW score_tournament_:tournament_id AS select b.id_match, b.id_team, count(b.id_match) as butes
//                          from latest_matches_tournament_:tournament_id l JOIN but b on l.id_match = b.id_match
//                          group by b.id_team, b.id_match  ";
//     $ScoreView = str_replace(':tournament_id', $tournament_id, $ScoreView);
//     $bd->query($ScoreView)->execute();

//     $matchInfo = "CREATE OR REPLACE VIEW match_info_tournament_:tournament_id AS SELECT 
//                 l.Nom_match ,l.date_match  ,l.team1_name ,l.team1_logo ,l.team2_name ,
//                 l.team2_logo, l.id_match, l.id_team1, l.id_team2, s1.butes as 'butes_team1', s2.butes as 'butes_team2' 
//                 FROM  latest_matches_tournament_:tournament_id l join score_tournament_:tournament_id s1 JOIN score_tournament_:tournament_id s2 
//                 on l.id_match = s1.id_match and l.id_match = s2.id_match 
//                 and l.id_team1 = s1.id_team and l.id_team2 = s2.id_team
//             ";
//     $matchInfo = str_replace(':tournament_id', $tournament_id, $matchInfo);
//     $bd->query($matchInfo)->execute();

//     $sql = "SELECT * FROM match_info_tournament_:tournament_id limit $limit";
//     $sql = str_replace(':tournament_id', $tournament_id, $sql);
//     $stmt = $bd->query($sql);

//     $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);


//     //Get scores

//     return $matches;
// }

function getLatestMatches($tournament_id, $limit = 30)
{
  global $bd;

  $tournament_id = intval($tournament_id);
  $limit = intval($limit);

  $sql = "
        SELECT 
            m.id_match,
            m.Nom_match,
            m.date_match,
            t1.id AS id_team1, 
            t2.id AS id_team2, 
            t1.team_name AS team1_name,
            t1.logo_path AS team1_logo,
            t2.team_name AS team2_name,
            t2.logo_path AS team2_logo,
            COALESCE(s1.butes, 0) AS butes_team1,
            COALESCE(s2.butes, 0) AS butes_team2
        FROM _match m
        JOIN teams t1 ON m.id_equipe1 = t1.id
        JOIN teams t2 ON m.id_equipe2 = t2.id
        LEFT JOIN (
            SELECT id_match, id_team, COUNT(*) AS butes
            FROM but
            GROUP BY id_match, id_team
        ) s1 ON m.id_match = s1.id_match AND m.id_equipe1 = s1.id_team
        LEFT JOIN (
            SELECT id_match, id_team, COUNT(*) AS butes
            FROM but
            GROUP BY id_match, id_team
        ) s2 ON m.id_match = s2.id_match AND m.id_equipe2 = s2.id_team
        WHERE m.tournament_id = :tournament_id
        AND datetime(m.date_match || ' ' || m.time_match) <= datetime('now', '+3 hours')
        ORDER BY m.date_match DESC, m.time_match ASC
        LIMIT :limit
    ";

  $stmt = $bd->prepare($sql);
  $stmt->bindValue(':tournament_id', $tournament_id, PDO::PARAM_INT);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// function getComingMatches($limit = 10, $id_tournoi){
//     global $bd;
//     $comingSql = "SELECT id_match, Nom_match, date_match, time_match,  t1.id AS id_team1, t1.team_name AS team1_name, t1.logo_path AS team1_logo,t1.id AS id_team2, t2.team_name AS team2_name, t2.logo_path AS team2_logo
//             FROM _match JOIN teams t1, teams t2 where id_equipe1=t1.id and id_equipe2=t2.id
//             AND 
//                 (date_match > CURRENT_DATE) 
//                 OR (date_match = CURRENT_DATE AND time_match > CURRENT_TIME)
//             ORDER BY date_match ASC, time_match ASC limit $limit
//             ";
//     $comingStmt = $bd->query($comingSql);
//     $comingStmt->execute();
//     $matches = $comingStmt->fetchAll(PDO::FETCH_ASSOC);
//     return $matches;
// }
function getComingMatches($id_tournoi, $limit = 10)
{
  global $bd;

  $sql = "
        SELECT 
            m.id_match, 
            m.Nom_match, 
            m.date_match, 
            m.time_match,  
            t1.id AS id_team1, 
            t1.team_name AS team1_name, 
            t1.logo_path AS team1_logo,
            t2.id AS id_team2, 
            t2.team_name AS team2_name, 
            t2.logo_path AS team2_logo
        FROM _match m
        JOIN teams t1 ON m.id_equipe1 = t1.id
        JOIN teams t2 ON m.id_equipe2 = t2.id
        WHERE m.tournament_id = :id_tournoi
        AND datetime(m.date_match || ' ' || m.time_match) > datetime('now')
        ORDER BY m.date_match ASC, m.time_match ASC
        LIMIT :limit
    ";

  $stmt = $bd->prepare($sql);
  $stmt->bindValue(':id_tournoi', intval($id_tournoi), PDO::PARAM_INT);
  $stmt->bindValue(':limit', intval($limit), PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getTeams($limit = 10, $id_tournoi)
{
  global $bd;
  $teamsSql = "SELECT * from teams limit $limit";
  $teams = $bd->query($teamsSql);
  $teams->execute();
  $teams = $teams->fetchAll(PDO::FETCH_ASSOC);
  return $teams;
}

// $comingMatches = getComingMatches(1, 10);
// $closestMatch = $comingMatches[0];
// $latestMatches = getLatestMatches(1, 10);

function getTournaments()
{
  global $bd;
  $sql = "SELECT * FROM tournaments";
  $stmt = $bd->query($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$tournaments = getTournaments();
$matchesData = [];

//store all infos for all tournaments
foreach ($tournaments as $tournament) {
  $matchesData[] = [
    'tournament' => $tournament,
    'latestMatches' => getLatestMatches($tournament['id'], 10),
    'comingMatches' => getComingMatches($tournament['id'], 10)
  ];
}
// $teams = getTeams(10);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="home-style.css">

  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


  <link rel="stylesheet" href="teams.css">

</head>

<body>

  <?php require('../includes/header.php') ?>

  <main class="match-container">
    <div class="match-layout">
      <section class="match-video-section slideshow-container" id="match-video-section">
        <?php foreach ($matchesData as $index => $matchData): ?>
          <?php $comingMatches = $matchData['comingMatches']; ?>
          <?php if (!empty($comingMatches)): ?>
            <?php $closestMatch = $comingMatches[0]; ?>
            <?php
              $matchTitle = trim($closestMatch['Nom_match'] ?? '');
              if ($matchTitle === '') {
                $matchTitle = $closestMatch['team1_name'] . ' vs ' . $closestMatch['team2_name'];
              }
              $kickoff = $closestMatch['date_match'] . 'T' . $closestMatch['time_match'];
            ?>
            <div class="slide fade" data-kickoff="<?= htmlspecialchars($kickoff) ?>">
              <h2 class="tournament-name">🏆 <?= $matchData['tournament']['tournament_name'] ?></h2>
              <img src="../assets/<?= $closestMatch["team1_logo"] ?>" alt="<?= htmlspecialchars($closestMatch['team1_name']) ?>" class="team-logo">
              <div class="info">
                <h2 class="info-match-name"><?= htmlspecialchars($matchTitle) ?></h2>
                <h2 class="info-match-date"><?= $closestMatch["date_match"] ?></h2>
                <h2 class="info-match-time"><?= substr($closestMatch["time_match"], 0, 5) ?></h2>
                <p class="info-countdown-label">Time left</p>
                <h2 class="info-countdown countdown-value">--:--:--</h2>
              </div>
              <img src="../assets/<?= $closestMatch["team2_logo"] ?>" alt="<?= htmlspecialchars($closestMatch['team2_name']) ?>" class="team-logo">
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
      </section>

      <script>
        let slideIndex = 0;
        let slides = document.querySelectorAll(".slide");
        let autoSlideTimeout;
        const slideshowContainer = document.getElementById("match-video-section");

        showSlides();

        function showSlides() {
          slides.forEach((slide) => slide.style.display = "none");
          slideIndex++;
          if (slideIndex > slides.length) {
            slideIndex = 1;
          }
          slides[slideIndex - 1].style.display = "flex";
          autoSlideTimeout = setTimeout(showSlides, 5000); // Change slide every 5 seconds
        }

        function changeSlide(n) {
          clearTimeout(autoSlideTimeout); // Stop the automatic slide change
          slides.forEach((slide) => slide.style.display = "none");
          slideIndex += n;
          if (slideIndex > slides.length) {
            slideIndex = 1;
          }
          if (slideIndex < 1) {
            slideIndex = slides.length;
          }
          slides[slideIndex - 1].style.display = "flex";
          autoSlideTimeout = setTimeout(showSlides, 5000); // Restart the automatic slide change
        }

        // Pause auto-slide when mouse is over the slideshow container
        slideshowContainer.addEventListener("mouseenter", () => {
          clearTimeout(autoSlideTimeout);
        });

        // Resume auto-slide when mouse leaves the slideshow container
        slideshowContainer.addEventListener("mouseleave", () => {
          autoSlideTimeout = setTimeout(showSlides, 5000);
        });
      </script>

      <style>
        .slideshow-container {
          position: relative;
          width: 100%;
          max-width: 920px;
          margin: auto;
          display: flex;
          justify-content: center;
          align-items: center;
          overflow: hidden;
        }

        .slide {
          display: none;
          flex-direction: row;
          justify-content: space-between;
          align-items: center;
          width: 100%;
          animation: fade 1s ease-in-out;
        }

        .team-logo {
          width: 100px;
          height: 100px;
          object-fit: contain;
        }

        .info {
          text-align: center;
        }

        .info-match-name,
        .info-match-date,
        .info-match-time {
          margin: 5px 0;
        }

        .tournament-name {
          position: absolute;
          top: 10px;
          left: 50%;
          transform: translateX(-50%);
          font-size: 1.5rem;
          font-weight: bold;
          font-family: Lato, sans-serif;
        }

        .prev,
        .next {
          cursor: pointer;
          position: absolute;
          top: 50%;
          width: auto;
          margin-top: -22px;
          padding: 16px;
          color: white;
          font-weight: bold;
          font-size: 18px;
          transition: 0.6s ease;
          border-radius: 0 3px 3px 0;
        }

        .prev {
          left: 0;
          border-radius: 3px 0 0 3px;
        }

        .next {
          right: 0;
          border-radius: 0 3px 3px 0;
        }

        .prev:hover,
        .next:hover {
          background-color: rgba(0, 0, 0, 0.8);
        }

        @keyframes fade {
          from {
            opacity: 0.4;
          }

          to {
            opacity: 1;
          }
        }

        .match-video-section div {
          width: 95%;
        }

        .score {
          width: 75px;
        }
      </style>







      <!-- <section class="stats-section">
      <article class="stats-card">
        <header class="match-header">
          <h1 class="match-status">Latest Match</h1>
          <p class="match-time"><?= $latestMatches[0]["Nom_match"] ?></p>
        </header>
        <section class="teams-score">
          <img
            src="../assets/<?= $latestMatches[0]["team1_logo"] ?>"
          class="team-logo"
            alt="Home team logo"
          />
          <p class="score-display">2 - 2</p>
          <img
          src="../assets/<?= $latestMatches[0]["team2_logo"] ?>"
          class="team-logo"
            alt="Away team logo"
          />
        </section>
        <section class="match-stats">
          <div class="stat-group">
            <h2 class="stat-title">Shoot on Target</h2>
            <div class="stat-values">
              <span class="home-stat">7</span>
              <span class="away-stat">3</span>
            </div>
          </div>
          <div class="stat-group">
            <h2 class="stat-title">Shoot</h2>
            <div class="stat-values">
              <span class="home-stat">12</span>
              <span class="away-stat">7</span>
            </div>
          </div>
          <div class="stat-group">
            <h2 class="stat-title">Fouls</h2>
            <div class="stat-values">
              <span class="home-stat">7</span>
              <span class="away-stat">3</span>
            </div>
          </div>
        </section>
      </article>
    </section> -->
    </div>
  </main>

  <!-- home matches -->
  <?php
  //get the latest played matches
  /*
function getLatestMatches(){
    global $bd;
    $sql = "SELECT DISTINCT 
            id_match, 
            Nom_match, 
            date_match, 
            time_match, 
            t1.team_name AS team1_name, 
            t1.logo_path AS team1_logo, 
            t2.team_name AS team2_name, 
            t2.logo_path AS team2_logo
        FROM _match 
        JOIN teams t1 ON id_equipe1 = t1.id
        JOIN teams t2 ON id_equipe2 = t2.id
        WHERE 
            (date_match < CURRENT_DATE) 
            OR 
            (date_match = CURRENT_DATE AND time_match < (CURRENT_TIME + INTERVAL 3 HOUR))
        ORDER BY date_match DESC, time_match ASC
    ";
    $stmt = $bd->query($sql);
    $stmt->execute();
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $matches;
}
*/
  // $latestMatches = getLatestMatches();
  // // print_r($latestMatches);
  // $comingMatches = [];
  ?>
  <?php foreach ($matchesData as $matcheData): ?>
    <?php
    $tournament = $matcheData['tournament'];
    $comingMatches = $matcheData['comingMatches'];
    $latestMatches = $matcheData['latestMatches'];
    // $comingMatches = getComingMatches($tournament['id'], 10);
    // $closestMatch = $comingMatches[0];
    // $latestMatches = getLatestMatches($tournament['id'], 10);
    ?>
    <main class="container">
      <header class="header">
        <h1 class="title">🏆 <?= $tournament['tournament_name'] ?></h1>
        <ul class="menu-items">
          <li class="menu-item active menu-item-<?= $tournament['id'] ?>" onclick="show('latest-matches', '<?= $tournament['id'] ?>', event)">Latest Match</li>
          <li class="menu-item menu-item-<?= $tournament['id'] ?>" onclick="show('coming-matches', '<?= $tournament['id'] ?>', event)">Coming Match</li>
          <li class="menu-item menu-item-<?= $tournament['id'] ?>" onclick="show('preseason', '<?= $tournament['id'] ?>', event)">Pre-season</li>
        </ul>
      </header>

      <!-- Latest Matches Section -->
      <section class="matches" id="latest-matches-<?= $tournament['id'] ?>">
        <?php foreach ($latestMatches as $match): ?>
          <article class="match">
            <div class="team-section">
              <a href="../teams/team-info.php?idTeam=<?= $match['id_team1'] ?>" class="team">
                <img src="../assets/<?= $match['team1_logo'] ?>" alt="<?= $match['team1_name'] ?>" class="team-flag" />
                <h2 class="team-name"><?= $match['team1_name'] ?></h2>
              </a>
              <div class="score"><?= $match['butes_team1'] ?> - <?= $match['butes_team2'] ?></div>
              <a href="../teams/team-info.php?idTeam=<?= $match['id_team2'] ?>" class="team right">
                <h2 class="team-name right"><?= $match['team2_name'] ?></h2>
                <img src="../assets/<?= $match['team2_logo'] ?>" alt="<?= $match['team2_name'] ?>" class="team-flag" />
              </a>
            </div>

            <div class="match-status">Full - Time</div>
            <div class="match-info">
              <time class="match-date"><?= $match['date_match'] ?></time>
            </div>
            <div class="match-icons">
              <a href="../match/match-details.php?match_id=<?= $match['id_match'] ?>" class="icon-button" aria-label="Match Information">
                <svg
                  class="info-icon"
                  width="24"
                  height="24"
                  viewBox="0 0 25 25"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M12.5 22.5C6.977 22.5 2.5 18.023 2.5 12.5C2.5 6.977 6.977 2.5 12.5 2.5C18.023 2.5 22.5 6.977 22.5 12.5C22.5 18.023 18.023 22.5 12.5 22.5ZM12.5 20.5C14.6217 20.5 16.6566 19.6571 18.1569 18.1569C19.6571 16.6566 20.5 14.6217 20.5 12.5C20.5 10.3783 19.6571 8.34344 18.1569 6.84315C16.6566 5.34285 14.6217 4.5 12.5 4.5C10.3783 4.5 8.34344 5.34285 6.84315 6.84315C5.34285 8.34344 4.5 10.3783 4.5 12.5C4.5 14.6217 5.34285 16.6566 6.84315 18.1569C8.34344 19.6571 10.3783 20.5 12.5 20.5V20.5ZM11.5 7.5H13.5V9.5H11.5V7.5ZM11.5 11.5H13.5V17.5H11.5V11.5Z"
                    fill="#A4A4A4" />
                </svg>
              </a>
            </div>

          </article>
        <?php endforeach; ?>
      </section>

      <!-- Coming Matches Section -->
      <section class="matches hidden" id="coming-matches-<?= $tournament['id'] ?>">
        <?php if (empty($comingMatches)): ?>
          <p class="empty-matches">No upcoming matches scheduled.</p>
        <?php endif; ?>
        <?php foreach ($comingMatches as $match): ?>
          <?php
            $matchTitle = trim($match['Nom_match'] ?? '');
            if ($matchTitle === '') {
              $matchTitle = $match['team1_name'] . ' vs ' . $match['team2_name'];
            }
            $kickoff = $match['date_match'] . 'T' . $match['time_match'];
          ?>
          <article class="match" data-kickoff="<?= htmlspecialchars($kickoff) ?>">
            <div class="team-section">
              <a href="../teams/team-info.php?idTeam=<?= $match['id_team1'] ?>" class="team">
                <img src="../assets/<?= $match['team1_logo'] ?>" alt="<?= $match['team1_name'] ?>" class="team-flag" />
                <h2 class="team-name"><?= $match['team1_name'] ?></h2>
              </a>
              <div class="score">-</div>
              <a href="../teams/team-info.php?idTeam=<?= $match['id_team2'] ?>" class="team right">
                <h2 class="team-name right"><?= $match['team2_name'] ?></h2>
                <img src="../assets/<?= $match['team2_logo'] ?>" alt="<?= $match['team2_name'] ?>" class="team-flag" />
              </a>
            </div>
            <div class="match-status">Upcoming</div>
            <div class="match-info">
              <time class="match-date"><?= $match['date_match'] ?> · <?= substr($match['time_match'], 0, 5) ?></time>
              <p class="match-countdown countdown-value">--:--:--</p>
            </div>
            <div class="match-icons">
              <a href="../match/match-details.php?match_id=<?= $match['id_match'] ?>" class="icon-button" aria-label="Match Information">
                <svg
                  class="info-icon"
                  width="24"
                  height="24"
                  viewBox="0 0 25 25"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M12.5 22.5C6.977 22.5 2.5 18.023 2.5 12.5C2.5 6.977 6.977 2.5 12.5 2.5C18.023 2.5 22.5 6.977 22.5 12.5C22.5 18.023 18.023 22.5 12.5 22.5ZM12.5 20.5C14.6217 20.5 16.6566 19.6571 18.1569 18.1569C19.6571 16.6566 20.5 14.6217 20.5 12.5C20.5 10.3783 19.6571 8.34344 18.1569 6.84315C16.6566 5.34285 14.6217 4.5 12.5 4.5C10.3783 4.5 8.34344 5.34285 6.84315 6.84315C5.34285 8.34344 4.5 10.3783 4.5 12.5C4.5 14.6217 5.34285 16.6566 6.84315 18.1569C8.34344 19.6571 10.3783 20.5 12.5 20.5V20.5ZM11.5 7.5H13.5V9.5H11.5V7.5ZM11.5 11.5H13.5V17.5H11.5V11.5Z"
                    fill="#A4A4A4" />
                </svg>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </section>

      <!-- Pre-season Section -->
      <section class="matches hidden" id="preseason-<?= $tournament['id'] ?>">
        <p>No pre-season matches available.</p>
      </section>

    </main>
  <?php endforeach; ?>

  <!-- teams bar -->
  <!-- teams bar -->
  <section class="teams-container">
    <section class="teams-container-header">
      <h3 class="title">🎮 Follow Club</h3>
      <div class="arrows-div">
        <i id="left-arrow" class="fa-solid fa-arrow-left" style="color: #A4A4A4;"></i>
        <i id="right-arrow" class="fa-solid fa-arrow-right" style="color: #A4A4A4;"></i>
      </div>
    </section>
    <section class="teams-section" id="teams-section">
      <?php foreach ($unFollowedTeams as $team): ?>
        <section class="team">
          <a class="team-div" href="../teams/team-info.php?idTeam=<?= $team["id"] ?>">
            <div class="team-logo">
              <img src="../assets/<?= $team["logo_path"] ?>" alt="">
            </div>
          </a>
          <h5 class="team-name"><?= $team["team_name"] ?></h5>
        </section>
      <?php endforeach; ?>
    </section>
  </section>

  <!-- teams bar -->
  <section class="teams-container">
    <section class="teams-container-header">
      <h3 class="title">🎮 Followed Clubs</h3>
      <div class="arrows-div">
        <i id="left-arrow" class="fa-solid fa-arrow-left" style="color: #A4A4A4;"></i>
        <i id="right-arrow" class="fa-solid fa-arrow-right" style="color: #A4A4A4;"></i>
      </div>
    </section>
    <section class="teams-section" id="teams-section">
      <?php foreach ($followedTeams as $team): ?>
        <section class="team">
          <a class="team-div" href="../teams/team-info.php?idTeam=<?= $team["id"] ?>">
            <div class="team-logo">
              <img src="../assets/<?= $team["logo_path"] ?>" alt="">
            </div>
          </a>
          <h5 class="team-name"><?= $team["team_name"] ?></h5>
        </section>
      <?php endforeach; ?>
    </section>
  </section>







  <script>
    const leftArrow = document.getElementById("left-arrow");
    const rightArrow = document.getElementById("right-arrow");
    const teamsSection = document.getElementById("teams-section");
    leftArrow.addEventListener("click", () => {
      teamsSection.scrollLeft -= 250; // Scroll left
    })
    rightArrow.addEventListener("click", () => {
      teamsSection.scrollLeft += 250; // Scroll left
    })

    // // To delete 
    // const teams = [
    //     {name: "Arsenal", img: "https://1000logos.net/wp-content/uploads/2018/07/Feyenoord-Logo.png"}, 
    //     {name: "Mcity", img: "https://1000logos.net/wp-content/uploads/2017/05/Manchester-City-Logo.png"},
    //     {name: "Real", img: "https://1000logos.net/wp-content/uploads/2017/04/Logo-Liverpool.png"},
    //     {name: "Arsenal", img: "https://1000logos.net/wp-content/uploads/2018/07/Feyenoord-Logo.png"}, 
    //     {name: "Mcity", img: "https://1000logos.net/wp-content/uploads/2017/05/Manchester-City-Logo.png"},
    //     {name: "Real", img: "https://1000logos.net/wp-content/uploads/2017/04/Logo-Liverpool.png"},
    //     {name: "Arsenal", img: "https://1000logos.net/wp-content/uploads/2018/07/Feyenoord-Logo.png"}, 
    //     {name: "Mcity", img: "https://1000logos.net/wp-content/uploads/2017/05/Manchester-City-Logo.png"},
    //     {name: "Real", img: "https://1000logos.net/wp-content/uploads/2017/04/Logo-Liverpool.png"},
    //     ]

    // teamsSection.textContent ="";
    // for (const team of teams) {
    //     teamsSection.innerHTML += `
    //         <section class="team">
    //             <a class="team-div" href="${team.img}">
    //                     <div class="team-logo">
    //                         <img src="${team.img}" alt="${team.name}">
    //                     </div>
    //             </a>
    //             <h5 class="team-name">${team.name}</h5>
    //         </section>
    //     `
    // }
  </script>

  <script>
    function show(selector, idTournoi, event) {
      // Hide all sections for the current tournament
      document.querySelectorAll(`#latest-matches-${idTournoi}, #coming-matches-${idTournoi}, #preseason-${idTournoi}`).forEach((section) => {
        section.classList.add("hidden");
      });

      // Show the selected section
      document.getElementById(`${selector}-${idTournoi}`).classList.remove("hidden");

      // Update the active state of the menu items
      document.querySelectorAll(`.menu-item-${idTournoi}`).forEach((menuItem) => {
        menuItem.classList.remove("active");
      });
      event.target.classList.add("active");
    }

    function updateMatchCountdowns() {
      document.querySelectorAll("[data-kickoff] .countdown-value").forEach((countdownEl) => {
        const container = countdownEl.closest("[data-kickoff]");
        const target = new Date(container.dataset.kickoff);
        if (Number.isNaN(target.getTime())) {
          return;
        }

        const diff = target - Date.now();
        if (diff <= 0) {
          countdownEl.textContent = "Starting soon";
          return;
        }

        const days = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const mins = Math.floor((diff % 3600000) / 60000);
        const secs = Math.floor((diff % 60000) / 1000);
        countdownEl.textContent = `${days}d ${hours}h ${mins}m ${secs}s`;
      });
    }

    updateMatchCountdowns();
    setInterval(updateMatchCountdowns, 1000);
  </script>
</body>

</html>