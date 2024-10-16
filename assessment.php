<?php
session_start(); 

if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); 
    exit();
}

$host = "127.0.0.1";
$user = "user";
$passwd = "password";
$database = "fr";

$conn = new mysqli($host, $user, $passwd, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$error = '';

$user_email = $_SESSION['email'];
$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Page Form</title>
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
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 75%; 
            max-width: 1200px; 
            margin: 30px auto; 
            display: flex; 
            flex-direction: column; 
        }

        .progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .progress-bar {
            height: 5px;
            background-color: #4CAF50;
            transition: width 0.3s;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .form-control label {
            display: block;
            margin-bottom: 5px;
        }

        .form-control input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #45a049; 
        }

        .slider {
            -webkit-appearance: none;
            width: 90%;
            height: 10px;
            background: #ccc;
            outline: none;
            border-radius: 5px;
            margin-right: 20px;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            background: #4CAF50;
            cursor: pointer;
            border-radius: 50%;
        }

        .slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: #4CAF50;
            cursor: pointer;
            border-radius: 50%;
        }

        .slider-value {
            display: inline-block;
            font-size: 18px;
            padding: 5px 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 15px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
            margin-bottom: 15px;
        }

        .btn-link {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .btn-link:hover {
            color: #45a049;
        }

        .card-header {
            border-radius: 5px;
        }

        #summaryContent {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .summary p {
            font-size: 16px;
            margin: 5px 0;
        }

        .summary h4 {
            margin-bottom: 10px;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .summary-box h3 {
            margin-top: 0;
            color: #4CAF50; 
        }
        .summary-box p {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .summary-box span {
            text-align: right;
            color: #333;
        }
        .name-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-control {
            margin-bottom: 15px;
        }
        .form-control2 {
            margin-bottom: 15px;
            width: 30%;
        }

        .form-control label {
            display: block;
            margin-bottom: 5px;
        }

        .form-control input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid lightgray;
            border-radius: 4px;
        }
        .short-field .form-control input {
            width: 40%; 
        }
        .name-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-item label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .summary-item span {
            text-align: left; 
        }

        .summary-box {
            padding: 20px;
            border: 1px solid lightgray;
            border-radius: 4px;
            background-color: #f0f2f5;
        }

        #loadingModal {
            display: none; 
            justify-content: space-between;
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0, 0, 0, 0.7); 
            z-index: 1000; 
            
        }

        #loadingModal > div {
            position: absolute; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); 
            height: 350px; 
            width: 400px; 
            text-align: center; 
            display: flex;
            flex-direction: column;
            align-items: center; 
            justify-content: center; 
        }
        

        #loadingModal h2 {
            color: #4CAF50; 
        }

        #loadingModal #result {
            visibility = hidden;
            margin-top: 20px; 
            font-size: 18px; 
            font-weight: bold; 
            color: #333; 
            text-align: center; 
        }

        #loadingModal #backButton {
            display: none;
            text-align: center;
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            margin-top: 20px; 
        }

        #loadingModal #backButton:hover {
            background-color: #45a049; 
        }

        #loader {
            width: 100px; 
            height: 100px; 
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
<div class="container">
    <h2>Evaluación</h2>
    
    <div class="progress">
        <div class="progress-bar" style="width: 10%;"></div>
    </div>

    <!-- Form Section 1 -->
    <div class="form-section active" id="section1">
        <h3>Información Personal</h3>
        
        <!-- Name Fields in a Square Layout -->
        <div class="name-grid">
            <div class="form-control">
                <label for="name">Nombre</label>
                <input type="text" id="name" required>
            </div>
            <div class="form-control">
                <label for="middle-name">Segundo Nombre</label>
                <input type="text" id="middle-name">
            </div>
            <div class="form-control">
                <label for="last-name1">Apellido Materno</label>
                <input type="text" id="last-name1" required>
            </div>
            <div class="form-control">
                <label for="last-name2">Apellido Paterno</label>
                <input type="text" id="last-name2" required>
            </div>
        </div>

        <!-- Other Fields -->
        <div class="short-field">
            <div class="form-control">
                <label for="age">Edad</label>
                <input type="number" id="age" required>
            </div>
            <div class="form-control">
                <label for="rut">RUT</label>
                <input type="text" id="rut" required>
            </div>
            <div class="form-control">
                <label for="address">Dirección</label>
                <input type="text" id="address" required>
            </div>
        </div>

        <button class="button" onclick="nextSection('section1', 'section2', true)">Siguiente</button>
    </div>


    <!-- Form Section 2 -->
    <div class="form-section" id="section2">
        <h3>Factores Personales</h3>
        
        <div class="accordion" id="accordionExample">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="1">
                    <h4 class="mb-0">
                        <label for="name">Historia Familiar de Problemas de Salud Mental</label>
                    </h4>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="1" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider1" oninput="updateSlider(this, 'sliderValue1')">
                        <div id="sliderValue1" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="2">
                    <h4 class="mb-0">
                        <label for="name">Antecedentes de Abuso de Sustancias</label>
                    </h4>
                </div>
                <div id="collapseTwo" class="collapse show" aria-labelledby="2" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider2" oninput="updateSlider(this, 'sliderValue2')">
                        <div id="sliderValue2" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="3">
                    <h4 class="mb-0">
                        <label for="name">Problemas de Comportamiento y de Regulación Emocional</label>
                    </h4>
                </div>
                <div id="collapseThree" class="collapse show" aria-labelledby="3" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider3" oninput="updateSlider(this, 'sliderValue3')">
                        <div id="sliderValue3" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="4">
                    <h4 class="mb-0">
                        <label for="name">Baja Autoestima</label>
                    </h4>
                </div>
                <div id="collapseFour" class="collapse show" aria-labelledby="4" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider4" oninput="updateSlider(this, 'sliderValue4')">
                        <div id="sliderValue4" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="5">
                    <h4 class="mb-0">
                        <label for="name">Enfermedades Crónicas o Discapacidades Físicas</label>
                    </h4>
                </div>
                <div id="collapseFive" class="collapse show" aria-labelledby="5" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider5" oninput="updateSlider(this, 'sliderValue5')">
                        <div id="sliderValue5" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="card">
                <div class="card-header" id="6">
                    <h4 class="mb-0">
                        <label for="name">Estrés Prolongado o Traumático</label>
                    </h4>
                </div>
                <div id="collapseSix" class="collapse show" aria-labelledby="6" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider6" oninput="updateSlider(this, 'sliderValue6')">
                        <div id="sliderValue6" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="button" onclick="nextSection('section2', 'section3', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section2', 'section1', false)">Anterior</button>
    </div>

    <!-- Form Section 3 -->
    <div class="form-section" id="section3">
        <h3>Factores Familiares</h3>
        
        <div class="accordion" id="accordionExample">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="7">
                    <h4 class="mb-0">
                        <label for="name">Conflictos Familiares y Violencia Doméstica</label>
                    </h4>
                </div>
                <div id="collapseSeven" class="collapse show" aria-labelledby="7" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider7" oninput="updateSlider(this, 'sliderValue7')">
                        <div id="sliderValue7" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="8">
                    <h4 class="mb-0">
                        <label for="name">Falta de Apoyo Emocional y Supervisión</label>
                    </h4>
                </div>
                <div id="collapseEight" class="collapse show" aria-labelledby="8" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider8" oninput="updateSlider(this, 'sliderValue8')">
                        <div id="sliderValue8" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="9">
                    <h4 class="mb-0">
                        <label for="name">Abuso Físico, Emocional o Sexual</label>
                    </h4>
                </div>
                <div id="collapseNine" class="collapse show" aria-labelledby="9" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider9" oninput="updateSlider(this, 'sliderValue9')">
                        <div id="sliderValue9" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="10">
                    <h4 class="mb-0">
                        <label for="name">Pérdida de uno o ambos Padres</label>
                    </h4>
                </div>
                <div id="collapseTen" class="collapse show" aria-labelledby="10" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider10" oninput="updateSlider(this, 'sliderValue10')">
                        <div id="sliderValue10" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="11">
                    <h4 class="mb-0">
                        <label for="name">Padres con Problemas de Salud Mental o Abuso de Sustancias</label>
                    </h4>
                </div>
                <div id="collapseEleven" class="collapse show" aria-labelledby="11" data-parent="#accordionExample">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider11" oninput="updateSlider(this, 'sliderValue11')">
                        <div id="sliderValue11" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="button" onclick="nextSection('section3', 'section4', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section3', 'section2', false)">Anterior</button>

    </div>

    <!-- Factores Riesgo Sociales -->
    <div class="form-section" id="section4">
        <h3>Factores Riesgo Sociales</h3>

        <div class="accordion" id="accordionExampleSociales">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="12">
                    <h4 class="mb-0">
                        <label for="name">Exclusión Social y Falta de Redes de Apoyo</label>
                    </h4>
                </div>
                <div id="collapseTwelve" class="collapse show" aria-labelledby="12" data-parent="#accordionExampleSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider12" oninput="updateSlider(this, 'sliderValue12')">
                        <div id="sliderValue12" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="13">
                    <h4 class="mb-0">
                        <label for="name">Pobreza y Dificultades Económicas</label>
                    </h4>
                </div>
                <div id="collapseThirteen" class="collapse show" aria-labelledby="13" data-parent="#accordionExampleSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider13" oninput="updateSlider(this, 'sliderValue13')">
                        <div id="sliderValue13" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="14">
                    <h4 class="mb-0">
                        <label for="name">Experiencias de Discriminación y Estigmatización</label>
                    </h4>
                </div>
                <div id="collapseFourteen" class="collapse show" aria-labelledby="14" data-parent="#accordionExampleSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider14" oninput="updateSlider(this, 'sliderValue14')">
                        <div id="sliderValue14" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="15">
                    <h4 class="mb-0">
                        <label for="name">Influencias Negativas de Pares y Amigos</label>
                    </h4>
                </div>
                <div id="collapseFifteen" class="collapse show" aria-labelledby="15" data-parent="#accordionExampleSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider15" oninput="updateSlider(this, 'sliderValue15')">
                        <div id="sliderValue15" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="16">
                    <h4 class="mb-0">
                        <label for="name">Ambientes Escolares Poco Seguros o Violentos</label>
                    </h4>
                </div>
                <div id="collapseSixteen" class="collapse show" aria-labelledby="16" data-parent="#accordionExampleSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider16" oninput="updateSlider(this, 'sliderValue16')">
                        <div id="sliderValue16" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="button" onclick="nextSection('section4','section5', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section4', 'section3', false)">Anterior</button>
    </div>

    <!-- Factores Riesgo Ambientales -->
    <div class="form-section" id="section5">
        <h3>Factores Riesgo Ambientales</h3>

        <div class="accordion" id="accordionExampleAmbientales">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="17">
                    <h4 class="mb-0">
                        <label for="name">Vivienda Inadecuada o Condiciones de Vida Peligrosas</label>
                    </h4>
                </div>
                <div id="collapseSeventeen" class="collapse show" aria-labelledby="17" data-parent="#accordionExampleAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider17" oninput="updateSlider(this, 'sliderValue17')">
                        <div id="sliderValue17" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="18">
                    <h4 class="mb-0">
                        <label for="name">Acceso Limitado a Servicios de Salud y Educación</label>
                    </h4>
                </div>
                <div id="collapseEighteen" class="collapse show" aria-labelledby="18" data-parent="#accordionExampleAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider18" oninput="updateSlider(this, 'sliderValue18')">
                        <div id="sliderValue18" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="19">
                    <h4 class="mb-0">
                        <label for="name">Desastres Naturales y Crisis Humanitarias</label>
                    </h4>
                </div>
                <div id="collapseNineteen" class="collapse show" aria-labelledby="19" data-parent="#accordionExampleAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider19" oninput="updateSlider(this, 'sliderValue19')">
                        <div id="sliderValue19" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="20">
                    <h4 class="mb-0">
                        <label for="name">Exposición a Violencia Comunitaria</label>
                    </h4>
                </div>
                <div id="collapseTwenty" class="collapse show" aria-labelledby="20" data-parent="#accordionExampleAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider20" oninput="updateSlider(this, 'sliderValue20')">
                        <div id="sliderValue20" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="button" onclick="nextSection('section5','section6', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section5', 'section4', false)">Anterior</button>
    </div>

    <!-- Factores Protección Personales -->
    <div class="form-section" id="section6">
        <h3>Factores Protección Personales</h3>

        <div class="accordion" id="accordionExampleProteccionPersonales">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="21">
                    <h4 class="mb-0">
                        <label for="name">Buena Salud Física y Mental</label>
                    </h4>
                </div>
                <div id="collapseTwentyOne" class="collapse show" aria-labelledby="21" data-parent="#accordionExampleProteccionPersonales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider21" oninput="updateSlider(this, 'sliderValue21')">
                        <div id="sliderValue21" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="22">
                    <h4 class="mb-0">
                        <label for="name">Habilidades de Afrontamiento y Manejo del Estrés</label>
                    </h4>
                </div>
                <div id="collapseTwentyTwo" class="collapse show" aria-labelledby="22" data-parent="#accordionExampleProteccionPersonales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider22" oninput="updateSlider(this, 'sliderValue22')">
                        <div id="sliderValue22" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="23">
                    <h4 class="mb-0">
                        <label for="name">Alta Autoestima y Autoconfianza</label>
                    </h4>
                </div>
                <div id="collapseTwentyThree" class="collapse show" aria-labelledby="23" data-parent="#accordionExampleProteccionPersonales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider23" oninput="updateSlider(this, 'sliderValue23')">
                        <div id="sliderValue23" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="24">
                    <h4 class="mb-0">
                        <label for="name">Buen Rendimiento Académico y Habilidades Cognitivas</label>
                    </h4>
                </div>
                <div id="collapseTwentyFour" class="collapse show" aria-labelledby="24" data-parent="#accordionExampleProteccionPersonales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider24" oninput="updateSlider(this, 'sliderValue24')">
                        <div id="sliderValue24" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="25">
                    <h4 class="mb-0">
                        <label for="name">Participación en Actividades Recreativas y Deportivas</label>
                    </h4>
                </div>
                <div id="collapseTwentyFive" class="collapse show" aria-labelledby="25" data-parent="#accordionExampleProteccionPersonales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider25" oninput="updateSlider(this, 'sliderValue25')">
                        <div id="sliderValue25" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="button" onclick="nextSection('section6','section7', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section6', 'section5', false)">Anterior</button>
    </div>

    <!-- Factores Protección Familiares -->
    <div class="form-section" id="section7">
        <h3>Factores Protección Familiares</h3>

        <div class="accordion" id="accordionExampleProteccionFamiliares">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="26">
                    <h4 class="mb-0">
                        <label for="name">Relaciones Familiares Cálidas y de Apoyo</label>
                    </h4>
                </div>
                <div id="collapseTwentySix" class="collapse show" aria-labelledby="26" data-parent="#accordionExampleProteccionFamiliares">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider26" oninput="updateSlider(this, 'sliderValue26')">
                        <div id="sliderValue26" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="27">
                    <h4 class="mb-0">
                        <label for="name">Supervisión y Guía Adecuada por Parte de los Padres</label>
                    </h4>
                </div>
                <div id="collapseTwentySeven" class="collapse show" aria-labelledby="27" data-parent="#accordionExampleProteccionFamiliares">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider27" oninput="updateSlider(this, 'sliderValue27')">
                        <div id="sliderValue27" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="28">
                    <h4 class="mb-0">
                        <label for="name">Comunicación Abierta y Efectiva en la Familia</label>
                    </h4>
                </div>
                <div id="collapseTwentyEight" class="collapse show" aria-labelledby="28" data-parent="#accordionExampleProteccionFamiliares">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider28" oninput="updateSlider(this, 'sliderValue28')">
                        <div id="sliderValue28" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="29">
                    <h4 class="mb-0">
                        <label for="name">Presencia de al Menos un Adulto Significativo y de Confianza</label>
                    </h4>
                </div>
                <div id="collapseTwentyNine" class="collapse show" aria-labelledby="29" data-parent="#accordionExampleProteccionFamiliares">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider29" oninput="updateSlider(this, 'sliderValue29')">
                        <div id="sliderValue29" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="30">
                    <h4 class="mb-0">
                        <label for="name">Prácticas de Crianza Positivas y Consistentes</label>
                    </h4>
                </div>
                <div id="collapseThirty" class="collapse show" aria-labelledby="30" data-parent="#accordionExampleProteccionFamiliares">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider30" oninput="updateSlider(this, 'sliderValue30')">
                        <div id="sliderValue30" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="button" onclick="nextSection('section7','section8', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section7', 'section6', false)">Anterior</button>
    </div>

    <!-- Factores Protección Sociales -->
    <div class="form-section" id="section8">
        <h3>Factores Protección Sociales</h3>

        <div class="accordion" id="accordionExampleProteccionSociales">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="31">
                    <h4 class="mb-0">
                        <label for="name">Redes de Apoyo Social Sólidas (Amigos, Comunidad)</label>
                    </h4>
                </div>
                <div id="collapseThirtyOne" class="collapse show" aria-labelledby="31" data-parent="#accordionExampleProteccionSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider31" oninput="updateSlider(this, 'sliderValue31')">
                        <div id="sliderValue31" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="32">
                    <h4 class="mb-0">
                        <label for="name">Participación en Grupos y Actividades Comunitarias</label>
                    </h4>
                </div>
                <div id="collapseThirtyTwo" class="collapse show" aria-labelledby="32" data-parent="#accordionExampleProteccionSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider32" oninput="updateSlider(this, 'sliderValue32')">
                        <div id="sliderValue32" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="33">
                    <h4 class="mb-0">
                        <label for="name">Ambiente Escolar Seguro y de Apoyo</label>
                    </h4>
                </div>
                <div id="collapseThirtyThree" class="collapse show" aria-labelledby="33" data-parent="#accordionExampleProteccionSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider33" oninput="updateSlider(this, 'sliderValue33')">
                        <div id="sliderValue33" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="34">
                    <h4 class="mb-0">
                        <label for="name">Acceso a Servicios de Salud Mental y Otros Recursos</label>
                    </h4>
                </div>
                <div id="collapseThirtyFour" class="collapse show" aria-labelledby="34" data-parent="#accordionExampleProteccionSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider34" oninput="updateSlider(this, 'sliderValue34')">
                        <div id="sliderValue34" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="35">
                    <h4 class="mb-0">
                        <label for="name">Experiencias de Éxito y Reconocimiento Social</label>
                    </h4>
                </div>
                <div id="collapseThirtyFive" class="collapse show" aria-labelledby="35" data-parent="#accordionExampleProteccionSociales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider35" oninput="updateSlider(this, 'sliderValue35')">
                        <div id="sliderValue35" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="button" onclick="nextSection('section8','section9', true)">Siguiente</button>
        <button class="button" onclick="nextSection('section8', 'section7', false)">Anterior</button>
    </div>

    <!-- Factores Protección Ambientales -->
    <div class="form-section" id="section9">
        <h3>Factores Protección Ambientales</h3>

        <div class="accordion" id="accordionExampleProteccionAmbientales">
            <!-- Card 1 -->
            <div class="card">
                <div class="card-header" id="36">
                    <h4 class="mb-0">
                        <label for="name">Entorno Físico Seguro y Saludable</label>
                    </h4>
                </div>
                <div id="collapseThirtySix" class="collapse show" aria-labelledby="36" data-parent="#accordionExampleProteccionAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider36" oninput="updateSlider(this, 'sliderValue36')">
                        <div id="sliderValue36" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-header" id="37">
                    <h4 class="mb-0">
                        <label for="name">Políticas y Programas Comunitarios de Apoyo</label>
                    </h4>
                </div>
                <div id="collapseThirtySeven" class="collapse show" aria-labelledby="37" data-parent="#accordionExampleProteccionAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider37" oninput="updateSlider(this, 'sliderValue37')">
                        <div id="sliderValue37" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-header" id="38">
                    <h4 class="mb-0">
                        <label for="name">Acceso a Educación de Calidad y Oportunidades de Empleo</label>
                    </h4>
                </div>
                <div id="collapseThirtyEight" class="collapse show" aria-labelledby="38" data-parent="#accordionExampleProteccionAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider38" oninput="updateSlider(this, 'sliderValue38')">
                        <div id="sliderValue38" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <div class="card-header" id="39">
                    <h4 class="mb-0">
                        <label for="name">Servicios de Salud Accesibles y de Buena Calidad</label>
                    </h4>
                </div>
                <div id="collapseThirtyNine" class="collapse show" aria-labelledby="39" data-parent="#accordionExampleProteccionAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider39" oninput="updateSlider(this, 'sliderValue39')">
                        <div id="sliderValue39" class="slider-value">1</div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <div class="card-header" id="40">
                    <h4 class="mb-0">
                        <label for="name">Programas de Prevención y Promoción de la Salud</label>
                    </h4>
                </div>
                <div id="collapseForty" class="collapse show" aria-labelledby="40" data-parent="#accordionExampleProteccionAmbientales">
                    <div class="card-body">
                        <input type="range" min="1" max="4" step="1" value="1" class="slider" id="slider40" oninput="updateSlider(this, 'sliderValue40')">
                        <div id="sliderValue40" class="slider-value">1</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="button" onclick="showSummary()">Revisar</button>
        <button class="button" onclick="nextSection('section9', 'section8', false)">Anterior</button>
    </div>

    <!-- Summary Section -->
    <div class="form-section" id="summarySection">
        <h3>Resumen de las Respuestas</h3>
        <div id="summaryContent">
        <div class="summary-box">
            <h3>Información Personal</h3>
            
            <!-- Names in a Grid Layout -->
            <div class="name-grid">
                <div class="summary-item">
                    <label for="summaryName">Nombre:</label>
                    <span id="summaryName"></span>
                </div>
                <div class="summary-item">
                    <label for="summaryMiddleName">Segundo Nombre:</label>
                    <span id="summaryMiddleName"></span>
                </div>
                <div class="summary-item">
                    <label for="summaryLastName1">Apellido Paterno:</label>
                    <span id="summaryLastName1"></span>
                </div>
                <div class="summary-item">
                    <label for="summaryLastName2">Apellido Materno:</label>
                    <span id="summaryLastName2"></span>
                </div>
            </div>

            <!-- Other Fields in Normal Layout -->
            <p>Age: <span id="summaryAge"></span></p>
            <p>RUT: <span id="summaryRUT"></span></p>
            <p>Address: <span id="summaryAddress"></span></p>
        </div>

            <!-- Slider Summary Section -->
            <div class="summary-box">
            <h3>Factores Personales</h3>
            <p> Historia Familiar de Problemas de Salud Mental:  <span id="summaryRiesgo1"></span></p>
            <p> Antecedentes de Abuso de Sustancias:  <span id="summaryRiesgo2"></span></p>
            <p> Problemas de Comportamiento y de Regulación Emocional:  <span id="summaryRiesgo3"></span></p>
            <p> Baja Autoestima:  <span id="summaryRiesgo4"></span></p>
            <p> Enfermedades Crónicas o Discapacidades Físicas:  <span id="summaryRiesgo5"></span></p>
            <p> Estrés Prolongado o Traumático:  <span id="summaryRiesgo6"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Riesgo Familiares</h3>
            <p> Conflictos Familiares y Violencia Doméstica:  <span id="summaryRiesgo7"></span></p>
            <p> Falta de Apoyo Emocional y Supervisión:  <span id="summaryRiesgo8"></span></p>
            <p> Abuso Físico, Emocional o Sexual:  <span id="summaryRiesgo9"></span></p>
            <p> Pérdida de uno o ambos Padres:  <span id="summaryRiesgo10"></span></p>
            <p> Padres con Problemas de Salud Mental o Abuso de Sustancias:  <span id="summaryRiesgo11"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Riesgo Sociales</h3>
            <p> Exclusión Social y Falta de Redes de Apoyo:  <span id="summaryRiesgo12"></span></p>
            <p> Pobreza y Dificultades Económicas:  <span id="summaryRiesgo13"></span></p>
            <p> Experiencias de Discriminación y Estigmatización:  <span id="summaryRiesgo14"></span></p>
            <p> Influencias Negativas de Pares y Amigos:  <span id="summaryRiesgo15"></span></p>
            <p> Ambientes Escolares Poco Seguros o Violentos:  <span id="summaryRiesgo16"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Riesgo Ambientales</h3>
            <p> Vivienda Inadecuada o Condiciones de Vida Peligrosas:  <span id="summaryRiesgo17"></span></p>
            <p> Acceso Limitado a Servicios de Salud y Educación:  <span id="summaryRiesgo18"></span></p>
            <p> Desastres Naturales y Crisis Humanitarias:  <span id="summaryRiesgo19"></span></p>
            <p> Exposición a Violencia Comunitaria:  <span id="summaryRiesgo20"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Protección Personales</h3>
            <p> Buena Salud Física y Mental:  <span id="summaryRiesgo21"></span></p>
            <p> Habilidades de Afrontamiento y Manejo del Estrés:  <span id="summaryRiesgo22"></span></p>
            <p> Alta Autoestima y Autoconfianza:  <span id="summaryRiesgo23"></span></p>
            <p> Buen Rendimiento Académico y Habilidades Cognitivas:  <span id="summaryRiesgo24"></span></p>
            <p> Participación en Actividades Recreativas y Deportivas:  <span id="summaryRiesgo25"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Protección Familiares</h3>
            <p> Relaciones Familiares Cálidas y de Apoyo:  <span id="summaryRiesgo26"></span></p>
            <p> Supervisión y Guía Adecuada por Parte de los Padres:  <span id="summaryRiesgo27"></span></p>
            <p> Comunicación Abierta y Efectiva en la Familia:  <span id="summaryRiesgo28"></span></p>
            <p> Presencia de al Menos un Adulto Significativo y de Confianza:  <span id="summaryRiesgo29"></span></p>
            <p> Prácticas de Crianza Positivas y Consistentes:  <span id="summaryRiesgo30"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Protección Sociales</h3>
            <p> Redes de Apoyo Social Sólidas (Amigos, Comunidad):  <span id="summaryRiesgo31"></span></p>
            <p> Participación en Grupos y Actividades Comunitarias:  <span id="summaryRiesgo32"></span></p>
            <p> Ambiente Escolar Seguro y de Apoyo:  <span id="summaryRiesgo33"></span></p>
            <p> Acceso a Servicios de Salud Mental y Otros Recursos:  <span id="summaryRiesgo34"></span></p>
            <p> Experiencias de Éxito y Reconocimiento Social:  <span id="summaryRiesgo35"></span></p>
            </div>
            <div class="summary-box">
            <h3>Factores de Protección Ambientales</h3>
            <p> Entorno Físico Seguro y Saludable:  <span id="summaryRiesgo36"></span></p>
            <p> Políticas y Programas Comunitarios de Apoyo:  <span id="summaryRiesgo37"></span></p>
            <p> Acceso a Educación de Calidad y Oportunidades de Empleo:  <span id="summaryRiesgo38"></span></p>
            <p> Servicios de Salud Accesibles y de Buena Calidad:  <span id="summaryRiesgo39"></span></p>
            <p> Programas de Prevención y Promoción de la Salud:  <span id="summaryRiesgo40"></span></p>
            </div>
        </div>
        <button class="button" onclick="nextSection('summarySection', 'section9', false)">Anterior</button>
        <button class="button" onclick="submitForm(<?php echo $userid; ?>)">Completar</button>
    </div>


</div>
<!-- Loading Modal -->
<div id="loadingModal" >
    <div>
        <h2 id="title">Procesando...</h2>
        <img id="loader" src="utils/loading.gif" alt="Loading...">
        <p id="text" >Por favor espere mientras se calcula el riesgo.</p>
        <div id="result"></div>
        <button id="backButton" onclick="window.location.href='homepage.php';">Home</button>
    </div>
</div>
<script>

    function updateSlider(slider, valueId) {
        const sliderValue = document.getElementById(valueId);
        const value = slider.value;

        // Update the displayed value
        sliderValue.textContent = value;

    }

    function nextSection(first, next, forward) {
        const section1 = document.getElementById(first);
        const section2 = document.getElementById(next);
        
        section1.classList.remove('active');
        section2.classList.add('active');

        // Update progress bar
        let progressBar = document.querySelector('.progress-bar');
        let currentWidth = parseInt(progressBar.style.width) || 0; 
        
        let newWidth; // Declare newWidth here

        if (forward) { // Add parentheses
            newWidth = currentWidth + 10;
        } else {
            newWidth = currentWidth - 10;
        }
        console.log(currentWidth);
        console.log(newWidth);
        progressBar.style.width = Math.min(newWidth, 100) + '%'; // This will also prevent going below 0%
    }

    function showSummary() {
        document.querySelector('.progress-bar').style.width = '100%';
        const section1 = document.getElementById('section9');
        const section2 = document.getElementById('summarySection');
        
        section1.classList.remove('active');
        section2.classList.add('active');

        const name = document.getElementById('name').value;
        const middleName = document.getElementById('middle-name').value;
        const lastName1 = document.getElementById('last-name1').value;
        const lastName2 = document.getElementById('last-name2').value;
        const age = document.getElementById('age').value;
        const rut = document.getElementById('rut').value;
        const address = document.getElementById('address').value;

        document.getElementById('summaryName').innerText = name;
        document.getElementById('summaryMiddleName').innerText = middleName || 'N/A';
        document.getElementById('summaryLastName1').innerText = lastName1;
        document.getElementById('summaryLastName2').innerText = lastName2;
        document.getElementById('summaryAge').innerText = age;
        document.getElementById('summaryRUT').innerText = rut;
        document.getElementById('summaryAddress').innerText = address;

        const riesgo = [];
        for (let i = 1; i <= 40; i++) {
            riesgo.push(document.getElementById(`slider${i}`).value);
        }
        for (let i = 0; i < riesgo.length; i++) {
            document.getElementById(`summaryRiesgo${i + 1}`).innerText = riesgo[i];
        } 
    }


    function submitForm(userid) {
        const name = document.getElementById('name').value;
        const middleName = document.getElementById('middle-name').value;
        const lastName1 = document.getElementById('last-name1').value;
        const lastName2 = document.getElementById('last-name2').value;
        const age = document.getElementById('age').value;
        const rut = document.getElementById('rut').value;
        const address = document.getElementById('address').value;
        const riesgo = [];

        for (let i = 1; i <= 40; i++) {
            riesgo.push(document.getElementById(`slider${i}`).value);
        }
        
        const loadingModal = document.getElementById('loadingModal');
        loadingModal.style.display = 'block';

        fetch('predict.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ riesgo: riesgo })
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            const title = document.getElementById('title');
            const text = document.getElementById('text');

            const predictionValue = data.prediction[0];
            title.innerHTML = "Riesgo Calculado ";

            let risk;
            if (predictionValue === 0) {
                risk = "bajo"; 
            } else if (predictionValue === 1) {
                risk = "medio"; 
            } else if (predictionValue === 2) {
                risk = "alto"; 
            } else {
                risk = "desconocido"; 
            }
            text.innerHTML = "Se estima que " + name + " tiene un riesgo " + risk; // Use risk here
            
            const backButton = document.getElementById('backButton');
            backButton.style.display = 'block';
            const result = document.getElementById('result');
            result.style.visibility = 'visible';

            const loader = document.getElementById('loader');
            loader.style.visibility = 'hidden';



            fetch('submit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    middleName: middleName,
                    lastName1: lastName1,
                    lastName2: lastName2,
                    age: age,
                    rut: rut,
                    address: address,
                    riesgo: riesgo,
                    predictionValue: predictionValue,
                    userid: userid,
                })
            })
            .then(response => response.text())  
            .then(text => {
                console.log("Raw response:", text);  
                try {
                    const jsonResponse = JSON.parse(text);  
                    if (jsonResponse.status === 'success') {
                        console.log("Data saved successfully, personId: " + jsonResponse.personId);
                    } else {
                        console.error("Error saving data: " + jsonResponse.message);
                    }
                } catch (error) {
                    console.error("Failed to parse JSON:", error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            

        })
        .catch((error) => {
            console.error('Error:', error);
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = "An error occurred. Please try again.";
        });
    }



</script>

</body>
</html>

</html>
