<?php
// php/functions.php — Camere & Rezervări

// ---- CAMERE (definite static, pot fi mutate în JSON) ----
function getRooms(): array {
    return [
        [
            'id'       => 'deluxe',
            'name'     => 'Camera Deluxe',
            'name_en'  => 'Deluxe Room',
            'name_ru'  => 'Номер Делюкс',
            'desc'     => 'Spațioasă și luminoasă, cu vedere la grădina hotelului. Pat king-size, baie de marmură.',
            'desc_en'  => 'Spacious and bright, overlooking the hotel garden. King-size bed, marble bathroom.',
            'desc_ru'  => 'Просторный и светлый, с видом на сад отеля. Кровать king-size, мраморная ванная.',
            'price'    => 180,
            'icon'     => '🛏️',
            'badge'    => 'Popular',
            'features' => ['King-size', 'Wi-Fi', 'Minibar', 'A/C'],
            'capacity' => 2,
        ],
        [
            'id'       => 'suite',
            'name'     => 'Suite Junior',
            'name_en'  => 'Junior Suite',
            'name_ru'  => 'Полулюкс',
            'desc'     => 'Suită elegantă cu salon separat, terasă privată și jacuzzi. Ideal pentru cupluri.',
            'desc_en'  => 'Elegant suite with separate living room, private terrace and jacuzzi. Ideal for couples.',
            'desc_ru'  => 'Элегантный люкс с отдельной гостиной, частной террасой и джакузи.',
            'price'    => 320,
            'icon'     => '🌟',
            'badge'    => 'Premium',
            'features' => ['King-size', 'Jacuzzi', 'Terasă', 'Butler'],
            'capacity' => 2,
        ],
        [
            'id'       => 'presidential',
            'name'     => 'Suite Prezidențială',
            'name_en'  => 'Presidential Suite',
            'name_ru'  => 'Президентский люкс',
            'desc'     => 'Vârful eleganței. 3 camere, panoramă de 180°, servicii personalizate 24/7.',
            'desc_en'  => 'The pinnacle of elegance. 3 rooms, 180° panorama, personalized 24/7 services.',
            'desc_ru'  => 'Вершина элегантности. 3 комнаты, панорама 180°, персональный сервис 24/7.',
            'price'    => 650,
            'icon'     => '👑',
            'badge'    => 'Exclusiv',
            'features' => ['3 camere', 'Panoramă', 'Chef privat', 'Limuzină'],
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
            'badge'    => 'Business',
            'features' => ['2 paturi', 'Wi-Fi', 'Birou', 'A/C'],
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