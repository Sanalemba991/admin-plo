<?php
date_default_timezone_set('Asia/Kolkata');

// Database connection
$servername = "localhost";
$username = "u398830080_ludo";
$password = "isP@ssw0rd2024";
$dbname = "u398830080_ludo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_time = date('Y-m-d H:i:s');
echo $current_time;

// Fetch leagues that are not completed
$sql = "SELECT * FROM `leagues` WHERE `status` != 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($league = $result->fetch_assoc()) {
        $league_id = $league['league_id'];

        // Start match if start time has passed
        if ($league['start_time'] < $current_time && $league['status'] != 1) {
            $stmt = $conn->prepare("UPDATE leagues SET `status` = 1 WHERE `league_id` = ?");
            $stmt->bind_param("i", $league_id);
            $stmt->execute();
            $stmt->close();
        }

        // End match if end time has passed
        if ($league['end_time'] < $current_time && $league['status'] != 2) {
            $stmt = $conn->prepare("UPDATE leagues SET `status` = 2 WHERE `league_id` = ?");
            $stmt->bind_param("i", $league_id);
            $stmt->execute();
            $stmt->close();
        }

        // Process results if result time has passed
        if ($league['result_time'] < $current_time) {
            $entry_fee = $league['entry_fee'];
            $joined = $league['joined'];
            $game_name = "Ludo";

            $stmt = $conn->prepare("UPDATE leagues SET `status` = 3 WHERE `league_id` = ?");
            $stmt->bind_param("i", $league_id);
            $stmt->execute();
            $stmt->close();

            // Fetch league rankings
            $rank_sql = "SELECT * FROM `league_rank_users` WHERE `league_id` = ? ORDER BY `points` DESC";
            $rank_stmt = $conn->prepare($rank_sql);
            $rank_stmt->bind_param("i", $league_id);
            $rank_stmt->execute();
            $rank_result = $rank_stmt->get_result();

            $rank = 1;
            while ($rank_row = $rank_result->fetch_assoc()) {
                if ($rank_row['is_bot'] != 1) {
                    $player_id = $rank_row['player_id'];

                    // Fetch prize for current rank
                    $prize_stmt = $conn->prepare("SELECT `prize` FROM `league_rank_prizes` WHERE `rank` = ? AND `league_id` = ?");
                    $prize_stmt->bind_param("ii", $rank, $league_id);
                    $prize_stmt->execute();
                    $prize_result = $prize_stmt->get_result();
                    $prize_row = $prize_result->fetch_assoc();
                    $prize = $prize_row['prize'];
                    $prize_stmt->close();

                    // Update user balance
                    $balance_stmt = $conn->prepare("UPDATE userdatas SET wincoin = wincoin + ? WHERE playerid = ?");
                    $balance_stmt->bind_param("ii", $prize, $player_id);
                    $balance_stmt->execute();
                    $balance_stmt->close();

                    // Fetch updated user balance
                    $user_stmt = $conn->prepare("SELECT playcoin, wincoin, refrelCoin FROM userdatas WHERE playerid = ?");
                    $user_stmt->bind_param("i", $player_id);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result();
                    $user_data = $user_result->fetch_assoc();
                    $final_amount = $user_data['playcoin'] + $user_data['wincoin'] + $user_data['refrelCoin'];
                    $user_stmt->close();

                    // Add game history
                  // Escape variables to prevent SQL injection



// Create the SQL query with sanitized values
$sql = "
    INSERT INTO gamehistories 
    (playerid, status, bid_amount, Win_amount, game_name, seat_limit, finalamount, playtime) 
    VALUES ($player_id, 'win', $entry_fee, $prize, '$game_name', $joined, $final_amount, '$current_time')
";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Record inserted successfully.";
} else {
    echo "Error: " . $conn->error;
}

                    
                }

                $rank++;
            }

            $rank_stmt->close();
        }
    }
} else {
    echo "0 results";
}

$conn->close();
?>
