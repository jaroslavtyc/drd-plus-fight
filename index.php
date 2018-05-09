<?php
namespace DrdPlus\Calculator\Fight;

include_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$controller = new Controller();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/generic/graphics.css" rel="stylesheet" type="text/css">
    <link href="css/generic/skeleton.css" rel="stylesheet" type="text/css">
    <link href="css/fight.css" rel="stylesheet" type="text/css">
    <link href="css/generic/issues.css" rel="stylesheet" type="text/css">
    <noscript>
      <link href="css/generic/no_script.css" rel="stylesheet" type="text/css">
    </noscript>
  </head>
  <body class="container">
    <div id="fb-root"></div>
    <div class="background"></div>
    <form class="block delete" action="/" method="post" onsubmit="return window.confirm('Opravdu smazat?')">
      <label>
        <input type="submit" value="Smazat" name="<?= $controller::DELETE_HISTORY ?>">
        <span class="hint">(včetně dlouhodobé paměti)</span>
      </label>
    </form>
      <?php include __DIR__ . '/vendor/drd-plus/calculator-skeleton/history_deletion.php' ?>
    <form action="" method="get">
      <div class="col">
          <?php include __DIR__ . '/parts/basic_fight_properties.php' ?>
      </div>
      <div class="col">
        <h2 id="Na blízko"><a href="#Na blízko" class="inner">Na blízko</a></h2>
        <fieldset>
            <?php include __DIR__ . '/parts/melee_weapon.php' ?>
        </fieldset>
      </div>
      <div class="col">
        <h2 id="Na dálku"><a href="#Na dálku" class="inner">Na dálku</a></h2>
        <fieldset>
            <?php include __DIR__ . '/parts/ranged_weapon.php'; ?>
        </fieldset>
      </div>
      <div class="col">
        <h2 id="Štít"><a href="#Štít" class="inner">Štít</a></h2>
        <fieldset>
            <?php include __DIR__ . '/parts/shield.php'; ?>
        </fieldset>
      </div>
      <div class="col">
        <h2 id="Zbroj"><a href="#Zbroj" class="inner">Zbroj a helma</a></h2>
        <fieldset>
            <?php include __DIR__ . '/parts/armor_and_helm.php'; ?>
        </fieldset>
      </div>
      <div class="col">
        <h2 id="Vlastnosti"><a href="#Vlastnosti" class="inner">Vlastnosti</a></h2>
        <fieldset>
            <?php include __DIR__ . '/parts/profession_and_body_properties.php'; ?>
        </fieldset>
      </div>
      <div class="col">
        <h2 id="Prostředí"><a href="#Prostředí" class="inner">Prostředí</a></h2>
        <fieldset>
            <?php include __DIR__ . '/parts/ride_and_animal_enemy.php'; ?>
        </fieldset>
      </div>
    </form>
      <?php
      /** @noinspection PhpUnusedLocalVariableInspection */
      $sourceCodeUrl = 'https://github.com/jaroslavtyc/drd-plus-fight';
      include __DIR__ . '/vendor/drd-plus/calculator-skeleton/issues.php' ?>
    <script type="text/javascript" src="js/generic/skeleton.js"></script>
  </body>
</html>
