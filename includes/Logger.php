<?php

/**
 * Created by PhpStorm.
 * User: victor
 * Date: 08.08.17
 * Time: 14:59
 */
class Logger
{
    public static function Info( $data ) {
        $output = $data;
        if ( is_array( $output ) )
            $output = implode( ',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

    public static function InfoKeyValue( $data ) {
        $output = "";

        echo "<script>console.log( 'START COLLECTION DATA: " . $output . "' );</script>";
        foreach ($data as $key => $value) {
            $output = $key . " - " . $value;
            echo "<script>console.log( 'data: " . $output . "' );</script>";
        }


        echo "<script>console.log( 'END COLLECTION DATA' );</script>";
    }
}