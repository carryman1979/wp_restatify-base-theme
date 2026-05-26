# Restatify Base Theme 1.0.15

## What's new

- Theme shared loader now prioritizes local root shared (`wp_restatify-shared/src/*`) for development environments.
- If root shared is unavailable, it loads only the required shared version from `wp-content/plugins/wp_restatify-shared/versions/<x.y.z>/` (or MU-plugins).
- Theme version was bumped to `1.0.15` in `style.css` and `package.json`.
- Copilot repo guidance was updated with the shared loader order policy.

## Compatibility

- Theme version: `1.0.15`
- No intended breaking changes.

## Artifact

- `wp_restatify-base-theme-1.0.15.zip`
