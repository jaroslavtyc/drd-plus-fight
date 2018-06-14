<?php
namespace DrdPlus\FightCalculator;

use DrdPlus\AttackSkeleton\AttackController;
use DrdPlus\AttackSkeleton\CustomArmamentsService;
use DrdPlus\AttackSkeleton\PreviousProperties;
use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\Tools\ToNumber;

class FightController extends AttackController
{
    public const PROFESSION = 'profession';
    public const MELEE_FIGHT_SKILL = 'melee_fight_skill';
    public const MELEE_FIGHT_SKILL_RANK = 'melee_fight_skill_rank';
    public const RANGED_FIGHT_SKILL = 'ranged_fight_skill';
    public const RANGED_FIGHT_SKILL_RANK = 'ranged_fight_skill_rank';
    public const SHIELD_USAGE_SKILL_RANK = 'shield_usage_skill_rank';
    public const FIGHT_WITH_SHIELDS_SKILL_RANK = 'fight_with_shields_skill_rank';
    public const ARMOR_SKILL_VALUE = 'armor_skill_value';
    public const ON_HORSEBACK = 'on_horseback';
    public const RIDING_SKILL_RANK = 'riding_skill_rank';
    public const FIGHT_FREE_WILL_ANIMAL = 'fight_free_will_animal';
    public const ZOOLOGY_SKILL_RANK = 'zoology_skill_rank';
    public const RANGED_TARGET_DISTANCE = 'ranged_target_distance';
    public const RANGED_TARGET_SIZE = 'ranged_target_size';

    /** @var Fight */
    private $fight;

    /**
     * @param string $sourceCodeUrl
     * @param string $documentRoot
     * @param string $vendorRoot
     * @param string|null $partsRoot
     * @param string|null $genericPartsRoot
     * @param int|null $cookiesTtl
     * @param array|null $selectedValues
     */
    public function __construct(
        string $sourceCodeUrl,
        string $documentRoot,
        string $vendorRoot,
        string $partsRoot = null,
        string $genericPartsRoot = null,
        int $cookiesTtl = null,
        array $selectedValues = null
    )
    {
        parent::__construct(
            $sourceCodeUrl,
            'fight' /* cookies postfix */,
            $documentRoot,
            $vendorRoot,
            $partsRoot,
            $genericPartsRoot,
            $cookiesTtl,
            $selectedValues
        );
        $this->fight = new Fight(
            $this->getCurrentValues(),
            $this->getCurrentProperties(),
            $this->getHistoryWithSkillRanks(),
            new PreviousProperties($this->getHistoryWithSkillRanks()),
            new CustomArmamentsService(),
            Tables::getIt()
        );
    }

    /**
     * @param array $values
     * @param string $cookiesPostfix
     * @param int|null $cookiesTtl
     * @return History|HistoryWithSkills
     */
    protected function createHistory(array $values, string $cookiesPostfix, int $cookiesTtl = null): History
    {
        return new HistoryWithSkills(
            [
                self::MELEE_FIGHT_SKILL => self::MELEE_FIGHT_SKILL_RANK,
                self::RANGED_FIGHT_SKILL => self::RANGED_FIGHT_SKILL_RANK,
                self::RANGED_FIGHT_SKILL => self::RANGED_FIGHT_SKILL_RANK,
            ],
            $this->shouldDeleteHistory(),
            $values, // values to remember
            !empty($values[self::REMEMBER_CURRENT]), // should remember given values
            $cookiesPostfix,
            $cookiesTtl
        );
    }

    private function shouldDeleteHistory(): bool
    {
        return !empty($_POST[self::DELETE_HISTORY]);
    }

    /**
     * @return Fight
     */
    public function getFight(): Fight
    {
        return $this->fight;
    }

    /**
     * @return HistoryWithSkills|History
     */
    private function getHistoryWithSkillRanks(): HistoryWithSkills
    {
        return $this->getHistory();
    }

    public function getHistoryMeleeSkillRanksJson(): string
    {
        return $this->arrayToJson($this->getHistoryWithSkillRanks()->getPreviousSkillRanks(self::MELEE_FIGHT_SKILL_RANK));
    }

    private function arrayToJson(array $values): string
    {
        return \json_encode($values, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
    }

    public function getHistoryRangedSkillRanksJson(): string
    {
        return $this->arrayToJson($this->getHistoryWithSkillRanks()->getPreviousSkillRanks(self::RANGED_FIGHT_SKILL_RANK));
    }

    /**
     * @return PhysicalSkillCode
     */
    public function getShieldUsageSkillCode(): PhysicalSkillCode
    {
        return PhysicalSkillCode::getIt(PhysicalSkillCode::SHIELD_USAGE);
    }

    /**
     * @return PhysicalSkillCode
     */
    public function getFightWithShieldsSkillCode(): PhysicalSkillCode
    {
        return PhysicalSkillCode::getIt(PhysicalSkillCode::FIGHT_WITH_SHIELDS);
    }

    /**
     * @param int|IntegerInterface $previous
     * @param int|IntegerInterface $current
     * @return string
     */
    public function getClassForChangedValue($previous, $current): string
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if (ToInteger::toInteger($previous) < ToInteger::toInteger($current)) {
            return 'increased';
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if (ToInteger::toInteger($previous) > ToInteger::toInteger($current)) {
            return 'decreased';
        }

        return '';
    }

    public function formatNumber($number): string
    {
        $number = ToNumber::toNumber($number);

        return $number >= 0
            ? '+' . $number
            : (string)$number;
    }
}