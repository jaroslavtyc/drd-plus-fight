<?php
namespace DrdPlus\Fight;

use DrdPlus\Codes\Skills\PsychicalSkillCode;

/** @var Controller $controller */
?>

<div class="block"><label><input type="checkbox" value="1"
                                 name="<?= $controller::FIGHT_FREE_WILL_ANIMAL ?>"
                                 <?php if ($controller->fightFreeWillAnimal()) { ?>checked="checked" <?php } ?>>
        Bojuješ se zvířetem s vlastní vůlí</label>
</div>
<div class="block">
    Dovednost <span
            class="keyword"><?= PsychicalSkillCode::getIt(PsychicalSkillCode::ZOOLOGY)->translateTo('cs') ?></span>
    <label>na stupni <input type="radio" value="0" name="<?= $controller::ZOOLOGY_SKILL_RANK ?>"
                            <?php if ($controller->getSelectedZoologySkillRank() === 0) { ?>checked<?php } ?>>
        0,
    </label>
    <label><input type="radio" value="1" name="<?= $controller::ZOOLOGY_SKILL_RANK ?>"
                  <?php if ($controller->getSelectedZoologySkillRank() === 1) { ?>checked<?php } ?>> 1,
    </label>
    <label><input type="radio" value="2" name="<?= $controller::ZOOLOGY_SKILL_RANK ?>"
                  <?php if ($controller->getSelectedZoologySkillRank() === 2) { ?>checked<?php } ?>> 2,
    </label>
    <label><input type="radio" value="3" name="<?= $controller::ZOOLOGY_SKILL_RANK ?>"
                  <?php if ($controller->getSelectedZoologySkillRank() === 3) { ?>checked<?php } ?>> 3
    </label>
</div>
<div class="block"><input type="submit" value="Přepočítat"></div>
