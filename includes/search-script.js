console.log("hello")
async function getData(query){
    let result = await fetch(`../includes/search-serv.php?query=${query}`, {
    method: "GET",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded"
    }
    });
    let data = await result.json();
    return data;
}

document.addEventListener('DOMContentLoaded', async function() {
  // Elements
  const searchBar = document.querySelector('.search-bar');
  const searchInput = document.querySelector('.search-input');
  const clearButton = document.querySelector('.clear-button');
  const searchResults = document.querySelector('.search-results');
  const filterButtons = document.querySelectorAll('.filter-button');
  const resultsSection = document.querySelector('.results-section');

  searchInput.value="";
//   let data = await getData("");

  // Sample data
//   const data = {
//     teams: [
//       { id: 1, name: 'FSV Zwickau', subtitle: 'Regionalliga Northeast', icon: '' },
//       { id: 2, name: 'FS Metta/LU', subtitle: 'Virsliga', icon: '' },
//       { id: 3, name: 'FSV 08 Bissingen', subtitle: 'Oberliga Baden-Wurttemberg', icon: '' },
//       { id: 4, name: 'FSV Fernwald', subtitle: 'Oberliga Hessen', icon: '' },
//       { id: 5, name: 'FSV Frankfurt', subtitle: 'Regionalliga Southwest', icon: '' },
//       { id: 6, name: 'FSV Hollenbach', subtitle: 'Oberliga Baden-Wurttemberg', icon: '' },
//       { id: 7, name: 'FSV Luckenwalde', subtitle: 'Regionalliga Northeast', icon: '' }
//     ],
//     players: [
//       { id: 1, name: 'Franz Schmidt', subtitle: 'FSV Zwickau', icon: '' },
//       { id: 2, name: 'Felix Müller', subtitle: 'FSV Frankfurt', icon: '' }
//     ],
//     matches: [
//       { id: 1, name: 'FSV Zwickau vs FSV Frankfurt', subtitle: 'Regionalliga - Tomorrow', icon: '' },
//       { id: 2, name: 'FS Metta/LU vs FSV Hollenbach', subtitle: 'Friendly - Next Week', icon: '' }
//     ],
//     leagues: [
//       { id: 1, name: 'Regionalliga Northeast', subtitle: 'Germany - 4th Tier', icon: '' },
//       { id: 2, name: 'Oberliga Baden-Wurttemberg', subtitle: 'Germany - 5th Tier', icon: '' }
//     ]
//   };
  
    // fetch("search-serv.php",{
    //     method: "POST",
    //     headers: {
    //     "Content-Type": "application/x-www-form-urlencoded"
    //     },
    //     body: "key=s"
    // }
    // ).then(response=>response.json())
    // .then(data => console.log(data));





  let activeFilter = 'All';
  let currentSearchTerm = '';
  
  // Toggle search bar active state
  searchBar.addEventListener('click', function() {
    searchBar.classList.add('active');
    searchResults.classList.add('visible');
    searchInput.focus();
    // renderResults();
  });
  
  // Close search when clicking outside
  document.addEventListener('click', function(event) {
    const isClickInside = searchBar.contains(event.target) || searchResults.contains(event.target);
    if (!isClickInside) {
      searchBar.classList.remove('active');
      searchResults.classList.remove('visible');
    }
  });
  
  // Handle input changes
  searchInput.addEventListener('input', function() {
    currentSearchTerm = searchInput.value.trim().toLowerCase();
    if (currentSearchTerm.length > 0) {
      clearButton.classList.add('visible');
    } else {
      clearButton.classList.remove('visible');
    }
    renderResults(currentSearchTerm);
  });
  
  // Clear search input
  clearButton.addEventListener('click', function(event) {
    event.stopPropagation();
    searchInput.value = '';
    currentSearchTerm = '';
    clearButton.classList.remove('visible');
    // renderResults();
    searchInput.focus();
    resultsSection.innerHTML = "";
  });
  
  // Filter buttons
  filterButtons.forEach(button => {
    button.addEventListener('click', function() {
      filterButtons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      activeFilter = button.textContent;
      searchInput.focus();
      renderResults(searchInput.value);

    //   renderResults();
    });
  });
  
  // Render filtered results
  async function renderResults(query) {
    let data = await getData(query);

    // let filteredData = [];
    
    // if (activeFilter === 'All') {
    // //   // Combine all data when "All" is selected
    //   filteredData = [
    //     ...data.teams, 
    //     ...data.players, 
    //     ...data.matches, 
    //     // ...data.leagues
    //   ];
    // } else if (activeFilter === 'Teams') {
    //   filteredData = data.teams;
    // } else if (activeFilter === 'Players') {
    //   filteredData = data.players;
    // } else if (activeFilter === 'Matches') {
    //   filteredData = data.matches;
    // } else if (activeFilter === 'Leagues') {
    //   filteredData = data.leagues;
    // }
    console.log(data)
    if(activeFilter === 'All'){
        renderAll(data);
    }
    if(activeFilter === 'Teams'){
        renderTeams(data.teams);
    }
    else if(activeFilter === 'Players'){
        renderPlayers(data.players);
    }
    // Apply search term filter if exists
    // if (currentSearchTerm) {
    //   filteredData = filteredData.filter(item => 
    //     item.name.toLowerCase().includes(currentSearchTerm) || 
    //     item.subtitle.toLowerCase().includes(currentSearchTerm)
    //   );
    // }
    
    // Create HTML for results

  }
  
  // Initial render
//   renderResults();
function renderAll(data){
    if (data.players.length === 0 || data.teams.length === 0) {
        resultsSection.innerHTML = '<div class="no-results">No results found</div>';
        return;
    } 
    let resultsHTML = '';
    data.teams.forEach(team => {
      resultsHTML += `
        <a class="result-item" href="../teams/team-info.php?idTeam=${team.id}">
          <div class="result-icon">
            <img src="../assets/${team.logo_path}" alt="${team.team_name}" >
          </div>
          <div class="result-info">
            <div class="result-title">${team.team_name}</div>
            <div class="result-subtitle">${team.city}</div>
          </div>
        </a>
      `;
    });
    data.players.forEach(player => {
        console.log(player)
        resultsHTML += `
          <a class="result-item" href="../player/player-info.php?id=${player.id}">
            <div class="result-icon">
            
            
              <img src="${player.player_photo}" alt="${player.last_name + ' ' + player.first_name}" >
            </div>
            <div class="result-info">
              <div class="result-title">${player.last_name + ' ' + player.first_name}</div>
              <div class="result-subtitle">${player.team_name}</div>
            </div>
          </a>
        `;
      });
    resultsSection.innerHTML = resultsHTML;
}
function renderTeams(teams){
    if (teams.length === 0) {
        resultsSection.innerHTML = '<div class="no-results">No results found</div>';
        return;
    } 
    let resultsHTML = '';
    teams.forEach(team => {
      resultsHTML += `
        <a class="result-item" href="../teams/team-info.php?idTeam=${team.id}">
          <div class="result-icon">
            <img src="../assets/${team.logo_path}" alt="${team.team_name}" >
          </div>
          <div class="result-info">
            <div class="result-title">${team.team_name}</div>
            <div class="result-subtitle">${team.city}</div>
          </div>
        </a>
      `;
    });
    resultsSection.innerHTML = resultsHTML;
}
function renderPlayers(players){
    if (players.length === 0) {
        resultsSection.innerHTML = '<div class="no-results">No results found</div>';
        return;
    } 
    let resultsHTML = '';
    players.forEach(player => {
      resultsHTML += `
        <a class="result-item" href="../player/player-info.php?id=${player.id}">
          <div class="result-icon">
            <img src="${player.player_photo}" alt="${player.last_name + ' ' + player.first_name}" >
          </div>
          <div class="result-info">
            <div class="result-title">${player.last_name + ' ' + player.first_name}</div>
            <div class="result-subtitle">${player.team_name}</div>
          </div>
        </a>
      `;
    });
    resultsSection.innerHTML = resultsHTML;
}

});

