<?php
$pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$roles = [
    ['id'=>1,'name'=>'admin','display_name'=>'Admin','description'=>'Administrateur'],
    ['id'=>2,'name'=>'dg','display_name'=>'DG','description'=>'Directeur Général'],
    ['id'=>3,'name'=>'responsable','display_name'=>'Responsable','description'=>'Responsable Entrepôt'],
    ['id'=>4,'name'=>'agent','display_name'=>'Agent','description'=>'Agent'],
];
$pdo->beginTransaction();
try{
    foreach($roles as $r){
        $stmt = $pdo->prepare('INSERT OR REPLACE INTO roles (id,name,display_name,description,created_at,updated_at) VALUES (:id,:name,:display_name,:description,datetime("now"),datetime("now"))');
        $stmt->execute($r);
    }
    $pdo->commit();
    echo "Inserted roles\n";
}catch(Exception $e){
    $pdo->rollBack();
    echo "Error: ".$e->getMessage();
}
