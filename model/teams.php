<?php
    include 'connection.php';

    $condition = "";
    $keyword = "";
    if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        //* QUESTION #6.d TODO here
        $keyword = $_GET['keyword'];
        $condition = "WHERE t.name LIKE '%$keyword%' OR t.abbr LIKE '%$keyword%'";
    }

    //* QUESTION #6.a TODO here
    // Run query statement
    // return MYSQLI RESULT OBJECT
    $statement = "SELECT t.*, COUNT(p.name) AS total_players FROM teams_tbl AS t INNER JOIN players_tbl AS p ON t.id = p.team_id " . $condition . " GROUP BY t.id ORDER BY t.id;";

    $teamResults = mysqli_query($conn, $statement);
    //* QUESTION #6.b TODO here
    // GET TEAMS COUNT THROUGH RESULT SET RETURNED 
    $teamCount = mysqli_num_rows($teamResults);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/custom.css" rel="stylesheet">
        <title>NBA Players</title>
    </head>
    <body>
        <?php
            include_once 'partials/header.php';
        ?>
        <div class="container-fluid mt-3">
            <div class="container">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Teams</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="container-fluid mt-4">
            <div class="container">
                <!-- //* QUESTION #6.b MAKE THIS TEAM COUNT DYNAMIC BASED ON THE TOTAL TEAMS THAT HAVE AT LEAST 1 PLAYER STORED ON THE DATABASE -->
                <h3 class="mb-3">List of active teams (<?php echo $teamCount;?>)</h3>
                <form class="mb-3" action="?" method="GET">
                    <div class="d-flex flex-row align-items-center">
                        <div class="form-group me-2">
                            <label>Search</label>
                        </div>
                        <div class="form-group me-2">
                            <input type="text" class="form-control" style="width: 350px;" placeholder="Search team name or abbreviation..." name="keyword" value="<?php echo $keyword;?>">
                        </div>
                        <div class="form-group me-2">
                            <label></label>
                            <button class="btn btn-primary">Search</button>
                        </div>
                        <?php
                            if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                                echo '
                                    <div class="form-group">
                                        <label></label>
                                        <a class="btn btn-secondary" href="teams.php">Clear search</a>
                                    </div>
                                ';
                            }
                        ?>
                    </div>
                </form>
                <table class="table table-light table-striped">
                    <thead>
                        <tr>
                            <th class="table-dark" scope="col">#</th>
                            <th class="table-dark" scope="col">Abbreviation</th>
                            <th class="table-dark" scope="col">Name</th>
                            <th class="table-dark text-center" scope="col">Total Players</th>
                            <th class="table-dark" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                            if($teamCount) {
                                $i = 1;
                                while($team = mysqli_fetch_array($teamResults, MYSQLI_BOTH)) {
                                    $teamLogo = file_exists('assets/teams/' . $team['abbr'] . '.png') ? 'assets/teams/' . $team['abbr'] : 'assets/logo';
                                    //* QUESTION #6.c TODO here
                                    // SHOW ALL TEAMS HERE USING TEMPLATES ABOVE
                                    echo   '<tr>
                                                <th scope="row">' . $i . '</th>
                                                <td><img src="' . $teamLogo . '.png" height="36">' . $team['abbr'] . '</td>
                                                <td>' . $team['name'] . '</td>
                                                <td class="text-center">' . $team['total_players'] . '</td>
                                                <td class="text-center">
                                                    <a class="btn btn-primary" href="team_profile.php?id=' . $team['id'] . '">View Team</a>
                                                </td>
                                            </tr>';
                                    $i++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</html>