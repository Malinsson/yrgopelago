<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/views/header.php';
?>

<main>

    <?php
    require_once __DIR__ . '/calendar.php';
    require_once __DIR__ . '/booking-form.php';


    /*$client = new \GuzzleHttp\Client();

    $response = $client->request(
        'POST',
        'https://www.yrgopelag.se/centralbank/withdraw',
        [
            'form_params' => [
                'user' => 'Rune',
                'api_key' => '10524e49-1955-484b-9368-9195f98bb7be',
                'amount' => 5,
            ],
        ]
    );

    echo $response->getStatusCode(); // 200
    echo $response->getHeaderLine('content-type')[0]; // 'application/json; charset=utf8'
    echo $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'

    if (json_decode($response->getBody(), true)['status'] === 'success') {


        $transferCode = json_decode($response->getBody(), true)['transferCode'];

        $client = new \GuzzleHttp\Client();

        $response = $client->request(
            'POST',
            'https://www.yrgopelag.se/centralbank/deposit',
            [
                'form_params' => [
                    'user' => 'Malin',
                    'uuid-string' => $transferCode,
                ],
            ]
        );

        echo $response->getStatusCode(); // 200
        echo $response->getHeaderLine('content-type')[0]; // 'application/json; charset=utf8'
        echo $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'
    }*/
    ?>
</main>

<?php
require_once __DIR__ . '/views/footer.php';
?>