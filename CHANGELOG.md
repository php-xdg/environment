# Changelog

## [2.0](https://github.com/php-xdg/environment/compare/1.0...2.0)

* `EnvSuperGlobalProvider`, `ServerSuperGlobalProvider` and `ArrayProvider`
  now accept a constructor argument to opt-out of coalescing empty strings to `null`.
* `EnvSuperGlobalProvider`, `ServerSuperGlobalProvider` and `ArrayProvider`
  now coalesce `false` to `"0"` instead of `null`.
* `ServerSuperGlobalProvider` now ignores keys starting with `HTTP_` by default.
* The provider returned by `XdgEnvironment::default()` now prioritizes `$_ENV` over `$_SERVER`.
