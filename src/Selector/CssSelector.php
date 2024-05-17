<?php

namespace CodeAlfa\Css2Xpath\Selector;

use CodeAlfa\Css2Xpath\SelectorFactoryInterface;
use CodeAlfa\RegexTokenizer\Css;
use SplObjectStorage;

use function preg_match_all;

use const PREG_SET_ORDER;

class CssSelector extends AbstractSelector
{
    use Css;

    protected ?TypeSelector $type;

    protected ?IdSelector $id;

    protected SplObjectStorage $classes;

    protected SplObjectStorage $attributes;

    protected SplObjectStorage $pseudoSelectors;

    protected string $combinator;

    protected ?CssSelector $descendant;

    public function __construct(
        ?TypeSelector $type = null,
        ?IdSelector $id = null,
        ?SplObjectStorage $classes = null,
        ?SplObjectStorage $attributes = null,
        ?SplObjectStorage $pseudoSelectors = null,
        string $combinator = '',
        ?CssSelector $descendant = null
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->classes = $classes ?? new SplObjectStorage();
        $this->attributes = $attributes ?? new SplObjectStorage();
        $this->pseudoSelectors = $pseudoSelectors ?? new SplObjectStorage();
        $this->combinator = $combinator;
        $this->descendant = $descendant;
    }

    public static function create(SelectorFactoryInterface $selectorFactory, string $css): static
    {
        $type = null;
        $id = null;
        $classes = new SplObjectStorage();
        $attributes = new SplObjectStorage();
        $pseudoSelectors = new SplObjectStorage();
        $combinator = '';
        $descendant = null;

        $elRx = self::cssTypeSelectorWithCaptureValueToken();
        $idRx = self::cssIdSelectorWithCaptureValueToken();
        $classRx = self::cssClassSelectorWithCaptureValueToken();
        $attrRx = self::cssAttributeSelectorWithCaptureValueToken();
        $pseudoRx = self::cssPseudoSelectorWithCaptureValueToken();
        $descRx = self::cssDescendantSelectorWithCaptureValueToken();
        $bc = self::blockCommentToken();

        $regex = "(?:{$elRx})?(?:{$idRx})?(?:{$classRx})?(?:{$attrRx})?(?:{$pseudoRx})?(?:{$descRx})?(?:\s*+{$bc})?";

        preg_match_all("#{$regex}#", $css, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (!empty($match['type'])) {
                $type = $selectorFactory->createTypeSelector(
                    $match['type'],
                    $match['typeSeparator'] ? $match['typeNs'] : null
                );
            }

            if (!empty($match['id'])) {
                $id = $selectorFactory->createIdSelector($match['id']);
            }

            if (!empty($match['class'])) {
                $classes->attach($selectorFactory->createClassSelector($match['class']));
            }

            if (!empty($match['attrName'])) {
                $attributes->attach(
                    $selectorFactory->createAttributeSelector(
                        $match['attrName'],
                        $match['attrValue'] ?? '',
                        $match['attrOperator'] ?? '',
                        $match['attrSeparator'] ? $match['attrNs'] : null,
                    )
                );
            }

            if (!empty($match['pseudoSelector'])) {
                if (
                    preg_match("#is|not|where|has#", $match['pseudoSelector'])
                    && !empty($match['pseudoSelectorList'])
                ) {
                    $pseudoSelectorList = $selectorFactory->createCssSelectorList(
                        $selectorFactory,
                        $match['pseudoSelectorList']
                    );
                    $modifier = '';
                } else {
                    $pseudoSelectorList = null;
                    $modifier = !empty($match['pseudoSelectorList']) ? $match['pseudoSelectorList'] : '';
                }

                $pseudoSelectors->attach(
                    $selectorFactory->createPseudoSelector(
                        $match['pseudoSelector'],
                        $match['pseudoPrefix'],
                        $pseudoSelectorList,
                        $modifier
                    )
                );
            }

            if (isset($match['combinator'])) {
                $combinator = $match['combinator'];
                $descendant = $selectorFactory->createCssSelector($selectorFactory, $match['descendant']);
            }
        }

        return new static(
            $type,
            $id,
            $classes,
            $attributes,
            $pseudoSelectors,
            $combinator,
            $descendant
        );
    }

    private static function cssTypeSelectorWithCaptureValueToken(): string
    {
        return "^(?:(?<typeNs>[a-zA-Z0-9-]*+)(?<typeSeparator>\|))?(?<type>(?:\*|[a-zA-Z0-9-]++))";
    }

    private static function cssIdSelectorWithCaptureValueToken(): string
    {
        $e = self::cssEscapedString();

        return "\#(?<id>(?>[a-zA-Z0-9_-]++|{$e})++)";
    }

    private static function cssClassSelectorWithCaptureValueToken(): string
    {
        $e = self::cssEscapedString();

        return "\.(?<class>(?>[a-zA-Z0-9_-]++|{$e})++)";
    }

    private static function cssAttributeSelectorWithCaptureValueToken(): string
    {
        $e = self::cssEscapedString();

        return "\[(?:(?<attrNs>[a-zA-Z0-9-]*+)(?<attrSeparator>\|))?(?<attrName>(?>[a-zA-Z0-9_-]++|{$e})++)"
        . "(?:\s*+(?<attrOperator>[~|$*^]?=)\s*?"
        . "(?|\"(?<attrValue>(?>[^\\\\\"\]]++|{$e})*+)\""
        . "|'(?<attrValue>(?>[^\\\\'\]]++|{$e})*+)'"
        . "|(?<attrValue>(?>[^\\\\\]]++|{$e})*+)))?(?:\s++[iIsS])?\s*+\]";
    }

    private static function cssPseudoSelectorWithCaptureValueToken(): string
    {
        return "(?<pseudoPrefix>::?)"
        . "(?<pseudoSelector>[a-zA-Z0-9-]++)(?<fn>\((?<pseudoSelectorList>(?>[^()]++|(?&fn))*+)\))?";
    }

    private static function cssDescendantSelectorWithCaptureValueToken(): string
    {
        return "\s*?(?<combinator>[ >+~|])\s*+(?<descendant>[^ >+~|].*+)";
    }

    private function internalRender(): string
    {
        return $this->renderTypeSelector()
            . $this->renderIdSelector()
            . $this->renderClassSelector()
            . $this->renderAttributeSelector()
            . $this->renderPseudoSelector()
            . $this->renderDescendant();
    }

    public function render(): string
    {
        $xpath = $this->internalRender();

        return "descendant-or-self::{$xpath}";
    }

    private function renderTypeSelector(): string
    {
        if ($this->type) {
            return $this->type->render();
        }

        return "*";
    }

    private function renderIdSelector(): string
    {
        if ($this->id) {
            return $this->id->render();
        }

        return '';
    }

    private function renderClassSelector(): string
    {
        $xpath = '';

        foreach ($this->classes as $class) {
            $xpath .= $class->render();
        }

        return $xpath;
    }

    private function renderAttributeSelector(): string
    {
        $xpath = '';

        foreach ($this->attributes as $attribute) {
            $xpath .= $attribute->render();
        }

        return $xpath;
    }

    private function renderPseudoSelector(): string
    {
        $pseudoXpath = '';

        foreach ($this->pseudoSelectors as $pseudoSelector) {
            $pseudoXpath .= $pseudoSelector->render();
        }

        return $pseudoXpath;
    }

    private function renderDescendant(): string
    {
        if ($this->descendant) {
            $axes = match ($this->combinator) {
                '>' => 'child::',
                '+' => 'following-sibling::*[1]/self::',
                '~' => 'following-sibling::',
                ' ' => 'descendant::',
                default => 'descendant-or-self::'
            };

            $descendant = $this->descendant->internalRender();

            return "/{$axes}{$descendant}";
        }

        return '';
    }

    public function getType(): ?TypeSelector
    {
        return $this->type;
    }

    public function getId(): ?IdSelector
    {
        return $this->id;
    }

    public function getClasses(): SplObjectStorage
    {
        return $this->classes;
    }

    public function getAttributes(): SplObjectStorage
    {
        return $this->attributes;
    }

    public function getPseudoSelectors(): SplObjectStorage
    {
        return $this->pseudoSelectors;
    }

    public function getCombinator(): string
    {
        return $this->combinator;
    }

    public function getDescendant(): static|null
    {
        return $this->descendant;
    }
}
