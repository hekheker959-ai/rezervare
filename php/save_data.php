<?php
// php/save_data.php — Endpoint AJAX
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add_reservation':
        if (!isLoggedIn()) { echo json_encode(['success'=>false,'message'=>'Neautentificat']); exit; }
        echo json_encode(addReservation($_SESSION['user_id'], $_POST));
        break;
    case 'delete_reservation':
        if (!isLoggedIn()) { echo json_encode(['success'=>false,'message'=>'Neautentificat']); exit; }
        echo json_encode(deleteReservation($_POST['id'] ?? '', $_SESSION['user_id']));
        break;
    case 'get_reservations':
        if (!isLoggedIn()) { echo json_encode(['success'=>false,'message'=>'Neautentificat']); exit; }
        echo json_encode(['success'=>true,'reservations'=>getUserReservations($_SESSION['user_id'])]);
        break;
    case 'contact':
        saveContact($_POST);
        echo json_encode(['success'=>true]);
        break;
    default:
        echo json_encode(['success'=>false,'message'=>'Acțiune necunoscută.']);
}