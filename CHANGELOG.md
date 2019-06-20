# Changelog

## 4.0.2

20 Jun 2019

- Fix slate_version parameter not passed ([f7c8f062](https://github.com/prezly/slate-php/commit/f7c8f062ecd584200b86a387b3dbe589e1740f64))

## 4.0.1

20 Jun 2019

- Leaf model deprecated ([908a91ba](https://github.com/prezly/slate-php/commit/908a91ba65617c2c1e38a8d929974758fd27cb7e))

## 4.0.0

20 Jun 2019

- Implement versionable serialization ([#24](https://github.com/prezly/slate-php/issues/24))

## 3.0.1

13 Jun 2019

- Drop missed mutable setter `Document::addNode()` ([4c32a883](https://github.com/prezly/slate-php/commit/4c32a8833502283d47abc5dd9862be18a1fc22c8)).

## 3.0.0

7 Jun 2019

- Reorder `Block` and `Inline` constructor arguments ([#21](https://github.com/prezly/slate-php/issues/21))
- Drop mutable setters in favour of their immutable analogs ([#20](https://github.com/prezly/slate-php/issues/20))
- `Leaf::getText()` is now always returning a string ([d1df77f3](https://github.com/prezly/slate-php/commit/d1df77f3b206749b3f6a69a2e48105da3fffb6e9)).

## 2.2.1

7 Jun 2019

- Always drop array keys of child nodes ([1f3aff87](https://github.com/prezly/slate-php/commit/1f3aff87e671da5aea7a5323dc815cf7d53f39f6)).

## 2.2.0

6 Jun 2019

- Implement immutable properties setters, deprecate their mutable analogs ([#22](https://github.com/prezly/slate-php/issues/22))

## 2.1.1

5 Jun 2019

- Implement `Leaf::setMarks()` method (fixed [#17](https://github.com/prezly/slate-php/issues/17))

## 2.1.0

28 Feb 2019

- Add `data` property support to Mark ([#15](https://github.com/prezly/slate-php/pull/15))

## 2.0.0

11 Oct 2018

- Drop `isVoid` property from block and inline nodes serialization format

## 1.0.0 

31 Jan 2018

- Initial implementation

