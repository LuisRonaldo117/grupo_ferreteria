<?php

require_once __DIR__ . '/../modelos/reclamo.modelo.php';

class ReclamoControlador {
    private $reclamoModelo;

    public function __construct($db) {
        $this->reclamoModelo = new ReclamoModelo($db);
    }

    public function guardarReclamo() {
        // Verificar si es una petición AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if (!isset($_SESSION['id_cliente'])) {
            error_log("Reclamo fallido: No hay id_cliente en la sesión");
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para enviar un reclamo.',
                    'redirect' => true,
                    'redirect_url' => '?ruta=login'
                ]);
                exit;
            }
            
            $_SESSION['reclamo_message'] = 'Debes iniciar sesión para enviar un reclamo.';
            $_SESSION['reclamo_success'] = false;
            header("Location: ?ruta=contacto");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $asunto = trim($_POST['subject'] ?? '');
            $mensaje = trim($_POST['message'] ?? '');
            $id_cliente = $_SESSION['id_cliente'];

            error_log("Intentando guardar reclamo para id_cliente: $id_cliente, Asunto: $asunto");

            if (empty($asunto) || empty($mensaje)) {
                error_log("Reclamo fallido: Asunto o mensaje vacíos");
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Por favor, completa el asunto y el mensaje.'
                    ]);
                    exit;
                }
                
                $_SESSION['reclamo_message'] = 'Por favor, completa el asunto y el mensaje.';
                $_SESSION['reclamo_success'] = false;
                header("Location: ?ruta=contacto");
                exit;
            }

            // Crear descripción
            $descripcion = $mensaje;

            if ($this->reclamoModelo->guardarReclamo($id_cliente, $descripcion)) {
                error_log("Reclamo guardado exitosamente para id_cliente: $id_cliente");
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => '¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.',
                        'title' => 'Mensaje Enviado'
                    ]);
                    exit;
                }
                
                $_SESSION['reclamo_message'] = 'Reclamo enviado con éxito. Nos pondremos en contacto contigo pronto.';
                $_SESSION['reclamo_success'] = true;
            } else {
                error_log("Error al guardar reclamo para id_cliente: $id_cliente");
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al enviar el mensaje. Por favor, intenta de nuevo.'
                    ]);
                    exit;
                }
                
                $_SESSION['reclamo_message'] = 'Error al enviar el reclamo. Por favor, intenta de nuevo.';
                $_SESSION['reclamo_success'] = false;
            }

            header("Location: ?ruta=contacto");
            exit;
        }

        error_log("Reclamo fallido: Método no permitido");
        
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido.'
            ]);
            exit;
        }
        
        $_SESSION['reclamo_message'] = 'Método no permitido.';
        $_SESSION['reclamo_success'] = false;
        header("Location: ?ruta=contacto");
        exit;
    }
}