<?php 
 class ProtectedWeb {
    public function __construct(){

    }

    public static function methodPostOnly(){
        /*
        include '../classes/class.protected_web.php';
        ProtectedWeb::methodPostOnly();
        */
        if($_SERVER['REQUEST_METHOD'] !== "POST"){
            echo json_encode([
                'status' => '400',
                'message' => 'method invalid!!!'
            ]);
            exit();
        }
    }

    public static function magic_quotes_array($input){
        //$_REQUEST = ProtectedWeb::magic_quotes_array($_REQUEST);
        foreach($input as $key => $value){
            $input[$key] = filter_var($input[$key],FILTER_SANITIZE_MAGIC_QUOTES);
        }
        return $input;
    }

    public static function magic_quotes_special_chars_array($input){
        //$_REQUEST = ProtectedWeb::magic_quotes_special_chars_array($_REQUEST);
        foreach($input as $key => $value){
            $input[$key] = filter_var($input[$key],FILTER_SANITIZE_MAGIC_QUOTES);
            $input[$key] = filter_var($input[$key],FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $input;
    }

    public static function login_only(){
        // ProtectedWeb::login_only();
        if(!isset($_SESSION['login_user_id'])){
            echo json_encode([
                'status' => '400',
                'message' => 'please login !!!'
            ]);
            exit();
        }
    }








    // public static function number_int($input){
    //     return filter_var($input,FILTER_SANITIZE_NUMBER_INT);
    // }
    // public static function number_float($input){
    //     return filter_var($input,FILTER_SANITIZE_NUMBER_FLOAT);
    // }
    // public static function string($input){
    //     return filter_var($input,FILTER_SANITIZE_STRING);
    // }
    // public static function special_chars($input){
    //     return filter_var($input,FILTER_SANITIZE_SPECIAL_CHARS);
    // }
}