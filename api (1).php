<?php

header('Content-Type: application/json');

// Configuração de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Função para enviar resposta JSON com código de status HTTP
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// Função para enviar erro
function sendError($message, $statusCode) {
    sendJsonResponse(['error' => $message], $statusCode);
}

// Validação do método HTTP
$method = $_SERVER['REQUEST_METHOD'];
$allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
if (!in_array($method, $allowedMethods)) {
    sendError('Método não permitido', 405);
}

// Arrays de dados
$teams = [
  [
    "team" => "Ferrari",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/ferrari-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/ferrari.png",
    "drivers" => [
      ["nome" => "Charles Leclerc", "bandeira" => "mc"],
      ["nome" => "Lewis Hamilton", "bandeira" => "gb"]
    ]
  ],
  [
    "team" => "Red Bull Racing",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/red-bull-racing-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/red-bull-racing.png",
    "drivers" => [
      ["nome" => "Max Verstappen", "bandeira" => "nl"],
      ["nome" => "Yuki Tsunoda", "bandeira" => "jp"]
    ]
  ],
  [
    "team" => "Mercedes",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/mercedes-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/mercedes.png",
    "drivers" => [
      ["nome" => "George Russell", "bandeira" => "gb"],
      ["nome" => "Kimi Antonelli", "bandeira" => "it"]
    ]
  ],
  [
    "team" => "McLaren",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/mclaren-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/mclaren.png",
    "drivers" => [
      ["nome" => "Lando Norris", "bandeira" => "gb"],
      ["nome" => "Oscar Piastri", "bandeira" => "au"]
    ]
  ],
  [
    "team" => "Aston Martin",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/aston-martin-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/aston-martin.png",
    "drivers" => [
      ["nome" => "Fernando Alonso", "bandeira" => "es"],
      ["nome" => "Lance Stroll", "bandeira" => "ca"]
    ]
  ],
  [
    "team" => "Alpine",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/alpine-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/alpine.png",
    "drivers" => [
      ["nome" => "Pierre Gasly", "bandeira" => "fr"],
      ["nome" => "Jack Doohan", "bandeira" => "au"]
    ]
  ],
  [
    "team" => "Haas",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/haas-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/haas.png",
    "drivers" => [
      ["nome" => "Esteban Ocon", "bandeira" => "fr"],
      ["nome" => "Oliver Bearman", "bandeira" => "gb"]
    ]
  ],
  [
    "team" => "Willians",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/williams-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/williams.png",
    "drivers" => [
      ["nome" => "Alexander Albon", "bandeira" => "th"],
      ["nome" => "Carlos Sainz", "bandeira" => "es"]
    ]
  ],
  [
    "team" => "Racing Bulls",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/racing-bulls-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/racing-bulls.png",
    "drivers" => [
      ["nome" => "Liam Lawson", "bandeira" => "nz"],
      ["nome" => "Isack Hadjar", "bandeira" => "fr"]
    ]
  ],
  [
    "team" => "Kick Sauber",
    "logo" => "https://media.formula1.com/content/dam/fom-website/teams/2025/kick-sauber-logo.png",
    "imagem" => "https://media.formula1.com/d_team_car_fallback_image.png/content/dam/fom-website/teams/2025/kick-sauber.png",
    "drivers" => [
      ["nome" => "Nico Hulkenberg", "bandeira" => "de"],
      ["nome" => "Gabriel Bortoleto", "bandeira" => "br"]
    ]
  ]
];


$races = [
    [
        "race" => "GP da Austrália",
        "date" => "19 de Março",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_771/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Australia_Circuit",
        "primeiraEdicao" => "1996",
        "tempoRecorde" => "1:19.813 (Charles Leclerc (2024)",
        "maiorVencedor" => "Michael Schumacher (4 vitórias)",
        "comprimento" => "5.303 km",
        "numVoltas" => 58
    ],
    [
        "race" => "GP da China",
        "date" => "23 de Março",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/China_Circuit",
        "primeiraEdicao" => "2004",
        "tempoRecorde" => "1:32.238 (Michael Schumacher (2004)",
        "maiorVencedor" => "Lewis Hamilton (6 vitórias)",
        "comprimento" => "5.451 km",
        "numVoltas" => 56
    ],
    [
        "race" => "GP da Japão",
        "date" => "6 de Abril",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Japan_Circuit",
        "primeiraEdicao" => "1987",
        "tempoRecorde" => "1:30.965 (Kimi Antonelli (2025)",
        "maiorVencedor" => "Michael Schumacher (6 vitórias)",
        "comprimento" => "5.807 km",
        "numVoltas" => 53
    ],
    [
        "race" => "GP do Bahrein",
        "date" => "13 de Abril",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Bahrain_Circuit",
        "primeiraEdicao" => "2004",
        "tempoRecorde" => "1:31.447 (Pedro de la Rosa (2005)",
        "maiorVencedor" => "Lewis Hamilton (5 vitórias)",
        "comprimento" => "5.412 km",
        "numVoltas" => 57
    ],
    [
        "race" => "GP da Arábia Saudita",
        "date" => "20 de Abril",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Saudi_Arabia_Circuit",
        "primeiraEdicao" => "2021",
        "tempoRecorde" => "1:30.734 (Lewis Hamilton (2021)",
        "maiorVencedor" => "Max Verstappen (2 vitórias)",
        "comprimento" => "6.174 km",
        "numVoltas" => 50
    ],
    [
        "race" => "GP de Miami",
        "date" => "4 de Maio",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Miami_Circuit",
        "primeiraEdicao" => "2022",
        "tempoRecorde" => "1:29.708 (Max Verstappen (2023)",
        "maiorVencedor" => "Max Verstappen (2 vitórias)",
        "comprimento" => "5.412 km",
        "numVoltas" => 57
    ],
    [
        "race" => "GP da Emília-Romanha",
        "date" => "18 de Maio",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Emilia_Romagna_Circuit",
        "primeiraEdicao" => "1980",
        "tempoRecorde" => "1:15.484 (Lewis Hamilton (2020)",
        "maiorVencedor" => "Michael Schumacher (7 vitórias), na pista atual Max Verstappen (3 vitórias)",
        "comprimento" => "4.909 km",
        "numVoltas" => 63
    ],
    [
        "race" => "GP de Mônaco",
        "date" => "25 de Maio",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Monaco_Circuit",
        "primeiraEdicao" => "1950",
        "tempoRecorde" => "1:12.909 (Lewis Hamilton (2021)",
        "maiorVencedor" => "Ayrton Senna (6 vitórias)",
        "comprimento" => "3.337km",
        "numVoltas" => 58
    ],
    [
        "race" => "GP da Espanha",
        "date" => "1 de Junho",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Spain_Circuit",
        "primeiraEdicao" => "1991",
        "tempoRecorde" => "1:16.330 (Max Verstappen (2023)",
        "maiorVencedor" => "Michael Schumacher e Lewis Hamilton (6 vitórias)",
        "comprimento" => "4.657 km",
        "numVoltas" => 66
    ],
    [
        "race" => "GP do Canadá",
        "date" => "15 de Junho",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Canada_Circuit",
        "primeiraEdicao" => "1978",
        "tempoRecorde" => "1:13.078 (Valtteri Bottas (2019)",
        "maiorVencedor" => "Michael Schumacher e Lewis Hamilton (7 vitórias)",
        "comprimento" => "4.361 km",
        "numVoltas" => 70
    ],
    [
        "race" => "GP da Áustria ",
        "date" => "29 de Junho",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Austria_Circuit",
        "primeiraEdicao" => "1970",
        "tempoRecorde" => "1:05.619 (Carlos Sainz (2020)",
        "maiorVencedor" => "Max Verstappen (4 vitórias)",
        "comprimento" => "4.318 km",
        "numVoltas" => 71
    ],
    [
        "race" => "GP da Inglaterra",
        "date" => "6 de Julho",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Great_Britain_Circuit",
        "primeiraEdicao" => "1950",
        "tempoRecorde" => "1:27.097 (Max Verstappen (2020)",
        "maiorVencedor" => "Lewis Hamilton (8 vitórias)",
        "comprimento" => "5.891 km",
        "numVoltas" => 52
    ],
    [
        "race" => "GP da Bélgica",
        "date" => "27 de Julho",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Belgium_Circuit",
        "primeiraEdicao" => "1950",
        "tempoRecorde" => "1:44.701 (Sergio Perez (2024)",
        "maiorVencedor" => "Michael Schumacher (6 vitórias)",
        "comprimento" => "7.004 km",
        "numVoltas" => 44
    ],
    [
        "race" => "GP da Hungria",
        "date" => "3 de Agosto",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Hungary_Circuit",
        "primeiraEdicao" => "1986",
        "tempoRecorde" => "1:16.627 (Lewis Hamilton (2020)",
        "maiorVencedor" => "Lewis Hamilton (8 vitórias)",
        "comprimento" => "4.381 km",
        "numVoltas" => 70
    ],
    [
        "race" => "GP da Holanda",
        "date" => "31 de Agosto",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Netherlands_Circuit",
        "primeiraEdicao" => "1952",
        "tempoRecorde" => "1:11.097 (Lewis Hamilton (2021)",
        "maiorVencedor" => "Jim Clark (4 vitórias)",
        "comprimento" => "4.259 km",
        "numVoltas" => 72
    ],
    [
        "race" => "GP da Itália",
        "date" => "7 de Setembro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Italy_Circuit",
        "primeiraEdicao" => "1950",
        "tempoRecorde" => "1:21.046 (Rubens Barrichello (2004)",
        "maiorVencedor" => "Michael Schumacher e Lewis Hamilton (5 vitórias)",
        "comprimento" => "5.793 km",
        "numVoltas" => 53
    ],
    [
        "race" => "GP do Azerbaijão",
        "date" => "21 de Setembro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Baku_Circuit",
        "primeiraEdicao" => "2016",
        "tempoRecorde" => "1:43.009 (Charles Leclerc (2019)",
        "maiorVencedor" => "Sergio Perez (8 vitórias)",
        "comprimento" => "6.003 km",
        "numVoltas" => 51
    ],
    [
        "race" => "GP de Singapura",
        "date" => "5 de Outubro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Singapore_Circuit",
        "primeiraEdicao" => "2008",
        "tempoRecorde" => "1:34.486 (Daniel Ricciardo (2024)",
        "maiorVencedor" => "Sebastian Vettel (5 vitórias)",
        "comprimento" => "4.940 km",
        "numVoltas" => 62
    ],
    [
        "race" => "GP dos Estados Unidos",
        "date" => "19 de Outubro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/USA_Circuit",
        "primeiraEdicao" => "2012",
        "tempoRecorde" => "1:36.169 (Charles Leclerc (2019)",
        "maiorVencedor" => "Lewis Hamilton (6 vitórias)",
        "comprimento" => "5.513 km",
        "numVoltas" => 56
    ],
    [
        "race" => "GP do México",
        "date" => "26 de Outubro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Mexico_Circuit",
        "primeiraEdicao" => "1963",
        "tempoRecorde" => "1:17.774 (Valtteri Bottas (2021)",
        "maiorVencedor" => "Max Verstappen (5 vitórias)",
        "comprimento" => "4.304 km",
        "numVoltas" => 71
    ],
    [
        "race" => "GP do Brasil",
        "date" => "9 de Novembro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Brazil_Circuit",
        "primeiraEdicao" => "1973",
        "tempoRecorde" => "1:10.540 (Valtteri Bottas (2018)",
        "maiorVencedor" => "Michael Schumacher (4 vitórias)",
        "comprimento" => "4.309 km",
        "numVoltas" => 71
    ],
    [
        "race" => "GP de Las Vegas",
        "date" => "22 de Novembro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Las_Vegas_Circuit",
        "primeiraEdicao" => "2023",
        "tempoRecorde" => "1:34.876 (Lando Norris (2024)",
        "maiorVencedor" => "Max Verstappen (1 vitória)",
        "comprimento" => "6.201 km",
        "numVoltas" => 50
    ],
    [
        "race" => "GP do Catar",
        "date" => "30 de Novembro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Qatar_Circuit",
        "primeiraEdicao" => "2021",
        "tempoRecorde" => "1:22.384 (Lando Norris (2024)",
        "maiorVencedor" => "Max Verstappen e Lewis Hamilton (1 vitória)",
        "comprimento" => "5.419 km",
        "numVoltas" => 57
    ],
    [
        "race" => "GP de Abu Dhabi",
        "date" => "7 de Dezembro",
        "url" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/2018-redesign-assets/Circuit%20maps%2016x9/Abu_Dhabi_Circuit",
        "primeiraEdicao" => "2009",
        "tempoRecorde" => "1:25.637 (Kevin Magnussen (2024)",
        "maiorVencedor" => "Lewis Hamilton (5 vitórias)",
        "comprimento" => "5.281 km",
        "numVoltas" => 58
    ]
];

$driverStats = [
    "Max Verstappen" => [
        "numero" => 1,
        "idade" => 27,
        "nacionalidade" => "Holandesa",
        "titulos" => 4,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/verstappen.jpg.transform/2col/image.jpg"
    ],
    "George Russell" => [
        "numero" => 63,
        "idade" => 27,
        "nacionalidade" => "Britânica",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/russell.jpg.transform/2col/image.jpg"
    ],
    "Kimi Antonelli" => [
        "numero" => 12,
        "idade" => 18,
        "nacionalidade" => "Italiana",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/antonelli"
    ],
    "Yuki Tsunoda" => [
        "numero" => 22,
        "idade" => 24,
        "nacionalidade" => "Japonesa",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/tsunoda"
    ],
    "Charles Leclerc" => [
        "numero" => 16,
        "idade" => 27,
        "nacionalidade" => "Monegasca",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/leclerc.jpg.transform/2col/image.jpg"
    ],
    "Lewis Hamilton" => [
        "numero" => 44,
        "idade" => 40,
        "nacionalidade" => "Britânica",
        "titulos" => 7,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/hamilton"
    ],
    "Lando Norris" => [
        "numero" => 4,
        "idade" => 25,
        "nacionalidade" => "Britânica",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/norris.jpg.transform/2col/image.jpg"
    ],
    "Oscar Piastri" => [
        "numero" => 81,
        "idade" => 24,
        "nacionalidade" => "Australiana",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/piastri.jpg.transform/2col/image.jpg"
    ],
    "Fernando Alonso" => [
        "numero" => 14,
        "idade" => 43,
        "nacionalidade" => "Espanhola",
        "titulos" => 2,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/alonso.jpg.transform/2col/image.jpg"
    ],
    "Lance Stroll" => [
        "numero" => 18,
        "idade" => 26,
        "nacionalidade" => "Canadense",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/content/dam/fom-website/drivers/2024Drivers/stroll.jpg.transform/2col/image.jpg"
    ],
    "Alexander Albon" => [
        "numero" => 23,
        "idade" => 29,
        "nacionalidade" => "Tailandes",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/albon"
    ],
    "Carlos Sainz" => [
        "numero" => 55,
        "idade" => 31,
        "nacionalidade" => "Espanhola",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/sainz"
    ],
    "Esteban Ocon" => [
        "numero" => 31,
        "idade" => 29,
        "nacionalidade" => "Francesa",
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/ocon"
    ],
    "Oliver Bearman" => [
        "numero" => 87,
        "idade" => 19,
        "nacionalidade" => "Britânica",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/bearman"
    ],
    "Liam Lawson" => [
        "numero" => 30,
        "idade" => 23,
        "nacionalidade" => "Neozelandêsa",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/fom-website/drivers/2025Drivers/lawson-racing-bulls"
    ],
    "Isack Hadjar" => [
        "numero" => 6,
        "idade" => 20,
        "nacionalidade" => "Francesa",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/hadjar"
    ],
    "Pierre Gasly" => [
        "numero" => 10,
        "idade" => 29,
        "nacionalidade" => "Francesa",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/gasly"
    ],
    "Jack Doohan" => [
        "numero" => 7,
        "idade" => 22,
        "nacionalidade" => "Austriaca",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/doohan"
    ],
    "Nico Hulkenberg" => [
        "numero" => 27,
        "idade" => 37,
        "nacionalidade" => "Alemã",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/hulkenberg"
    ],
    "Gabriel Bortoleto" => [
        "numero" => 5,
        "idade" => 20,
        "nacionalidade" => "Brasileira",
        "titulos" => 0,
        "foto" => "https://media.formula1.com/image/upload/f_auto,c_limit,q_auto,w_1320/content/dam/fom-website/drivers/2025Drivers/bortoleto"
    ]
];
$resource = $_GET['resource'] ?? null;

if ($resource) {
    switch ($resource) {
        case 'teams':
            if ($method !== 'GET') {
                sendError('Método não permitido para este recurso', 405);
            }
            sendJsonResponse(['teams' => $teams]);
            break;

        case 'races':
            if ($method !== 'GET') {
                sendError('Método não permitido para este recurso', 405);
            }
            sendJsonResponse(['races' => $races]);
            break;

        case 'drivers':
            if ($method !== 'GET') {
                sendError('Método não permitido para este recurso', 405);
            }
            sendJsonResponse(['driverStats' => $driverStats]);
            break;

        case 'user':
            switch ($method) {
                case 'GET':
                    $username = $_GET['username'] ?? null;
                    if (!$username) {
                        sendError('Username não fornecido', 400);
                    }
                    // Aqui você implementaria a lógica para buscar o usuário
                    // Por enquanto, simularemos que o usuário não foi encontrado
                    sendError('Usuário não encontrado', 404);
                    break;

                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!$data) {
                        sendError('Dados inválidos', 400);
                    }

                    // Validação dos campos obrigatórios
                    $requiredFields = ['username', 'password', 'name', 'favoriteTeam', 'favoriteDriver'];
                    foreach ($requiredFields as $field) {
                        if (!isset($data[$field]) || empty(trim($data[$field]))) {
                            sendError("Campo obrigatório ausente ou vazio: $field", 400);
                        }
                    }

                    // Simular criação bem-sucedida
                    sendJsonResponse([
                        'message' => 'Usuário criado com sucesso',
                        'user' => array_merge($data, ['id' => uniqid()])
                    ], 201);
                    break;

                case 'PUT':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!$data || !isset($data['username'])) {
                        sendError('Dados inválidos ou username não fornecido', 400);
                    }

                    // Validação dos campos
                    $allowedFields = ['password', 'name', 'favoriteTeam', 'favoriteDriver'];
                    $updates = array_intersect_key($data, array_flip($allowedFields));
                    
                    if (empty($updates)) {
                        sendError('Nenhum campo válido para atualização', 400);
                    }

                    // Simular atualização bem-sucedida
                    sendJsonResponse([
                        'message' => 'Usuário atualizado com sucesso',
                        'user' => $data
                    ]);
                    break;

                case 'DELETE':
                    if (!isset($_GET['username'])) {
                        sendError('Username não fornecido', 400);
                    }

                    // Simular exclusão bem-sucedida
                    sendJsonResponse(['message' => 'Usuário excluído com sucesso']);
                    break;

                default:
                    sendError('Método não permitido para este recurso', 405);
            }
            break;

        default:
            sendError('Recurso não encontrado', 404);
    }
} else {
    // Se nenhum recurso for especificado, retorna todos os dados
    if ($method !== 'GET') {
        sendError('Método não permitido', 405);
    }
    sendJsonResponse([
        'teams' => $teams,
        'races' => $races,
        'driverStats' => $driverStats
    ]);
}
?>