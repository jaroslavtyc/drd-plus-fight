<?php
namespace DrdPlus\Fight;

use DrdPlus\Background\BackgroundParts\Ancestry;
use DrdPlus\Background\BackgroundParts\SkillsFromBackground;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Codes\ProfessionCode;
use DrdPlus\CombatActions\CombatActions;
use DrdPlus\FightProperties\BodyPropertiesForFight;
use DrdPlus\FightProperties\FightProperties;
use DrdPlus\Health\Health;
use DrdPlus\Health\Inflictions\Glared;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Professions\Commoner;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Properties\Body\Height;
use DrdPlus\Properties\Body\HeightInCm;
use DrdPlus\Properties\Body\Size;
use DrdPlus\Properties\Derived\Speed;
use DrdPlus\Skills\Combined\CombinedSkills;
use DrdPlus\Skills\Physical\PhysicalSkills;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use DrdPlus\Skills\Skills;
use DrdPlus\Tables\Tables;
use Granam\Integer\PositiveIntegerObject;
use Granam\Strict\Object\StrictObject;

class Controller extends StrictObject
{
    const HISTORY_TOKEN = 'historyToken';

    public function __construct()
    {
        $afterYear = (new \DateTime('+ 1 year'))->getTimestamp();
        $parameters = ['meleeWeapon', 'rangedWeapon'];
        if (!empty($_GET)) {
            foreach ($parameters as $name) {
                $this->setCookie($name, $_GET[$name] ?? null, $afterYear);
            }
            setcookie(self::HISTORY_TOKEN, md5_file(__FILE__), $afterYear);
        } elseif (!$this->cookieHistoryIsValid()) {
            setcookie(self::HISTORY_TOKEN, null);
            foreach ($parameters as $parameter) {
                setcookie($parameter, null);
            }
        }
    }

    private function setCookie(string $name, $value, int $expire = 0)
    {
        setcookie(
            $name,
            $value,
            $expire,
            '/',
            '',
            !empty($_SERVER['HTTPS']), // secure only ?
            true // http only
        );
    }

    /**
     * @return MeleeWeaponCode
     */
    public function getSelectedMeleeWeapon(): MeleeWeaponCode
    {
        $meleeWeaponValue = $this->getValueFromRequest('meleeWeapon');
        if (!$meleeWeaponValue) {
            return MeleeWeaponCode::getIt(MeleeWeaponCode::HAND);
        }

        return MeleeWeaponCode::getIt($meleeWeaponValue);
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function getValueFromRequest(string $name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }
        if (array_key_exists($name, $_COOKIE) && $this->cookieHistoryIsValid()) {
            return $_COOKIE[$name];
        }

        return null;
    }

    private function cookieHistoryIsValid(): bool
    {
        return !empty($_COOKIE['historyToken']) && $_COOKIE['historyToken'] === md5_file(__FILE__);
    }

    /**
     * @return RangedWeaponCode|null
     */
    public function getSelectedRangedWeapon()
    {
        $rangedWeaponValue = $this->getValueFromRequest('rangedWeapon');
        if (!$rangedWeaponValue) {
            return null;
        }

        return RangedWeaponCode::getIt($rangedWeaponValue);
    }

    public function getMeleeFightProperties(): FightProperties
    {
        return new FightProperties(
            new BodyPropertiesForFight(
                $strength = Strength::getIt(0),
                Strength::getIt(0),
                Strength::getIt(0),
                $agility = Agility::getIt(0),
                Knack::getIt(0),
                Will::getIt(0),
                Intelligence::getIt(0),
                Charisma::getIt(0),
                Size::getIt(0),
                $height = Height::getIt(HeightInCm::getIt(150), Tables::getIt()),
                Speed::getIt($strength, $agility, $height)
            ),
            new CombatActions([], Tables::getIt()),
            Skills::createSkills(
                new ProfessionLevels(
                    ProfessionZeroLevel::createZeroLevel(Commoner::getIt()),
                    $firstLevel = ProfessionFirstLevel::createFirstLevel(Commoner::getIt())
                ),
                SkillsFromBackground::getIt(
                    new PositiveIntegerObject(0),
                    Ancestry::getIt(new PositiveIntegerObject(0), Tables::getIt()),
                    Tables::getIt()
                ),
                new PhysicalSkills($firstLevel),
                new PsychicalSkills($firstLevel),
                new CombinedSkills($firstLevel),
                Tables::getIt()
            ),
            BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR),
            HelmCode::getIt(HelmCode::WITHOUT_HELM),
            ProfessionCode::getIt(ProfessionCode::COMMONER),
            Tables::getIt(),
            $this->getSelectedMeleeWeapon(),
            ItemHoldingCode::getIt(
                Tables::getIt()->getArmourer()->isTwoHandedOnly($this->getSelectedMeleeWeapon())
                    ? ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS)
                    : ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND)
            ),
            false, // does not fight with two weapons
            ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD),
            false, // enemy is not faster
            Glared::createWithoutGlare(new Health())
        );
    }

    public function getMeleeWeaponCodes(): array
    {
        return [
            WeaponCategoryCode::AXE => MeleeWeaponCode::getAxeCodes(),
            WeaponCategoryCode::KNIFE_AND_DAGGER => MeleeWeaponCode::getKnifeAndDaggerCodes(),
            WeaponCategoryCode::MACE_AND_CLUB => MeleeWeaponCode::getMaceAndClubCodes(),
            WeaponCategoryCode::MORNINGSTAR_AND_MORGENSTERN => MeleeWeaponCode::getMorningstarAndMorgensternCodes(),
            WeaponCategoryCode::SABER_AND_BOWIE_KNIFE => MeleeWeaponCode::getSaberAndBowieKnifeCodes(),
            WeaponCategoryCode::STAFF_AND_SPEAR => MeleeWeaponCode::getStaffAndSpearCodes(),
            WeaponCategoryCode::SWORD => MeleeWeaponCode::getSwordCodes(),
            WeaponCategoryCode::VOULGE_AND_TRIDENT => MeleeWeaponCode::getVoulgeAndTridentCodes(),
            WeaponCategoryCode::UNARMED => MeleeWeaponCode::getUnarmedCodes(),
        ];
    }

    public function getRangedWeaponCodes(): array
    {
        return [
            WeaponCategoryCode::BOW => RangedWeaponCode::getBowValues(),
            WeaponCategoryCode::CROSSBOW => RangedWeaponCode::getCrossbowValues(),
        ];
    }
}