<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="<?php echo URLROOT; ?>/assets/brand.png" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dashboard.css" type="text/css">
    <script src="https://kit.fontawesome.com/6e1faf1eda.js" crossorigin="anonymous"></script>
    <script src="<?php echo URLROOT; ?>/js/script.js" defer></script>
</head>

<body>
    <header>
        <h2>Data<img src="<?php echo URLROOT; ?>/assets/brand.png" alt=brand />are</h2>
        <nav>
            <a href="#"><i class="fa-solid fa-house"></i> Home</a>
            <?php
            if ($data) {
                if ($data->role === 0) {
                    echo '<a href="#myTeams"><i class="fa-solid fa-user-group"></i>Teams</a><a href="#myProjects"><i class="fa-solid fa-bars-progress"></i>Projects</a>';
                } else if ($data->role === 3) {
                    echo '<a href="./projects.php"><i class="fa-solid fa-bars-progress"></i>Projects</a>';
                } else {
                    echo '';
                }
            } else {
                echo 'User not found';
            }

            ?>
            <a href="#" onclick="openMyPopup()"><i class="fa-solid fa-user"></i> Profile</a>
            <a href="<?php echo URLROOT; ?>/users/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> LogOut</a>
        </nav>
    </header>
    <div id="myPopup" class="popup">
        <div class="popup-content">
            <div class="popup-header">
                <?php
                echo "
                        <h2>{$data->fname} {$data->lname}</h2>";
                ?>
                <span class="close" onclick="closeMyPopup()">&times;</span>
            </div>
            <div class="popup-body">
                <?php
                echo "
                    <h3>Personal information:</h3> <p>Birthdate : ";
                echo ($data->birthdate === NULL) ? 'empty' : '' . $data->birthdate . '';
                echo "</p><p>Phone Number : {$data->tel}</p>
                    <p>Adress : ";
                echo ($data->adress === NULL) ? 'empty' : '' . $data->adress . '';
                echo "
                    </p><h3 class=pro>Professional information:</h3>
                    <p>Email : {$data->email}</p>
                    <p>Service : {$data->service}</p>
                    <p>Role : 
                ";
                echo ($data['role'] === 0) ? "Member" : (($data['role'] === 1) ? "Product Owner" : (($data['role'] === 2) ? "Scrum Master" : "Admin"));
                echo "</p>";
                ?>
                <div class="popup-footer">
                    <a class="delete" href="#">Delete</a>
                    <a href="#">Modify</a>
                </div>
            </div>
        </div>
    </div>

    <main>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user['role'] === 3) {
            $userID = $_POST['userID'];
            $newRole = $_POST['newRole'];
            $stmt = $conn->prepare("UPDATE users SET role = :newRole WHERE id = :userID");
            $stmt->bindParam(':newRole', $newRole);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            header('Location: dashboard.php?success=true');
            exit();
        }
        if (isset($_POST['setSM'])) {
            $teamId = $_POST['teamId'];
            $newSM = $_POST['newSM'];
            $stmt3 = $conn->prepare("UPDATE teams SET scrumMaster = :newSM WHERE id = :teamId");
            $stmt3->bindParam(':teamId', $teamId, PDO::PARAM_INT);
            $stmt3->bindParam(':newSM', $newSM, PDO::PARAM_INT);
            $stmt3->execute();
            header('Location: dashboard.php');
            exit();
        }
        if (isset($_POST['setTeam'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $projectId = $_POST['newP'];
            $scrumMaster = $_POST['scrumMaster'];

            $query = "INSERT INTO teams (name, description, projectId, scrumMaster) 
            VALUES (:name, :description, :projectId, :scrumMaster)";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':projectId', $projectId);
            $stmt->bindParam(':scrumMaster', $scrumMaster);

            $stmt->execute();
            header('Location: dashboard.php');
            exit();
        }

        ?>
        <div class="hero">
            <?php
            echo '<h2 class=title>Hello ' . ucfirst($user['fname']) . ' ' . ucfirst($user['lname']) . '</h2>';

            if ($user['role'] === 3) {
                echo '<h4 class=sub-title>All Users : </h4>';
                echo '<div class=cards>';

                $query = "SELECT * from users WHERE users.role != 3";

                $stmt = $conn->prepare($query);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user) {
                    echo "<div class=card>
                                <div class=card-top>
                                    <h4>" . substr($user['fname'], 0, 1) . "" . substr($user['lname'], 0, 1) . "</h4>
                                    <h2>" . $user['fname'] . " " . $user['lname'] . "</h2>
                                </div>
                                <div class=card-body>
                                    <p>" . $user['email'] . "</p>
                                    <p>Tel: " . $user['tel'] . "</p>
                                </div>
                                <div class=card-btm>
                                    <a href=#><i class='fa-solid fa-location-dot' style='margin-right:5px;'></i>" . $user['service'] . "</a>";
                    echo ($user['role'] === 0) ? "<a onclick='openPopup(" . $user["id"] . ")'>Member <i class='fa-solid fa-pencil'></i></a>" : (($user['role'] === 1) ? "<a onclick='openPopup(" . $user["id"] . ")'>Product Owner <i class='fa-solid fa-pencil'></i></a>" : (($user['role'] === 2) ? "<a onclick='openPopup(" . $user["id"] . ")'>Scrum Master <i class='fa-solid fa-pencil'></i></a>" : ""));
                    echo "</div>
                            </div>";
                }
                echo '</div>';
            } else if ($user['role'] === 1) {
                echo '<div class="add">
                    <h4 class=sub-title>All Projects : </h4>
                    <a href="./addProject.php?productOwner=' . $user['id'] . '">+ New Project</a>
                </div>';

                $query = "SELECT * from projects WHERE productOwner = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $user['id'], PDO::PARAM_STR);
                $stmt->execute();
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<div class=fullPage><table class='teamTable'>
                        <tr>
                            <th>Project Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>";
                foreach ($projects as $project) {
                    echo "
                        <tr>
                            <td>{$project['name']}</td>
                            <td>{$project['date_start']}</td>
                            <td>{$project['date_end']}</td>
                            <td>{$project['description']}</td>
                            <td>";
                    echo ($project['status'] === 0) ? '<p  class=active>● Active</p>' : '<p class=done>✔ Done</p></td>';
                    echo "<td class='actions'><a href='./modifyProject.php?projectId=" . $project['id'] . "'>Modify</a> <a href='./deleteProject.php?deleteOne=" . $project['id'] . "'>Delete</a></td>";
                }

                echo "</table><h4 class=sub-title id=myTeams>Teams</h4> <div class='fullPage'>
                <table class='teamTable'>
                    <tr>
                        <th>Team Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Project Name</th>
                        <th>Action</th>
                    </tr>";
                $query1 = "SELECT teams.*
                    FROM teams
                    JOIN projects ON teams.projectId = projects.id
                    JOIN users ON projects.productOwner = users.id
                    WHERE teams.scrumMaster IS NULL
                    AND users.id = :id";
                $stmt1 = $conn->prepare($query1);
                $stmt1->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $stmt1->execute();
                $teams = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                foreach ($teams as $team) {
                    $query2 = "SELECT * from projects WHERE id = :id";
                    $stmt2 = $conn->prepare($query2);
                    $stmt2->bindParam(':id', $team['projectId'], PDO::PARAM_INT);

                    $stmt2->execute();
                    $teamP = $stmt2->fetch(PDO::FETCH_ASSOC);

                    echo "
                        <tr>
                            <td>{$team['name']}</td>
                            <td>{$team['description']}</td>
                            <td>{$team['created_at']}</td>
                            <td>{$teamP['name']}</td>
                            <td><a href=# onclick='openSMPopup(" . $team["id"] . ")'>Set Scrum Master</a></td>
                        </tr>";
                }
            } else if ($user['role'] === 2) {
                echo '<div class="add">
                    <h4 class=sub-title>All Teams : </h4>
                    <a onclick="openTeamPopup(' . $user['id'] . ')">+ New Team</a>
                </div>';

                $query = "SELECT * from teams WHERE scrumMaster = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $user['id'], PDO::PARAM_STR);
                $stmt->execute();
                $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<div class=fullPage><table class='teamTable'>
                        <tr>
                            <th>Team Name</th>
                            <th>Created At</th>
                            <th>Description</th>
                            <th>Project Name</th>
                            <th>Actions</th>
                        </tr>";
                foreach ($teams as $team) {
                    $queryProject = "SELECT * FROM projects WHERE id = :projectId";
                    $stmtProject = $conn->prepare($queryProject);
                    $stmtProject->bindParam(':projectId', $team['projectId'], PDO::PARAM_INT);
                    $stmtProject->execute();
                    $project = $stmtProject->fetch(PDO::FETCH_ASSOC);

                    echo "
                        <tr>
                            <td>{$team['name']}</td>
                            <td>{$team['created_at']}</td>
                            <td>{$team['description']}</td>";
                    if ($team['projectId'] === NULL) {
                        echo "<td>-</td>";
                    } else {
                        echo "<td>{$project['name']}</td>";
                    }
                    echo "<td class='actions'><a href='./modifyTeam.php?teamId=" . $team['id'] . "'>Modify</a> <a href='./members.php?teamId=" . $team['id'] . "'>Members</a> <a href='./deleteTeam.php?teamId=" . $team['id'] . "'>Delete</a></td>";
                }
            } else {

                $query = "SELECT users.*, team_user.team_id AS teamId, teams.name AS team_name, teams.description AS team_description FROM users JOIN team_user ON users.id = team_user.user_id JOIN teams ON team_user.team_id = teams.id WHERE users.email = :email";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);


                if (empty($user)) {
                    echo '<div class=fullPage><h4>No Teams <br>No Projects</h4></div>';
                } else {
                    echo "<h4 class=sub-title id=myTeams>Teams</h4> <div class='fullPage'>";
                    echo "<table class='teamTable'>
                    <tr>
                        <th>Team Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Project Name</th>
                        <th>Scrum Master</th>
                    </tr>";

                    $userId = $user['id'];

                    $queryUserTeam = "SELECT team_id FROM team_user WHERE user_id = :userId";
                    $stmtUserTeam = $conn->prepare($queryUserTeam);
                    $stmtUserTeam->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmtUserTeam->execute();
                    $userTeams = $stmtUserTeam->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($userTeams as $userTeam) {
                        $teamId = $userTeam['team_id'];

                        $queryTeam = "SELECT * FROM teams WHERE id = :teamId";
                        $stmtTeam = $conn->prepare($queryTeam);
                        $stmtTeam->bindParam(':teamId', $teamId, PDO::PARAM_INT);
                        $stmtTeam->execute();
                        $team = $stmtTeam->fetch(PDO::FETCH_ASSOC);

                        $teamProjectId = $team['projectId'];
                        $queryProject = "SELECT * FROM projects WHERE id = :projectId";
                        $stmtProject = $conn->prepare($queryProject);
                        $stmtProject->bindParam(':projectId', $teamProjectId, PDO::PARAM_INT);
                        $stmtProject->execute();
                        $project = $stmtProject->fetch(PDO::FETCH_ASSOC);

                        $querySM = "SELECT * FROM users WHERE id = :smId";
                        $stmtSM = $conn->prepare($querySM);
                        $stmtSM->bindParam(':smId', $team['scrumMaster'], PDO::PARAM_INT);
                        $stmtSM->execute();
                        $sm = $stmtSM->fetch(PDO::FETCH_ASSOC);

                        echo "
                        <tr>
                            <td>{$team['name']}</td>
                            <td>{$team['description']}</td>
                            <td>{$team['created_at']}</td>";

                        if ($teamProjectId === NULL) {
                            echo "<td>-</td>";
                        } else {
                            echo "<td>{$project['name']}</td>";
                        }

                        if ($team['scrumMaster'] === NULL) {
                            echo "<td>-</td>
                            </tr>";
                        } else {
                            echo "<td>{$sm['fname']} {$sm['lname']}</td>
                            </tr>";
                        }
                    }

                    echo "</table>
                    <h4 class=sub-title id=myProjects>Projects</h4>
                    <table class='teamTable'>
                    <tr>
                        <th>Project Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Product Owner</th>
                    </tr>";
                    $query = "SELECT projects.*
                        FROM users
                        JOIN team_user ON users.id = team_user.user_id
                        JOIN teams ON team_user.team_id = teams.id
                        JOIN projects ON teams.projectId = projects.id
                        WHERE users.id = :userId";

                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmt->execute();

                    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($projects as $project) {
                        echo "
                        <tr>
                            <td>{$project['name']}</td>
                            <td>{$project['date_start']}</td>
                            <td>{$project['date_end']}</td>
                            <td>{$project['description']}</td>
                            <td>";
                        echo ($project['status'] === 0) ? '<p  class=active>● Active</p>' : '<p class=done>✔ Done</p></td>';
                        $queryPO = "SELECT * FROM users WHERE id = :poId";
                        $stmtPO = $conn->prepare($queryPO);
                        $stmtPO->bindParam(':poId', $project['productOwner'], PDO::PARAM_INT);
                        $stmtPO->execute();
                        $po = $stmtPO->fetch(PDO::FETCH_ASSOC);

                        if ($project['productOwner'] === NULL) {
                            echo "<td>-</td>
                            </tr>";
                        } else {
                            echo "
                                <td>{$po['fname']} {$po['lname']}</td>
                            </tr>";
                        }
                    }
                    echo "</div>";
                }
            }
            ?>
        </div>
        <div id="popup" class="popup">
            <div class="popup-content">
                <div class="popup-header">
                    <h2>Modify User Role</h2>
                    <span class="close" onclick="closePopup()">&times;</span>
                </div>
                <div class="popup-body">
                    <form action="" method="post">
                        <label for="userID">User ID:</label>
                        <input type="text" id="userID" name="userID" id="userId" required readonly>
                        <label for="newRole">New Role:</label>
                        <select name="newRole" id="newRole" required>
                            <option value="0">Member</option>
                            <option value="1">Product Owner</option>
                            <option value="2">Scrum Master</option>
                        </select>
                        <div class="popup-footer">
                            <button type="submit" class="btn btn-primary">Modify Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="SMpopup" class="popup">
            <div class="popup-content">
                <div class="popup-header">
                    <h2>Set Scrum Master</h2>
                    <span class="close" onclick="closeSMPopup()">&times;</span>
                </div>
                <div class="popup-body">
                    <form action="dashboard.php" method="post">
                        <label for="teamId" style="color: #008fd4; font-size: 16px; font-weight: 600;">Team ID:</label><br>
                        <input type="number" id="teamId" name="teamId" required readonly style="width: 100%; padding: 10px 7px; font-size: 16px; border-radius: 5px; outline: none; border: #1e1e1e4c 1px solid; margin-bottom: 15px;"> <br>
                        <label for="newSM" style="color: #008fd4; font-size: 16px; font-weight: 600;">New Scrum Master:</label><br>
                        <select name="newSM" id="newSM" required style="width: 100%; padding: 10px 7px; font-size: 16px; border-radius: 5px; outline: none; border: #1e1e1e4c 1px solid; margin-bottom: 15px;">
                            <option value="" hidden>Select Scrum Master</option>
                            <?php
                            $query = "SELECT * FROM users WHERE role = 2";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $sms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($sms as $sm) {
                                echo "<option value={$sm['id']}>{$sm['fname']} {$sm['lname']}</option>";
                            }
                            ?>
                        </select>
                        <div class="popup-footer">
                            <button type="submit" class="btn btn-primary" name="setSM">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="teamPopup" class="popup">
            <div class="popup-content">
                <div class="popup-header">
                    <h2>Add Team</h2>
                    <span class="close" onclick="closeTeamPopup()">&times;</span>
                </div>
                <div class="popup-body">
                    <form action="dashboard.php" method="post">
                        <label for="name" style="color: #008fd4; font-size: 16px; font-weight: 600;">Team Name:</label><br>
                        <input type="text" id="name" name="name" required placeholder="Enter Team Name" style="width: 100%; padding: 10px 7px; font-size: 16px; border-radius: 5px; outline: none; border: #1e1e1e4c 1px solid; margin-bottom: 15px;"> <br>
                        <label for="description" style="color: #008fd4; font-size: 16px; font-weight: 600;">Team Description:</label><br>
                        <textarea id="description" name="description" required placeholder="Tell us about your team <3" style="width: 100%; padding: 10px 7px; font-size: 16px; border-radius: 5px; outline: none; border: #1e1e1e4c 1px solid; margin-bottom: 15px;"></textarea> <br>
                        <label for="newP" style="color: #008fd4; font-size: 16px; font-weight: 600;">Project:</label><br>
                        <select name="newP" id="newP" required style="width: 100%; padding: 10px 7px; font-size: 16px; border-radius: 5px; outline: none; border: #1e1e1e4c 1px solid; margin-bottom: 15px;">
                            <option value="" hidden>Select Project</option>
                            <?php
                            $query = "SELECT * FROM projects WHERE NOT EXISTS ( SELECT * FROM teams WHERE teams.projectId = projects.id)";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($projects as $proj) {
                                echo "<option value={$proj['id']}>{$proj['name']}</option>";
                            }
                            ?>
                        </select>
                        <label for="scrumMaster" style="color: #008fd4; font-size: 16px; font-weight: 600;">Scrum Master:</label><br>
                        <input type="number" id="scrumMaster" name="scrumMaster" required style="width: 100%; padding: 10px 7px; font-size: 16px; border-radius: 5px; outline: none; border: #1e1e1e4c 1px solid; margin-bottom: 15px;" readonly> <br>

                        <div class="popup-footer">
                            <button type="submit" class="btn btn-primary" name="setTeam">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>


</body>

</html>