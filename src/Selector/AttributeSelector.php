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
        $this->value = $value;
        $this->operator = $operator;
        $this->namespace = $namespace;
    }

    public function render(): string
    {
        $attrName = $this->namespace !== null ? "{$this->namespace}:{$this->name}" : "{$this->name}";

        $attrExpression = match ($this->operator) {
            '=' => "@{$attrName}='{$this->value}'",
            '~=' => "contains(concat(' ',normalize-space(@{$attrName}),' '),' {$this->value} ')",
            '|=' => "@{$attrName}='{$this->value}' or starts-with(@{$attrName},concat('{$this->value}','-'))",
            '^=' => "starts-with(@{$attrName}, '{$this->value}')",
            '$=' => "substring(@{$attrName},string-length(@{$attrName})"
                        . "-(string-length('{$this->value}')-1)='{$this->value}'",
            '*=' => "contains(@{$attrName}, '{$this->value}",
            default => "@{$attrName}"
        };

        return "[{$attrExpression}]";
    }
}
