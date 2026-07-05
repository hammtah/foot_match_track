// Function to show the initial active tab
function showInitialTab() {
    const navItems = document.querySelectorAll(".match-navigation-card .nav-item");
    const tabPanels = document.querySelectorAll(".tab-panels .tab-content"); // Select panels inside the new container

    const initialActiveTab = document.querySelector('.match-navigation-card .nav-item.active');
    if (initialActiveTab) {
         const initialTargetId = initialActiveTab.getAttribute('data-tab');
         const initialTargetContent = document.getElementById(initialTargetId);
         if (initialTargetContent) {
             // Ensure only the correct initial tab is active
             tabPanels.forEach(panel => panel.classList.remove('active')); // Use tabPanels
             initialTargetContent.classList.add('active');
         }
    } else {
        // Fallback: If no nav item is active, activate the first one
        if (navItems.length > 0 && tabPanels.length > 0) {
            navItems[0].classList.add('active');
            tabPanels[0].classList.add('active');
        }
    }
}


document.addEventListener("DOMContentLoaded", () => {
  // NEW Tab Navigation Logic for main nav
  const navItems = document.querySelectorAll(".match-navigation-card .nav-item");
  const tabPanels = document.querySelectorAll(".tab-panels .tab-content"); // Select panels inside the new container

  navItems.forEach((item) => {
      item.addEventListener("click", () => {
          const targetId = item.getAttribute("data-tab");
          const targetContent = document.getElementById(targetId);

          // Remove active class from all nav items and tab panels
          navItems.forEach((nav) => nav.classList.remove("active"));
          tabPanels.forEach((panel) => panel.classList.remove("active")); // Use tabPanels here

          // Add active class to clicked nav item and corresponding content
          item.classList.add("active");
          if (targetContent) {
              targetContent.classList.add("active");
          } else {
              console.warn(`Tab content with ID ${targetId} not found.`);
          }
      });
  });

  // Team Tabs for Lineups (keep existing functionality - assuming this is still needed elsewhere)
  const teamTabs = document.querySelectorAll(".team-tab");
  const teamLineups = document.querySelectorAll(".team-lineup");

  teamTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const team = tab.getAttribute("data-team");

      // Remove active class from all tabs and lineups
      teamTabs.forEach((t) => t.classList.remove("active"));
      teamLineups.forEach((lineup) => lineup.classList.remove("active"));

      // Add active class to clicked tab and corresponding lineup
      tab.classList.add("active");
      document
        .querySelector(`.team-lineup[data-team="${team}"]`)
        .classList.add("active");
    });
  });

  // NEW CODE: Fetch and display match data
  loadMatchData();

  // Show the initial tab after setting up listeners and loading data
  showInitialTab();
});

/**
 * Extracts URL parameters
 * @returns {Object} Object containing URL parameters
 */
function getUrlParams() {
  const params = {};
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  for (const [key, value] of urlParams.entries()) {
    params[key] = value;
  }

  return params;
}

/**
 * Format a date and time for display
 * @param {string} dateStr - Date string in YYYY-MM-DD format
 * @param {string} timeStr - Time string in HH:MM:SS format
 * @returns {string} Formatted date string
 */
function formatMatchDate(dateStr, timeStr) {
  const options = { weekday: "short", month: "long", day: "numeric" };
  const date = new Date(`${dateStr}T${timeStr}`);

  const dayMonth = date.toLocaleDateString("en-US", options);
  const time = date.toLocaleTimeString("en-US", {
    hour: "numeric",
    minute: "2-digit",
  });

  return `${dayMonth}, ${time}`;
}

/**
 * Main function to load all match data
 */
async function loadMatchData() {
  const params = getUrlParams();
  const matchId = params.match_id;

  if (!matchId) {
    console.error("No match ID provided");
    return;
  }

  try {
    // Fetch all matches to get the basic match data
    const matchesResponse = await fetch(
      "../admin-tournoi/fetch-matches.php?tournament_id=1"
    );
    if (!matchesResponse.ok) throw new Error("Error fetching matches");
    let matches = await matchesResponse.json();
    matches.forEach(match => {
      let home_goals=0
      let away_goals=0
      fetchGoals(match.id_match).then((data)=>{
        // console.log("here we go")
        // console.log(data)
        away_goals=data.away.length
        home_goals=data.home.length
        console.log(away_goals,home_goals)
        match.home_score=home_goals
        match.away_score=away_goals
        
      })
      
      
    });

    // Find the specific match by ID
    const match = matches.find(
      (m) => String(m.id_match) === String(matchId)
    );
    if (!match) {
      console.error(`Match with ID ${matchId} not found`);
      return;
    }

    // Fetch additional match data
    const [referees, goals, stats] = await Promise.all([
      fetchReferees(matchId),
      fetchGoals(matchId),
      fetchStats(matchId),
    ]);
    const homeGoalCount = goals.home ? goals.home.length : 0;
    const awayGoalCount = goals.away ? goals.away.length : 0;

    // Add the goal counts to the match object
    match.home_score = homeGoalCount;
    match.away_score = awayGoalCount;

    // Update the UI with the fetched data
    updateMatchHeader(match);
    updateMatchInfo(match, referees);
    updateMatchScore(match);
    updateMatchGoals(goals);
    updateMatchStats(stats);
    updateSidebarMatches(matches, matchId);
  } catch (error) {
    console.error("Error loading match data:", error);
  }
}

/**
 * Fetch match referees
 * @param {number} matchId - Match ID
 * @returns {Promise<Object>} - Match referees data
 */
async function fetchReferees(matchId) {
  try {
    const response = await fetch(
      `../admin-tournoi/get_match_referees.php?match_id=${matchId}`
    );
    if (!response.ok) throw new Error("Error fetching referees");
    return await response.json();
  } catch (error) {
    console.error("Error fetching referees:", error);
    return {
      referees: { main_referee: "N/A", assistant1: "N/A", assistant2: "N/A" },
    };
  }
}










/**
 * Fetch match goals and process data to home/away format
 * @param {number} matchId - Match ID
 * @returns {Promise<Object>} - Match goals data
 */
async function fetchGoals(matchId) {
  try {
    console.log(`Fetching goals for match ID: ${matchId}`);
    const response = await fetch(
      `../admin-tournoi/get_goals.php?match_id=${matchId}`
    );

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    console.log("Goals data received:", data);

    // The API returns {success: true, goals: [...]} format
    if (!data.success || !Array.isArray(data.goals)) {
      return { home: [], away: [] };
    }

    // Need to get match details to determine which team is home/away
    const matchResponse = await fetch(
      `../admin-tournoi/fetch-matches.php?tournament_id=1`
    );
    const matches = await matchResponse.json();
    const match = matches.find((m) => m.id_match == matchId);

    if (!match) {
      console.error("Could not find match details to process goals");
      return { home: [], away: [] };
    }

    // Process goals into home and away categories based on team_id
    const homeTeamId = match.home_team_id;
    const awayTeamId = match.away_team_id;
    console.log(homeTeamId, awayTeamId);

    const home = data.goals.filter((goal) => goal.team_id == homeTeamId);
    const away = data.goals.filter((goal) => goal.team_id == awayTeamId);

    console.log("Processed goals:", { home, away });

    return { home, away };
  } catch (error) {
    console.error("Error fetching goals:", error);
    return { home: [], away: [] };
  }
}

/**
 * Update the goal scorers section
 * @param {Object} goals - Goals data with home and away arrays
 */
function updateMatchGoals(goals) {
  const homeScorersElement = document.querySelector(".home-scorers");
  const awayScorersElement = document.querySelector(".away-scorers");

  // Debug the elements
  console.log("Home scorers element:", homeScorersElement);
  console.log("Away scorers element:", awayScorersElement);
  console.log("Goals data:", goals);

  if (!homeScorersElement || !awayScorersElement) {
    console.error("Could not find goal scorer elements!");
    return;
  }

  // Clear existing scorers
  homeScorersElement.innerHTML = "";
  awayScorersElement.innerHTML = "";

  // Add home team scorers
  if (goals.home && goals.home.length > 0) {
    goals.home.forEach((goal) => {
      const scorerElement = document.createElement("div");
      scorerElement.className = "scorer";

      const goalTypeText = goal.goal_type ? ` (${goal.goal_type})` : "";
      scorerElement.innerHTML = `<span class="player">${goal.scorer_name} ${goal.goal_time}'${goalTypeText} ( 🦶${goal.assist_name})</span>`;

      homeScorersElement.appendChild(scorerElement);
    });
  } else {
    console.log("No home team goals found");
  }

  // Add away team scorers
  if (goals.away && goals.away.length > 0) {
    goals.away.forEach((goal) => {
      const scorerElement = document.createElement("div");
      scorerElement.className = "scorer";

      const goalTypeText = goal.goal_type ? ` (${goal.goal_type})` : "";
      scorerElement.innerHTML = `<span class="player">${goal.scorer_name} ${goal.goal_time}'${goalTypeText}, ( 🦶 ${goal.assist_name})</span>`;

      awayScorersElement.appendChild(scorerElement);
    });
  } else {
    console.log("No away team goals found");
  }
}

/**
 * Fetch match stats
 * @param {number} matchId - Match ID
 * @returns {Promise<Object>} - Match stats data
 */
async function fetchStats(matchId) {
  try {
    const response = await fetch(
      `../admin-tournoi/get_stats.php?match_id=${matchId}`
    );
    if (!response.ok) throw new Error("Error fetching stats");
    return await response.json();
  } catch (error) {
    console.error("Error fetching stats:", error);
    return {
      home_possession: 50,
      away_possession: 50,
      home_shots: 0,
      away_shots: 0,
      home_shots_on_target: 0,
      away_shots_on_target: 0,
      home_corners: 0,
      away_corners: 0,
      home_fouls: 0,
      away_fouls: 0,
    };
  }
}

/**
 * Update the match header section with tournament info
 * @param {Object} match - Match data
 */
function updateMatchHeader(match) {
  const competitionName = document.querySelector(".competition span");
  competitionName.textContent = `${match.Nom_match || "Tournament"} Round ${
    match.round || ""
  }`;

  // You could also update the logo if available
  // const competitionLogo = document.querySelector(".competition-logo img");
  // if (match.tournament_logo) competitionLogo.src = match.tournament_logo;
}

/**
 * Update the match info section with date, venue, etc.
 * @param {Object} match - Match data
 * @param {Object} referees - Referees data
 */
function updateMatchInfo(match, refereesData) {
  const details = document.querySelectorAll(".match-details .detail");

  // Date
  if (details[0]) {
    const dateElement = details[0].querySelector("span");
    dateElement.textContent = formatMatchDate(
      match.date_match,
      match.time_match
    );
  }

  // Venue
  if (details[1]) {
    const venueElement = details[1].querySelector("span");
    venueElement.textContent = match.staduim || "TBD";
  }

  // Referee
  if (details[2] && refereesData.referees) {
    const refereeElement = details[2].querySelector("span");
    refereeElement.textContent = refereesData.referees.main_referee || "TBD";
  }

  // Spectator count
  if (details[3]) {
    const spectatorElement = details[3].querySelector("span");
    spectatorElement.textContent = match.Nombre_spectateur || "0";
  }
}

/**
 * Update the match score section with team names, logos, and score
 * @param {Object} match - Match data
 */
function updateMatchScore(match) {
  // Home team
  const homeTeamName = document.querySelector(".home-team .team-name");
  const homeTeamLogo = document.querySelector(".home-team .team-logo img");
  homeTeamName.textContent = match.home_team || "Home Team";
  if (match.home_team_logo)
    homeTeamLogo.src = `../assets/${match.home_team_logo}`;


 // Update poll buttons
 const pollHomeTeamName = document.getElementById("home-team-name");
 const pollHomeTeamLogo = document.getElementById("home-team-logo");
 pollHomeTeamName.textContent = match.home_team || "Home Team";
 pollHomeTeamLogo.src = `../assets/${match.home_team_logo}`;



  // Away team
  const awayTeamName = document.querySelector(".away-team .team-name");
  const awayTeamLogo = document.querySelector(".away-team .team-logo img");
  awayTeamName.textContent = match.away_team || "Away Team";
  if (match.away_team_logo)
    awayTeamLogo.src = `../assets/${match.away_team_logo}`;

  

  // Update poll buttons
  const pollAwayTeamName = document.getElementById("away-team-name");
  const pollAwayTeamLogo = document.getElementById("away-team-logo");
  pollAwayTeamName.textContent = match.away_team || "Away Team";
  pollAwayTeamLogo.src = `../assets/${match.away_team_logo}`;



  // Score and status
  const scoreElement = document.querySelector(".score-display .score");
  const statusElement = document.querySelector(".score-display .match-status");

  // For completed matches, show actual score; for others show vs
  if (match.status === "completed") {
    scoreElement.textContent = `${match.home_score || 0} - ${
      match.away_score || 0
    }`;
    statusElement.textContent = "Full time";
  } else if (match.status === "in-progress") {
    scoreElement.textContent = `${match.home_score || 0} - ${
      match.away_score || 0
    }`;
    statusElement.textContent = "Live";
  } else {
    scoreElement.textContent = "vs";
    statusElement.textContent = "Upcoming";
  }
}

/**
 * Update the match stats section with actual DB data
 * @param {Object} stats - Match statistics from database
 */
function updateMatchStats(stats) {
  // Get the stats container
  const statsBody = document.querySelector(".stats-card .card-body");

  // Clear the existing stats
  statsBody.innerHTML = "";

  // Add possession stat
  const possessionItem = document.createElement("div");
  possessionItem.className = "stat-item";
  possessionItem.innerHTML = `
    <div class="stat-label">Ball possession</div>
    <div class="stat-bar">
      <div class="home-bar" style="width: ${stats.home_possession}%;">
        <span class="stat-value">${stats.home_possession}%</span>
      </div>
      <div class="away-bar" style="width: ${stats.away_possession}%;">
        <span class="stat-value">${stats.away_possession}%</span>
      </div>
    </div>
  `;
  statsBody.appendChild(possessionItem);

  // Add total shots stat
  const shotsItem = document.createElement("div");
  shotsItem.className = "stat-item";
  shotsItem.innerHTML = `
    <div class="stat-label">Total shots</div>
    <div class="stat-values">
      <div class="home-value">${stats.home_shots}</div>
      <div class="away-value">${stats.away_shots}</div>
    </div>
  `;
  statsBody.appendChild(shotsItem);

  // Add shots on target stat
  const shotsTargetItem = document.createElement("div");
  shotsTargetItem.className = "stat-item";
  shotsTargetItem.innerHTML = `
    <div class="stat-label">Shots on target</div>
    <div class="stat-values">
      <div class="home-value">${stats.home_shots_target}</div>
      <div class="away-value">${stats.away_shots_target}</div>
    </div>
  `;
  statsBody.appendChild(shotsTargetItem);

  // Add corners stat
  const cornersItem = document.createElement("div");
  cornersItem.className = "stat-item";
  cornersItem.innerHTML = `
    <div class="stat-label">Corners</div>
    <div class="stat-values">
      <div class="home-value">${stats.home_corners}</div>
      <div class="away-value">${stats.away_corners}</div>
    </div>
  `;
  statsBody.appendChild(cornersItem);

  // Add fouls stat
  const foulsItem = document.createElement("div");
  foulsItem.className = "stat-item";
  foulsItem.innerHTML = `
    <div class="stat-label">Fouls</div>
    <div class="stat-values">
      <div class="home-value">${stats.home_fouls}</div>
      <div class="away-value">${stats.away_fouls}</div>
    </div>
  `;
  statsBody.appendChild(foulsItem);

  // Add passes stat
  const passesItem = document.createElement("div");
  passesItem.className = "stat-item";
  passesItem.innerHTML = `
    <div class="stat-label">Passes</div>
    <div class="stat-values">
      <div class="home-value">${stats.home_passes}</div>
      <div class="away-value">${stats.away_passes}</div>
    </div>
  `;
  statsBody.appendChild(passesItem);
}

/**
 * Update the sidebar with other matches from the tournament
 * @param {Array} matches - All matches data
 * @param {number} currentMatchId - Current match ID to highlight
 */
function updateSidebarMatches(matches, currentMatchId) {
  const matchList = document.querySelector(".sidebar .match-list");
  let home_goals=0
  let away_goals=0
  fetchGoals(currentMatchId).then((data)=>{
    console.log("here we go")
    console.log(data)
    away_goals=data.away.length
    home_goals=data.home.length
    console.log(away_goals,home_goals)
  })
  

  // console.log("this the gaol saddddddddddddd");
  // console.log(goals_);
  if (!matchList) return;

  // Clear existing matches
  matchList.innerHTML = "";

  // Add matches to the sidebar
  matches.forEach((match) => {
    console.log('matchhh')
    console.log(match)
    if (match.status === "completed") {
      const isCurrentMatch = match.id_match === parseInt(currentMatchId);
      const matchItem = document.createElement("div");
      matchItem.className = `match-item${isCurrentMatch ? " highlighted" : ""}`;

      if (match.status === "scheduled" || match.status === "upcoming") {
        // // Upcoming match
        // matchItem.classList.add('upcoming');
        // matchItem.innerHTML = `
        //   <div class="match-teams">
        //       <div class="team">
        //           <img src="../assets/${match.home_team_logo}" alt="${match.home_team}">
        //           <span>${match.home_team}</span>
        //       </div>
        //   </div>
        //   <div class="match-teams">
        //       <div class="team">
        //           <img src="../assets/${match.away_team_logo}" alt="${match.away_team}">
        //           <span>${match.away_team}</span>
        //       </div>
        //   </div>
        //   <div class="match-time">
        //       <div>${new Date(match.date_match).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})}</div>
        //       <div>${new Date(`2000-01-01T${match.time_match}`).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit'})}</div>
        //   </div>
        // `;
      } else {
        // Completed or in-progress match
        matchItem.innerHTML = `
        <div class="match-teams">
            <div class="team">
                <img src="../assets/${match.home_team_logo}" alt="${
          match.home_team
        }">
                <span>${match.home_team}</span>
            </div>
            <div class="match-result">
                <span>${match.home_score || 0}</span>
            </div>
        </div>
        <div class="match-teams">
            <div class="team">
                <img src="../assets/${match.away_team_logo}" alt="${
          match.away_team
        }">
                <span>${match.away_team}</span>
            </div>
            <div class="match-result">
                <span>${match.away_score || 0}</span>
            </div>
        </div>
        <div class="match-status">${
          match.status === "in-progress" ? "LIVE" : "FT"
        }</div>
      `;
      }

      // Make each match item clickable to navigate to that match
      matchItem.style.cursor = "pointer";
      matchItem.addEventListener("click", () => {
        if (match.id_match !== parseInt(currentMatchId)) {
          window.location.href = `match-details.php?match_id=${match.id_match}`;
        }
      });

      matchList.appendChild(matchItem);
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
    const homeVoteButton = document.getElementById("vote-home");
    const drawVoteButton = document.getElementById("vote-draw");
    const awayVoteButton = document.getElementById("vote-away");
  
    const homeVotesElement = document.getElementById("home-votes");
    const drawVotesElement = document.getElementById("draw-votes");
    const awayVotesElement = document.getElementById("away-votes");
  
    // Fetch poll results
    async function fetchPollResults() {
      try {
        const params = getUrlParams();
        const matchId = params.match_id;

        const response = await fetch(`../handle_votes.php?match_id=${matchId}`);
        if (!response.ok) throw new Error("Error fetching poll results");
        const results = await response.json();
  
        // homeVotesElement.textContent = results.home || 0;
        // drawVotesElement.textContent = results.draw || 0;
        // awayVotesElement.textContent = results.away || 0;

        document.getElementById("home-odds").textContent = results.home || 0;
        document.getElementById("draw-odds").textContent = results.draw || 0;
        document.getElementById("away-odds").textContent = results.away || 0;
      } catch (error) {
        console.error("Error fetching poll results:", error);
      }
    }
  
    // Submit a vote
    async function submitVote(voteType) {
      try {
        const params = getUrlParams();
        const matchId = params.match_id;

        const formData = new URLSearchParams();
        formData.append("vote_type", voteType);
        formData.append("match_id", matchId);

        const response = await fetch("../handle_votes.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: formData.toString(),
        });
        if (!response.ok) throw new Error("Error submitting vote");
        await fetchPollResults(); // Refresh results after voting
      } catch (error) {
        console.error("Error submitting vote:", error);
      }
    }

    // Add event listeners for voting
    homeVoteButton.addEventListener("click", () => submitVote("home"));
    drawVoteButton.addEventListener("click", () => submitVote("draw"));
    awayVoteButton.addEventListener("click", () => submitVote("away"));
  
    // Initialize poll
    fetchPollResults();
});

