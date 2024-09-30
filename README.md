# CSS to XPath Converter

## Installation
Run the following command to use the library in your projects.
```
composer require codealfa/css2xpath
```

## Basic Usage

```php
use CodeAlfa\Css2Xpath\Css2XpathConverter;
use CodeAlfa\Css2Xpath\SelectorFactory;

$converter = new Css2XpathConverter(new SelectorFactory());
$xPath = $converter->convert('p#main, div.container');
var_dump($xPath);
```
Output:
```
p[@id="main"]|div[@class and contains(concat(" ", normalize-space(@class), " "), " container ")]
```

## Notes
* The Selector classes are extendable if you need to add or change functionality. You'll just need to create your own 
  `SelectorFactory` or extend the existing one to inject into the `Css2XpathConverter` class.
* Some CSS pseudo-selectors only make sense in the context of a web browser, so only the following pseudo-selectors are
  implemented. All others are ignored:
  * :enabled
  * :disabled
  * :read-only
  * :read-write
  * :checked
  * :required
  * :root
  * :empty
  * :first-child
  * :last-child
  * :only-child
  * :first-of-type
  * :last-of-type
  * :only-of-type
  * :not
  * :has

## License
GPL-3.0 or later