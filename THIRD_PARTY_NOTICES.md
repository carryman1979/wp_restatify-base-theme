# THIRD_PARTY_NOTICES

Status: release-ready documentation baseline.
Last updated: 2026-04-30

## Scope
This file documents third-party code, fonts, and icon packs referenced or bundled in this theme.

## Bundled or Referenced Components

| Component | Path (example) | Claimed/Observed License | Action |
|---|---|---|---|
| Bootstrap | assets/bootstrap/ | MIT (license notice found in bundled JS header) | Keep MIT notice and link |
| Animate.css | assets/animatecss/animate.css | MIT (license header found) | Keep MIT notice and link |
| smooth-scroll | assets/smoothscroll/smooth-scroll.js | MIT (license header found) | Keep MIT notice and link |
| yt-player | assets/ytplayer/index.js | MIT (license header found) | Keep MIT notice and link |
| Embla Carousel | assets/embla/embla.min.js | MIT | Reference: https://github.com/davidjerleke/embla-carousel/blob/master/LICENSE |
| Mobirise Icons 2 | assets/web/assets/mobirise-icons2/ | Included via active Mobirise kit license: All Mobirise Themes & Extensions Kit (Yearly) | Keep internal purchase/account evidence and follow Mobirise terms |
| Icon54 v2 | assets/icon54-v2/ | Included via active Mobirise kit license: All Mobirise Themes & Extensions Kit (Yearly) | Keep internal purchase/account evidence and follow Mobirise terms |
| IconsMind | assets/iconsMind/ | Included via active Mobirise kit license: All Mobirise Themes & Extensions Kit (Yearly) | Keep internal purchase/account evidence and follow Mobirise terms |
| Socicon | assets/socicon/ | No explicit local license header in bundled files; external claim indicates CC BY 4.0 for Mobirise free icons | Keep as Mobirise-bundled asset unless separately re-sourced and independently documented |
| Ubuntu font family (referenced) | style.css, theme.json, assets/mobirise/css/mbr-additional.css | Ubuntu Font Licence 1.0 | Reference: https://fonts.google.com/specimen/Ubuntu/license and https://ubuntu.com/legal/font-licence |
| Science Gothic font family (referenced) | style.css, theme.json, assets/mobirise/css/mbr-additional.css | SIL Open Font License 1.1 | Reference: https://fonts.google.com/specimen/Science+Gothic/license and https://openfontlicense.org/open-font-license-official-text/ |

## Verification Checklist Before Public Switch

- Socicon: if you want to treat it as standalone OSS instead of Mobirise-bundled content, re-source from official upstream and attach independent license evidence.
- For Mobirise/icon packs: keep invoice and EULA outside git; confirm public redistribution rights in writing.
- Verify whether attribution text is required in your distribution channel for Ubuntu/Science Gothic (license URLs already recorded).
- If any commercial asset cannot be redistributed publicly, replace before publishing.

## Mobirise License Evidence (Provided by Maintainer)

- Product: All Mobirise Themes & Extensions Kit (Yearly)
- Vendor: https://mobirise.com/
- Official help reference (license validity): https://mobirise.com/help/how-long-is-my-license-valid-for-425.html
- Official help reference (icons extension): https://mobirise.com/help/the-icons-extension-386.html
- Mobirise free icons page (CC BY 4.0 statement): https://mobiriseicons.com/
- Screenshot evidence on file indicates kit validity through 2027-03-03.

Interpretation note:

- Even if Mobirise internally uses free icon libraries, redistribution in this repository should be treated under the Mobirise package terms unless the original upstream licenses are independently documented and followed.

## Internal Evidence (Not for Public Repo)

Store these privately (not in git):

- Purchase receipts/invoices for Mobirise add-ons.
- License text/EULA snapshots valid at purchase date.
- Vendor clarification emails about redistribution rights.
- Account identifier used for purchase records (keep private, do not publish personal email addresses in this file).

## Maintainer Note

This file is a legal/compliance helper, not legal advice.
