<?php
namespace DrdPlus\Fight;

include_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

/** @noinspection PhpUnusedLocalVariableInspection */
$controller = new Controller();
?>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="" method="post">
    <input type="hidden" name="<?= $controller::DELETE_HISTORY ?>" value="1">
    <input type="submit" value="Vymazat historii">
</form>
<div class="block">
    <?php $fightProperties = $controller->getRangedFightProperties() ?>
    <div>Boj <span class="hint">(není ovlivněn výzbrojí)</span>: <?= $fightProperties->getFight() ?></div>
    <div>Útok <span class="hint">(není ovlivněn výzbrojí)</span>: <?= $fightProperties->getAttack() ?></div>
    <div>Obrana <span class="hint">(není ovlivněna výzbrojí)</span>: <?= $fightProperties->getDefense() ?></div>
    <div>Obranné číslo <span class="hint">(je ovlivněno pouze akcí, oslněním a chybějící Převahou)</span>:
        <?= $fightProperties->getDefenseNumber() ?>
    </div>
</div>
<form class="block" action="" method="get">
    <div class="block">
        <h2>Na blízko</h2>
        <div class="panel">
            <?php include __DIR__ . '/melee_weapon.php' ?>
        </div>
        <div class="panel">
            <?php include __DIR__ . '/shield_with_melee_weapon.php'; ?>
        </div>
    </div>
    <div class="block">
        <h2> Na dálku </h2>
        <div class="panel">
            <?php include __DIR__ . '/ranged_weapon.php'; ?>
        </div>
        <div class="panel">
            <?php include __DIR__ . '/shield_with_ranged_weapon.php'; ?>
        </div>
    </div>
    <div class="block">
        <h2>Zbroj</h2>
        <div class="block"><?php include __DIR__ . '/armor.php'; ?></div>
    </div>
    <div class="block"><?php include __DIR__ . '/properties.php'; ?></div>
</form>
</body>
</html>
