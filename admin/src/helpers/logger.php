<?php

class Logger {
    
    public static function log(mysqli $db, string $action, ?string $target_type = null, ?int $target_id = null, ?array $details = null): void {
        self::commit_log($db, $action, $target_type, $target_id, $details);
    }

    public static function log_update(mysqli $db, string $action, object $before_object, array $after_data): void {
        $changes = [];
        $target_type = get_class($before_object);
        $target_id = $before_object->id;

        foreach ($after_data as $key => $new_value) {
            if (property_exists($before_object, $key) && $new_value !== null && $before_object->$key != $new_value) {
                $changes[$key] = [
                    'de' => $before_object->$key,
                    'para' => $new_value
                ];
            }
        }

        if (!empty($changes)) {
            self::commit_log($db, $action, $target_type, $target_id, $changes);
        }
    }

    private static function commit_log(mysqli $db, string $action, ?string $target_type, ?int $target_id, ?array $details): void {
        // ALTERADO: Busca os dados da sessão do admin
        $admin_user_id = $_SESSION[ADMIN_SESSION]['id'] ?? null;
        $admin_user_name = $_SESSION[ADMIN_SESSION]['name'] ?? 'System';
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $details_json = $details ? json_encode($details, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null;

        // ALTERADO: Query para a nova tabela 'admin_activity_logs' com as colunas corretas
        $query = "INSERT INTO admin_activity_logs (admin_user_id, admin_user_name, action, target_type, target_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        if ($stmt) {
            // Os tipos de bind_param continuam os mesmos: (i, s, s, s, i, s, s)
            $stmt->bind_param('isssiss', $admin_user_id, $admin_user_name, $action, $target_type, $target_id, $details_json, $ip_address);
            $stmt->execute();
            $stmt->close();
        }
    }
}