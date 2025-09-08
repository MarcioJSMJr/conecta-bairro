<?php
require_once 'session.php';

class ModelException extends Exception {
    public function __construct(int $code, ?string $message = null) {
        $this->code = $code;
        $this->message = $message;
    }

    public static function handle_exception(ModelException $e) {
        if ($e->message !== null) {
            set_status(SessionStatusCode::DANGER(), $e->message);
            return;
        }

        switch ($e->getCode()) {
            //============================================ Model
            case ModelExceptionsEnum::MISSING_ID_NOT_FOUND_CONSTANT:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Ocorreu um erro durante o tratamento de outro erro.');
                break;
            
            case ModelExceptionsEnum::FILE_UPLOAD_FAILED:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Ocorreu um erro durante o upload de arquivo.');
                break;
            
            case ModelExceptionsEnum::REQUIRED_FIELD_MISSING:
                set_status(
                    SessionStatusCode::DANGER(),
                    'É necessário preencher todos os campos obrigatórios.');
                break;
            
            case ModelExceptionsEnum::INVALID_TIME_RANGE:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Intervalo de tempo inválido.');
                break;
            
            case ModelExceptionsEnum::UNAUTHENTICATED:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Você precisa estar logado para realizar esta ação.');
                break;
            
            case ModelExceptionsEnum::UNAUTHORIZED:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Você não tem permissão para realizar esta ação.');
                break;
            
            case ModelExceptionsEnum::FAILED_TO_SEND_EMAIL:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Ocorreu um erro ao enviar o e-mail.');
                break;
            
            case ModelExceptionsEnum::INVALID_CONTROLLER_SANITIZER:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Ocorreu um erro ao processar a entrada.');
                break;
            
            default:
                set_status(
                    SessionStatusCode::DANGER(),
                    'Ocorreu um erro interno no sistema.');
                break;
        }
    }
}

class ModelExceptionsEnum {
    const MISSING_ID_NOT_FOUND_CONSTANT = -2;
    const I_HAVE_NO_CLUE = -1;
    const FILE_UPLOAD_FAILED = 1;
    const REQUIRED_FIELD_MISSING = 2;
    const INVALID_TIME_RANGE = 4;
    const UNAUTHENTICATED = 5;
    const UNAUTHORIZED = 6;
    const FAILED_TO_SEND_EMAIL = 7;
    const INVALID_CONTROLLER_SANITIZER = 8;
}

class AdminUserExceptionsEnum { 
    const ID_NOT_FOUND = 100;
}

class SiteUserExceptionsEnum {
    const ID_NOT_FOUND = 200;
}

class DonationExceptionsEnum {
    const ID_NOT_FOUND = 300;
}

class DonationCategoryExceptionsEnum {
    const ID_NOT_FOUND = 400;
}

class CollectionPointExceptionsEnum {
    const ID_NOT_FOUND = 500;
}