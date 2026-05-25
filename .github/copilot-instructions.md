# Copilot Instructions for wp_restatify-base-theme

Shared baseline:
- https://github.com/carryman1979/wp_restatify-shared/blob/main/docs/ai/copilot-instructions.shared.md

Repo-specific requirements:
- Do not break block save/edit data shape compatibility.
- Keep link handling and trigger URL plumbing stable.
- Keep shared loader order stable: local root shared first for dev, otherwise exact versioned shared under plugins/mu-plugins, never mixed in one request.

Required checks:
- npm run test:unit:js
- If block behavior changes, verify editor and frontend output parity.
