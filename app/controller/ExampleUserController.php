<?php

namespace App\Controller;

use App\Database;

class UserController
{
    public function index()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT id, nombre, email FROM users LIMIT 10");

            return json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id, nombre, email FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if (!$user) {
                http_response_code(404);
                return json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
            }

            return json_encode(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

            if (empty($data['nombre']) || empty($data['email'])) {
                http_response_code(400);
                return json_encode(['success' => false, 'error' => 'Nombre y email requeridos']);
            }

            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO users (nombre, email) VALUES (?, ?)");
            $stmt->execute([$data['nombre'], $data['email']]);

            return json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

