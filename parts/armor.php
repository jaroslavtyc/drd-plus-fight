<?php
namespace DrdPlus\Fight;
/** @var Controller $controller */
?>
<div class="panel">
    <label><select name="<?= $controller::BODY_ARMOR ?>">
            <?php /** @var array $bodyArmor */
            foreach ($controller->getPossibleBodyArmors() as $bodyArmor) {
                $bodyArmorCode = $bodyArmor['code']; ?>
                <option value="<?= $bodyArmorCode->getValue() ?>"
                        <?php if ($controller->getSelectedBodyArmor()->getValue() === $bodyArmorCode->getValue()) { ?>selected<?php }
                        if (!$bodyArmor['canUseIt']) { ?>disabled<?php } ?>>
                    <?= $bodyArmorCode->translateTo('cs') . ($controller->getProtectionOfBodyArmor($bodyArmorCode) > 0 ? (' +' . $controller->getProtectionOfBodyArmor($bodyArmorCode)) : '') ?>
                </option>
            <?php } ?>
        </select>
    </label>
</div>
<div class="panel">
    <label>
        <select name="<?= $controller::HELM ?>">
            <?php /** @var array $helm */
            foreach ($controller->getPossibleHelms() as $helm) {
                $helmCode = $helm['code']; ?>
                <option value="<?= $helmCode->getValue() ?>"
                        <?php if ($controller->getSelectedHelm()->getValue() === $helmCode->getValue()) { ?>selected<?php }
                        if (!$helm['canUseIt']) { ?>disabled<?php } ?>>
                    <?= $helmCode->translateTo('cs') . ($controller->getProtectionOfHelm($helmCode) > 0 ? (' +' . $controller->getProtectionOfHelm($helmCode)) : '') ?>
                </option>
            <?php } ?>
        </select>
    </label>
</div>
<div class="block skill">
    <div class="panel">
        <label>
            <span class="keyword"><?= $controller->getPossibleSkillForArmor()->translateTo('cs') ?></span>
        </label>
    </div>
    <div class="panel">
        <label>na stupni <input type="radio" value="0" name="<?= $controller::ARMOR_SKILL_VALUE ?>"
                                <?php if ($controller->getSelectedArmorSkillRank() === 0) { ?>checked<?php } ?>> 0,
        </label>
        <label><input type="radio" value="1" name="<?= $controller::ARMOR_SKILL_VALUE ?>"
                      <?php if ($controller->getSelectedArmorSkillRank() === 1) { ?>checked<?php } ?>> 1,
        </label>
        <label><input type="radio" value="2" name="<?= $controller::ARMOR_SKILL_VALUE ?>"
                      <?php if ($controller->getSelectedArmorSkillRank() === 2) { ?>checked<?php } ?>> 2,
        </label>
        <label><input type="radio" value="3" name="<?= $controller::ARMOR_SKILL_VALUE ?>"
                      <?php if ($controller->getSelectedArmorSkillRank() === 3) { ?>checked<?php } ?>> 3
        </label>
    </div>
</div>
<div class="block"><input type="submit" value="Přepočítat"></div>
