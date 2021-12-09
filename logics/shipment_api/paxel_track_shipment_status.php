<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include class
include_once 'paxel.php';

// initialize child
$tracker = new Paxel();

// make sure data is not empty
if (

    !empty($_POST['airway_bill'])

) {

    try {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://stage-commerce-api.paxel.co/v1/shipments/' . $_POST['airway_bill'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'X-Paxel-API-Key: ' . Paxel::API_KEY,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    } catch (Throwable $error) {
        http_response_code(500);
        echo "Error: {$error->getMessage()}";
    }

}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("Error: " => "Data is incomplete."));
}


// {
//   "message": "OK",
//   "status_code": 200,
//   "data": {
//     "airwaybill_code": "EM.3BM5H5WOBN-20180413-8-X8H3YN",
//     "invoice_number": "A8HGK893J8",
//     "payment_type": "CRD",
//     "invoice_value": 10020000,
//     "origin": {
//       "name": "Jhon Doe",
//       "email": "jhondoe@paxel.co",
//       "phone": "081987654321",
//       "address": "Jl. Sultan Iskandar Muda No.6C",
//       "note": "Lantai 6 divisi IT",
//       "longitude": 106.781564,
//       "latitude": -6.259275,
//       "province": "DKI Jakarta",
//       "city": "Jakarta Selatan",
//       "district": "Kebayoran Lama",
//       "zip_code": "12240"
//     },
//     "destination": {
//       "name": "Jhon Lenon",
//       "email": "jhonlenon@paxel.co",
//       "phone": "081122334456",
//       "address": "Muara Karang Blok 7",
//       "note": "Masuk dari gang sebelah Muara Kuring",
//       "longitude": 106.581593,
//       "latitude": -6.17995,
//       "province": "DKI Jakarta",
//       "city": "Jakarta Utara",
//       "district": "Penjaringan",
//       "zip_code": "14450"
//     },
//     "items": [
//       {
//         "code": "SKU0000000001",
//         "name": "Samsung Galaxy S9",
//         "category": "Handphone",
//         "is_fragile": true,
//         "price": 10000000,
//         "quantity": 1,
//         "weight": 2500,
//         "length": 30,
//         "width": 14,
//         "height": 8
//       }
//     ],
//     "pickup_datetime": "2018-04-23 12:30:00",
//     "need_insurance": false,
//     "note": "jangan dibanting",
//     "shipping_cost": 50000,
//     "created_datetime": "2018-04-23 10:39:17",
//     "estimated_pickup_date": "2018-04-23",
//     "estimated_pickup_min_time": "12:00",
//     "estimated_pickup_max_time": "14:00",
//     "estimated_arrival_date": "2018-04-30",
//     "estimated_arrival_min_time": "14:00",
//     "estimated_arrival_max_time": "16:00",
//     "photo": "http://image.paxel.co/shipments/EM.3BM5H5WOBN-20180413-8-X8H3YN/papv.jpg",
//     "signature": "http://image.paxel.co/shipments/EM.3BM5H5WOBN-20180413-8-X8H3YN/signature.jpg",
//     "latest_status": "PDO",
//     "delivery_datetime": "2018-04-30 16:21:05",
//     "logs": [
//       {
//         "created_datetime": "2018-04-23 16:10:55",
//         "name": "Budi",
//         "address": "Jl. Sultan Iskandar Muda No.6C",
//         "note": "Lantai 6 divisi IT",
//         "longitude": 106.781564,
//         "latitude": -6.259275,
//         "province": "DKI Jakarta",
//         "city": "Jakarta Selatan",
//         "district": "Kebayoran Lama",
//         "status": "Picked up by Ahmad Faisal"
//       }
//     ],
//     "cancellation_reason": "penjual kehabisan stok"
//   }
// }