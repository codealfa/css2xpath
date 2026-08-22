# Changelog

All notable changes to this project are documented here.

## [2.0.0] - 2026-08-22

### Added

- Support for complex pseudo-classes, including `:is()`, `:where()`, and nested selector lists.
- Support for `:nth-child()`, `:nth-last-child()`, `:nth-of-type()`, and `:nth-last-of-type()` formulas.
- Separate pseudo-class and pseudo-element selector types.
- Typed selector collections for classes, attributes, pseudo-classes, and selector lists.
- Improved handling of structural and form-state pseudo-classes.

### Changed

- XPath output now consistently starts selector paths with `descendant-or-self::`.
- Selector predicates are composed as a single XPath predicate, improving handling of combined filters and nested selectors.
- The public `SelectorFactoryInterface` now exposes separate pseudo-class and pseudo-element factory methods.
- The `codealfa/regextokenizer` dependency now uses the stable `^2.0` constraint and resolves to `2.1.0`.

### Fixed

- Selector-list parsing and complex pseudo-selector handling.
- PHP 8.5 deprecation warnings from SPL collection usage.
- Return typing and several CSS delivery selector cases.

### Upgrade notes

Version 2.0 changes the generated XPath format and several public selector APIs. Consumers with custom selector factories or code that compares generated XPath strings should review the new output and update their integrations.

## [1.0.0] - Previous release

- Initial stable release.

[2.0.0]: https://github.com/codealfa/css2xpath/releases/tag/2.0
[1.0.0]: https://github.com/codealfa/css2xpath/releases/tag/1.0
