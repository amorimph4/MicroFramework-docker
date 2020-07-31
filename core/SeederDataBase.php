<?php

namespace Core;

use PDO;
use Core\DataBase; 

class SeederDataBase
{

    public static function tablesCreation()
    {
        try {
        	$pdo = DataBase::getDataBase();

        	$stmt = $pdo->prepare("show tables");
        	$stmt->execute();
        	$result = $stmt->fetchAll();
        	$stmt->closeCursor();
        	if (0 === count($result)) {
	            $query = "CREATE TABLE products (
	                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	                name VARCHAR(65) NOT NULL,
	                code VARCHAR(50) NOT NULL,
	                price FLOAT(10, 2) NOT NULL,
	                qtd INT NOT NULL,
	                description TEXT,
	                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	                updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
	                UNIQUE (code)
	            );

	            CREATE TABLE categorys (
	                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	                name VARCHAR(65) NOT NULL,
	                code VARCHAR(50) NOT NULL,
	                UNIQUE (code)
	            );

	            CREATE TABLE users (
	                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	                name VARCHAR(30) NOT NULL,
	                email VARCHAR(50) NOT NULL,
	                password VARCHAR(100) NOT NULL,
	                UNIQUE (email)
	            );

	            CREATE TABLE product_categorys (
	                product_id INT(6) UNSIGNED NOT NULL,
	                category_id INT(6) UNSIGNED NOT NULL,
	                FOREIGN KEY (product_id) REFERENCES products(id),
	                FOREIGN KEY (category_id) REFERENCES categorys(id)
	            );";

	            $stmt = $pdo->prepare($query);
	            $stmt->execute();
	            $stmt->closeCursor();
        	}
        } catch (Exception $e) {
            throw Exception($e->getMessage(), 500);
        }
    }

}