<?php

namespace CodeAlfa\Css2Xpath\Selector;

class AttributeSelector extends AbstractSelector
{
    protected ?string $namespace;
    protected string $name;
    protected string $operator;
    protected string $value;

    public function __construct(string $name, string $value = '', string $operator = '', ?string $namespace = null)
    {
        $this->name = $name;
        $this->value = $this->cssStripSlash($value);
        $this->operator = $operator;
        $this->namespace = $namespace;
    }

    public function render(): string
    {
        $attrName = $this->namespace !== null ? "{$this->namespace}:{$this->name}" : "{$this->name}";
        $delim = $this->getDelimiter($this->value);

        $attrExpression = match ($this->operator) {
            '=' => "@{$attrName}={$delim}{$this->value}{$delim}",
            '~=' => "contains(concat(\" \",normalize-space(@{$attrName}),\" \"),{$delim} {$this->value} {$delim})",
            '|=' => "@{$attrName}={$delim}{$this->value}{$delim}"
                        . " or starts-with(@{$attrName},concat({$delim}{$this->value}{$delim},\"-\"))",
            '^=' => "starts-with(@{$attrName}, {$delim}{$this->value}{$delim})",
            '$=' => "substring(@{$attrName},string-length(@{$attrName})"
                        . "-(string-length({$delim}{$this->value}{$delim})-1))={$delim}{$this->value}{$delim}",
            '*=' => "contains(@{$attrName}, {$delim}{$this->value}{$delim})",
            default => "@{$attrName}"
        };

        return "[{$attrExpression}]";
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
