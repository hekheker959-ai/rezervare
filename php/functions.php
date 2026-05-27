<?php
// php/functions.php — Camere & Rezervări

// ---- CAMERE (definite static, pot fi mutate în JSON) ----
function getRooms(): array {
    return [
        [
            'id'       => 'standard',
            'name'     => 'Cameră Standard',
            'name_en'  => 'Standard Room',
            'name_ru'  => 'Стандартный номер',
            'desc'     => 'Cameră spațioasă cu vedere la oraș, perfectă pentru sejururi confortabile.',
            'desc_en'  => 'Spacious room with city view, perfect for comfortable stays.',
            'desc_ru'  => 'Просторный номер с видом на город, идеально подходит для комфортного проживания.',
            'price'    => 120,
            'icon'     => '🛏️',
            'badge'    => '',
            'features' => ['Wi-Fi', 'TV', 'Minibar'],
            'capacity' => 2,
        ],
        [
            'id'       => 'deluxe',
            'name'     => 'Cameră Deluxe',
            'name_en'  => 'Deluxe Room',
            'name_ru'  => 'Номер Делюкс',
            'desc'     => 'Cameră premium cu amenajări elegante și spațiu generos.',
            'desc_en'  => 'Premium room with elegant furnishings and generous space.',
            'desc_ru'  => 'Премиум номер с элегантной обстановкой и просторной площадью.',
            'price'    => 180,
            'icon'     => '👑',
            'badge'    => 'Popular',
            'features' => ['Wi-Fi', 'TV', 'Minibar', 'Jacuzzi'],
            'capacity' => 2,
        ],
        [
            'id'       => 'executive',
            'name'     => 'Suite Executive',
            'name_en'  => 'Executive Suite',
            'name_ru'  => 'Люкс Экзекьютив',
            'desc'     => 'Suite de lux cu living separat și facilități exclusive.',
            'desc_en'  => 'Luxury suite with separate living room and exclusive amenities.',
            'desc_ru'  => 'Люкс с отдельной гостиной и эксклюзивными удобствами.',
            'price'    => 280,
            'icon'     => '✨',
            'badge'    => '',
            'features' => ['Wi-Fi', 'TV', 'Minibar', 'Jacuzzi', 'Terasă'],
            'capacity' => 2,
        ],
        [
            'id'       => 'presidential',
            'name'     => 'Suite Prezidențială',
            'name_en'  => 'Presidential Suite',
            'name_ru'  => 'Президентский люкс',
            'desc'     => 'Cel mai luxos spațiu cu servicii personalizate și vedere panoramică.',
            'desc_en'  => 'The most luxurious space with personalized services and panoramic view.',
            'desc_ru'  => 'Самое роскошное пространство с персональным сервисом и панорамным видом.',
            'price'    => 450,
            'icon'     => '🌟',
            'badge'    => 'Lux',
            'features' => ['Wi-Fi', 'TV', 'Minibar', 'Jacuzzi', 'Terasă', 'Butler'],
            'capacity' => 4,
        ],
        [
            'id'       => 'twin',
            'name'     => 'Camera Twin',
            'name_en'  => 'Twin Room',
            'name_ru'  => 'Двухместный номер',
            'desc'     => 'Două paturi single confortabile. Perfectă pentru prieteni sau colegi de afaceri.',
            'desc_en'  => 'Two comfortable single beds. Perfect for friends or business colleagues.',
            'desc_ru'  => 'Две удобные односпальные кровати. Отлично подходит для друзей или коллег.',
            'price'    => 150,
            'icon'     => '🛌',
            'badge'    => '',
            'features' => ['2 paturi', 'Wi-Fi', 'TV', 'A/C'],
            'capacity' => 2,
        ],
    ];
}

function getRoomById(string $id): ?array {
    foreach (getRooms() as $r) if ($r['id'] === $id) return $r;
    return null;
}

// ---- REZERVĂRI ----
function getReservations(): array {
    $file = __DIR__ . '/../data/reservations.json';
    if (!file_exists($file)) { file_put_contents($file, '[]'); return []; }
    return json_decode(file_get_contents($file), true) ?? [];
}

function saveReservations(array $res): void {
    file_put_contents(__DIR__ . '/../data/reservations.json',
        json_encode(array_values($res), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function getUserReservations(string $userId): array {
    return array_values(array_filter(getReservations(), fn($r) => $r['user_id'] === $userId));
}

function addReservation(string $userId, array $data): array {
    $room = getRoomById($data['room_id'] ?? '');
    if (!$room) return ['success' => false, 'message' => 'Camera nu există.'];

    $checkin  = $data['checkin']  ?? '';
    $checkout = $data['checkout'] ?? '';
    if (!$checkin || !$checkout) return ['success' => false, 'message' => 'Datele sunt obligatorii.'];
    if (strtotime($checkout) <= strtotime($checkin))
        return ['success' => false, 'message' => 'Check-out trebuie să fie după check-in.'];

    $nights = (int)((strtotime($checkout) - strtotime($checkin)) / 86400);
    $total  = $nights * $room['price'];

    $reservations = getReservations();
    $reservations[] = [
        'id'         => uniqid('r_', true),
        'user_id'    => $userId,
        'room_id'    => $room['id'],
        'room_name'  => $room['name'],
        'checkin'    => $checkin,
        'checkout'   => $checkout,
        'guests'     => intval($data['guests'] ?? 1),
        'nights'     => $nights,
        'total'      => $total,
        'requests'   => htmlspecialchars($data['requests'] ?? '', ENT_QUOTES),
        'status'     => 'confirmed',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    saveReservations($reservations);
    return ['success' => true, 'message' => 'Rezervare confirmată!', 'total' => $total, 'nights' => $nights];
}

function deleteReservation(string $id, string $userId): array {
    $res = getReservations();
    $found = false;
    $res = array_values(array_filter($res, function($r) use ($id, $userId, &$found) {
        if ($r['id'] === $id && $r['user_id'] === $userId) { $found = true; return false; }
        return true;
    }));
    if (!$found) return ['success' => false, 'message' => 'Rezervarea nu a fost găsită.'];
    saveReservations($res);
    return ['success' => true, 'message' => 'Rezervarea a fost anulată.'];
}

function updateReservationStatus(string $id, string $userId, string $status): array {
    $res = getReservations();
    $found = false;
    foreach ($res as &$r) {
        if ($r['id'] === $id && $r['user_id'] === $userId) {
            $r['status'] = $status; $found = true; break;
        }
    }
    if (!$found) return ['success' => false, 'message' => 'Rezervarea nu a fost găsită.'];
    saveReservations($res);
    return ['success' => true];
}

// ---- CONTACT ----
function saveContact(array $data): void {
    $file = __DIR__ . '/../data/contacts.json';
    $contacts = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
    $contacts[] = [
        'id'         => uniqid('c_', true),
        'name'       => htmlspecialchars($data['name'] ?? '', ENT_QUOTES),
        'email'      => strtolower(trim($data['email'] ?? '')),
        'message'    => htmlspecialchars($data['message'] ?? '', ENT_QUOTES),
        'created_at' => date('Y-m-d H:i:s'),
    ];
    file_put_contents($file, json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}