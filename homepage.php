<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); 
    exit();
}

$user_email = $_SESSION['email'];

$host = "127.0.0.1";
$user = "user";
$passwd = "password";
$database = "fr";

$conn = new mysqli($host, $user, $passwd, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$user_id = $_SESSION['userid'];
$query = "
    SELECT p.* 
    FROM people p 
    JOIN assessments a ON p.personid = a.personid 
    WHERE a.userid = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$people = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f0f2f5;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #A9BD93;
            color: white;
            padding: 15px 20px;
            margin: auto;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar input[type="text"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .bigcontainer {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 75%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .header {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            margin-bottom: 20px; 
            position: relative;
        }

        .search-container {
            position: relative; 
            display: flex;
            align-items: center; 
            gap: 10px;
        }

        .search-bar {
            display: none;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            height: 40px; 
            transition: width 0.3s ease; 
            position: absolute; 
            right: 30px; 
        }

        .search-icon {
            cursor: pointer;
            font-size: 20px; 
            margin-left: 10px; 
        }

        .sidebar {
            width: 250px;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .person-card {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
            cursor: pointer;
            display: block; 
        }

        .person-card.hidden {
            display: none; 
        }

        .new-button {
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .new-button:hover {
            background-color: #45a049;
        }

        .detail {
            flex-grow: 1;
            padding: 20px;
            height: 400px;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .detail-info {
            margin-top: 20px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <h1>PROTEGE</h1>
    <div>
        <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="bigcontainer">
    <div class="container">
        <div class="header">
            <h2>Evaluaciones Previas</h2>
            <div class="search-container">
                <input type="text" id="search-bar" class="search-bar" placeholder="Buscar personas..." onkeyup="filterPeople()">
                <i class="fas fa-search search-icon" onclick="toggleSearchBar()"></i>
            </div>
        </div>
        
        <div style="display: flex; gap: 20px;">
            <!-- Sidebar -->
            <div class="sidebar">
                <h2>Personas</h2>
                <div class="sidebar-content" id="people-list">
                    <?php foreach ($people as $person): ?>
                        <div class="person-card" data-name="<?php echo htmlspecialchars($person['name']); ?>" onclick="showDetails(<?php echo $person['personid']; ?>)">
                            <?php echo htmlspecialchars($person['name']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="new-button" onclick="location.href='assessment.php'">Nueva Evaluación</button>
            </div>

            <!-- Detail Area -->
            <div class="detail">
                <h2>Detalles</h2>
                <div id="detail-info" class="detail-info hidden"></div>
            </div>
        </div>
    </div>
</div>


<script>
    const peopleData = <?php echo json_encode($people); ?>;

    function toggleSearchBar() {
        const searchBar = document.getElementById('search-bar');
        if (searchBar.style.display === "none" || searchBar.style.display === "") {
            searchBar.style.display = "block"; 
            searchBar.focus(); 
        } else {
            searchBar.style.display = "none"; 
        }
    }

    function showDetails(personId) {
        const person = peopleData.find(p => p.personid === personId);
        const detailDiv = document.getElementById('detail-info');

        if (person) {
            detailDiv.innerHTML = `
                <h3>${person.name}</h3>
                <p>Edad: ${person.age}</p>
                <p>Dirección: ${person.address}</p>
                <p>RUT: ${person.rut}</p>
            `;
            detailDiv.classList.remove('hidden');
        } else {
            detailDiv.innerHTML = '<p>No details available.</p>';
            detailDiv.classList.remove('hidden');
        }
    }

    function filterPeople() {
        const query = document.getElementById('search-bar').value.toLowerCase();
        const peopleCards = document.querySelectorAll('.person-card');

        peopleCards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            if (name.includes(query)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }
</script>

</body>
</html>
