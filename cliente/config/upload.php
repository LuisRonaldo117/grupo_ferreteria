<?php

if (!defined('UPLOADS_DIR')) {
    require_once __DIR__ . '/paths.php';
}

class ImageUploader {
    private $uploadDir;
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private $maxSize = 5 * 1024 * 1024;

    public function __construct() {
        $this->uploadDir = UPLOADS_DIR;
        
        // Crear directorio si no existe
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true) or die('No se pudo crear el directorio de uploads: ' . $this->uploadDir);
        }
    }

    public function upload($file) {
        // Verificar errores de subida
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error al subir el archivo: " . $this->getUploadErrorMessage($file['error']));
        }

        // Validar tipo de archivo
        if (!in_array($file['type'], $this->allowedTypes)) {
            throw new Exception("Tipo de archivo no permitido. Solo se aceptan JPG, PNG, WEBP o GIF.");
        }

        // Validar tamaño
        if ($file['size'] > $this->maxSize) {
            throw new Exception("El archivo excede el tamaño máximo de " . ($this->maxSize / (1024 * 1024)) . "MB.");
        }

        // Generar nombre único
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'prod_' . uniqid() . '.' . $ext;
        $destination = $this->uploadDir . $filename;

        // Mover el archivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        } else {
            throw new Exception("Error al mover el archivo subido. Verifica los permisos del directorio.");
        }
    }

    // Método auxiliar para mensajes de error de subida
    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "El archivo excede el tamaño máximo permitido por el servidor.";
            case UPLOAD_ERR_PARTIAL:
                return "La subida del archivo fue interrumpida.";
            case UPLOAD_ERR_NO_FILE:
                return "No se seleccionó ningún archivo.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Falta la carpeta temporal en el servidor.";
            case UPLOAD_ERR_CANT_WRITE:
                return "No se pudo escribir el archivo en el disco.";
            default:
                return "Error desconocido.";
        }
    }
}
?>

