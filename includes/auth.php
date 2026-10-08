<?php
session_start();

function admin_autenticado()
{
    return isset($_SESSION['admin_id']);
}

function exigir_admin()
{
    if (!admin_autenticado()) {
        header('Location: login.php');
        exit;
    }
}