<?php
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = $bd;

    // Fetch all players for player selection
    $stmt = $pdo->query("SELECT p.id, (p.first_name || ' ' || p.last_name) as player_name, pos.position_name 
                         FROM players p 
                         LEFT JOIN player_position pos ON p.id_position = pos.id 
                         ORDER BY p.last_name");
    $availablePlayers = $stmt->fetchAll();

    // Fetch positions
    $posStmt = $pdo->query("SELECT id, position_name FROM player_position");
    $positions = $posStmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Team</title>
    <link rel="stylesheet" href="team_css.css">
</head>

<body>
    <div class="container">


        <div class="header">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
            <h1>Add New Team</h1>
        </div>

        <form action="add_team.php" method="POST" enctype="multipart/form-data">
            <div class="tabs">
                <div class="tab active" data-tab="team-info">Team Info</div>
                <div class="tab" data-tab="stadium">Stadium</div>
                <div class="tab" data-tab="contact">Contact</div>
                <div class="tab" data-tab="staff">Staff</div>
                <div class="tab" data-tab="players">Players</div>
                <div class="tab" data-tab="review">Review</div>
            </div>

            <!-- Team Info Tab -->
            <div class="tab-content active" id="team-info">
                <div class="form-section">
                    <h2>Team Information</h2>

                    <div class="logo-upload">
                        <div class="logo-preview">
                            <img id="preview-img" src="/placeholder.svg" style="display: none;">
                            <div class="icon" id="icon-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </div>
                        </div>
                        <label for="team_logo" class="upload-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px; vertical-align: middle;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            Upload Logo
                        </label>
                        <input type="file" id="team_logo" name="team_logo" accept="image/*" style="display: none;">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_name" class="required">Team Name</label>
                            <input type="text" id="team_name" name="team_name" placeholder="Enter team name" required>
                        </div>

                        <div class="form-group">
                            <label for="founded_year">Founded Year</label>
                            <input type="number" id="founded_year" name="founded_year" min="1800" max="<?php echo date('Y'); ?>" placeholder="Enter founded year">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" placeholder="Enter country">
                        </div>

                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="Enter city">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="primary_color">Primary Color</label>
                            <div style="display: flex; align-items: center;">
                                <input type="color" id="primary_color" name="primary_color" value="#f0b941" style="width: 50px; height: 40px; padding: 0;">
                                <div id="primary-color-preview" class="color-preview" style="background-color: #f0b941;"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="secondary_color">Secondary Color</label>
                            <div style="display: flex; align-items: center;">
                                <input type="color" id="secondary_color" name="secondary_color" value="#2563eb" style="width: 50px; height: 40px; padding: 0;">
                                <div id="secondary-color-preview" class="color-preview" style="background-color: #2563eb;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-primary" id="team-info-next">Next</button>
                </div>
            </div>

            <!-- Stadium Tab -->
            <div class="tab-content" id="stadium">
                <div class="form-section">
                    <h2>Stadium Information</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="home_stadium">Home Stadium</label>
                            <input type="text" id="home_stadium" name="home_stadium" placeholder="Enter home stadium name">
                        </div>

                        <div class="form-group">
                            <label for="stadium_capacity">Stadium Capacity</label>
                            <input type="number" id="stadium_capacity" name="stadium_capacity" min="0" placeholder="Enter stadium capacity">
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" id="stadium-prev">Previous</button>
                    <button type="button" class="btn btn-primary" id="stadium-next">Next</button>
                </div>
            </div>

            <!-- Contact Tab -->
            <div class="tab-content" id="contact">
                <div class="form-section">
                    <h2>Contact Information</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" id="website" name="website" placeholder="Enter website URL">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter email address">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1 0 100%;">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" placeholder="Enter full address"></textarea>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" id="contact-prev">Previous</button>
                    <button type="button" class="btn btn-primary" id="contact-next">Next</button>
                </div>
            </div>

            <!-- Staff Tab -->
            <div class="tab-content" id="staff">
                <div class="form-section">
                    <h2>Staff Information</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="head_coach">Head Coach</label>
                            <input type="text" id="head_coach" name="head_coach" placeholder="Enter head coach name">
                        </div>

                        <div class="form-group">
                            <label for="assistant_coach">Assistant Coach</label>
                            <input type="text" id="assistant_coach" name="assistant_coach" placeholder="Enter assistant coach name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_manager">Team Manager</label>
                            <input type="text" id="team_manager" name="team_manager" placeholder="Enter team manager name">
                        </div>

                        <div class="form-group">
                            <label for="physiotherapist">Physiotherapist</label>
                            <input type="text" id="physiotherapist" name="physiotherapist" placeholder="Enter physiotherapist name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1 0 100%;">
                            <label for="history">Team History</label>
                            <textarea id="history" name="history" placeholder="Enter team history and achievements"></textarea>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" id="staff-prev">Previous</button>
                    <button type="button" class="btn btn-primary" id="staff-next">Next</button>
                </div>
            </div>

            <!-- Players Tab -->
            <div class="tab-content" id="players">
                <div class="form-section">
                    <h2>Team Players</h2>

                    <div id="player-list" class="player-list">
                        <!-- This will be populated by JavaScript -->
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1 0 100%;">
                            <label for="player_select">Available Players</label>
                            <select id="player_select" style="width: 70%; display: inline-block;">
                                <option value="">Select player to add</option>
                                <?php foreach ($availablePlayers as $player): ?>
                                    <option value="<?= $player['id'] ?>"
                                        data-name="<?= htmlspecialchars($player['player_name']) ?>"
                                        data-position="<?= htmlspecialchars($player['position_name'] ?? '') ?>">
                                        <?= htmlspecialchars($player['player_name']) ?>
                                        <?= !empty($player['position_name']) ? '- ' . htmlspecialchars($player['position_name']) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" id="add-player" class="btn btn-sm" style="display: inline-block; margin-left: 10px;">
                                Add Player
                            </button>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" id="players-prev">Previous</button>
                    <button type="button" class="btn btn-primary" id="players-next">Next</button>
                </div>
            </div>

            <!-- Review Tab -->
            <div class="tab-content" id="review">
                <div class="form-section">
                    <h2>Review Team Information</h2>

                    <div id="review-content" style="margin-bottom: 30px;">
                        <!-- This will be filled by JavaScript -->
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" id="review-prev">Previous</button>
                    <button type="submit" name="submit_team" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
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

            // Next and Previous buttons
            document.getElementById('team-info-next').addEventListener('click', () => setActiveTab('stadium'));
            document.getElementById('stadium-prev').addEventListener('click', () => setActiveTab('team-info'));
            document.getElementById('stadium-next').addEventListener('click', () => setActiveTab('contact'));
            document.getElementById('contact-prev').addEventListener('click', () => setActiveTab('stadium'));
            document.getElementById('contact-next').addEventListener('click', () => setActiveTab('staff'));
            document.getElementById('staff-prev').addEventListener('click', () => setActiveTab('contact'));
            document.getElementById('staff-next').addEventListener('click', () => setActiveTab('players'));
            document.getElementById('players-prev').addEventListener('click', () => setActiveTab('staff'));
            document.getElementById('players-next').addEventListener('click', () => {
                // Generate review content
                generateReview();
                setActiveTab('review');
            });
            document.getElementById('review-prev').addEventListener('click', () => setActiveTab('players'));

            // Logo upload preview
            const logoInput = document.getElementById('team_logo');
            const previewImg = document.getElementById('preview-img');
            const iconPlaceholder = document.getElementById('icon-placeholder');

            logoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        iconPlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Color preview
            const primaryColorInput = document.getElementById('primary_color');
            const secondaryColorInput = document.getElementById('secondary_color');
            const primaryColorPreview = document.getElementById('primary-color-preview');
            const secondaryColorPreview = document.getElementById('secondary-color-preview');

            primaryColorInput.addEventListener('input', function() {
                primaryColorPreview.style.backgroundColor = this.value;
            });

            secondaryColorInput.addEventListener('input', function() {
                secondaryColorPreview.style.backgroundColor = this.value;
            });

            // Player management
            let playerCount = 0;
            const playerList = document.getElementById('player-list');
            const addPlayerBtn = document.getElementById('add-player');

            function addPlayer() {
                const playerItem = document.createElement('div');
                playerItem.className = 'player-item';
                playerItem.dataset.id = playerCount;

                playerItem.innerHTML = `
                    <button type="button" class="remove-player" data-id="${playerCount}">×</button>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="player_name_${playerCount}">Player Name</label>
                            <input type="text" id="player_name_${playerCount}" name="player_name[]" placeholder="Enter player name">
                        </div>
                        
                        <div class="form-group">
                            <label for="player_position_${playerCount}">Position</label>
                            <select id="player_position_${playerCount}" name="player_position[]">
                                <option value="">Select position</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                                <option value="Defender">Defender</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Forward">Forward</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="player_number_${playerCount}">Jersey Number</label>
                            <input type="number" id="player_number_${playerCount}" name="player_number[]" min="1" max="99" placeholder="Enter jersey number">
                        </div>
                        
                        <div class="form-group">
                            <label for="player_nationality_${playerCount}">Nationality</label>
                            <input type="text" id="player_nationality_${playerCount}" name="player_nationality[]" placeholder="Enter nationality">
                        </div>
                    </div>
                `;

                playerList.appendChild(playerItem);

                // Add event listener to remove button
                const removeBtn = playerItem.querySelector('.remove-player');
                removeBtn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const playerToRemove = document.querySelector(`.player-item[data-id="${id}"]`);
                    playerList.removeChild(playerToRemove);
                });

                playerCount++;
            }

            addPlayerBtn.addEventListener('click', addPlayer);

            // Add initial player
            addPlayer();

            // Generate Review Content
            function generateReview() {
                const reviewContent = document.getElementById('review-content');

                // Get all form values
                const teamName = document.getElementById('team_name').value || 'Not provided';
                const foundedYear = document.getElementById('founded_year').value || 'Not provided';
                const country = document.getElementById('country').value || 'Not provided';
                const city = document.getElementById('city').value || 'Not provided';
                const homeStadium = document.getElementById('home_stadium').value || 'Not provided';
                const headCoach = document.getElementById('head_coach').value || 'Not provided';

                // Get players
                const playerNames = document.querySelectorAll('input[name="player_name[]"]');
                const playerPositions = document.querySelectorAll('select[name="player_position[]"]');
                let playersHTML = '';

                for (let i = 0; i < playerNames.length; i++) {
                    if (playerNames[i].value) {
                        const position = playerPositions[i].value || 'Not specified';
                        playersHTML += `<li>${playerNames[i].value} - ${position}</li>`;
                    }
                }

                if (!playersHTML) {
                    playersHTML = '<li>No players added</li>';
                }

                // Create review HTML
                let reviewHTML = `
                    <div style="background-color: var(--input-bg); padding: 20px; border-radius: 8px;">
                        <h3 style="margin-bottom: 15px; color: var(--primary-color);">${teamName}</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                            <div>
                                <p><strong>Founded:</strong> ${foundedYear}</p>
                                <p><strong>Location:</strong> ${city}, ${country}</p>
                                <p><strong>Home Stadium:</strong> ${homeStadium}</p>
                                <p><strong>Head Coach:</strong> ${headCoach}</p>
                            </div>
                            <div>
                                <p><strong>Team Players:</strong></p>
                                <ul style="list-style-type: disc; padding-left: 20px;">
                                    ${playersHTML}
                                </ul>
                            </div>
                        </div>
                        <p style="margin-top: 20px; color: var(--text-muted);">Please review all information before submitting. Once submitted, you can still edit the team information later.</p>
                    </div>
                `;

                reviewContent.innerHTML = reviewHTML;
            }
        });
    </script>
</body>

</html>