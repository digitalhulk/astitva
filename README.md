# Astitva

![Astitva theme](astitva/screenshot.png)

**[Open the live demo](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/digitalhulk/astitva/main/demo/live-a820ee8a.json)**

The demo runs a complete WordPress site with Astitva in your own browser, free, with no sign-up. The first load takes 1 to 2 minutes while it installs Elementor and the demo pages. Nothing you change there is saved, and it does not affect any real site. To see the admin area, open `/wp-admin/` in the demo.

**[Download the latest version](https://github.com/digitalhulk/astitva/releases/latest)**

## Install and Setup

Two packages make up the site. Both are in the `dist` folder.

| File | What it does |
| --- | --- |
| `astitva.zip` | The theme: design, header, footer, Theme Options |
| `astitva-core.zip` | The plugin: 24 Elementor widgets, post types, demo import |

## Install

1. Use a WordPress site on PHP 8.3 or newer. The theme and plugin run on 8.1, but the current Contact Form 7 needs 8.3.
2. Go to Plugins > Add New. Install and activate **Elementor** and **Contact Form 7**.
3. Go to Plugins > Add New > Upload Plugin. Upload `astitva-core.zip` and activate it.
4. Go to Appearance > Themes > Add New > Upload Theme. Upload `astitva.zip` and activate it.
5. Go to Tools > Import Astitva Demo and click **Import demo content**.

The import creates all pages, menus, sample content and the contact form. It changes your front page and overwrites pages with the same address, so run it on a new site.

## Make it yours

- **Logo, colors, fonts, social links, cookie text, 404 page, custom CSS and JS:** Appearance > Theme Options.
- **Page content:** open any page and click Edit with Elementor. The widgets are in the Astitva category.
- **Services, projects, team and jobs:** use their own menus in the WordPress admin.
- **Blur on the header:** Theme Options > Header and Footer. It is off by default.

## Replace before going live

- All sample text, client quotes, prices and statistics.
- Placeholder images and the hero video. Change the hero video on the Home page: select the top container, then Style > Background > Video.
- The Privacy, Terms and Cookie pages. They contain sample wording and need review for your business.
- The office address, phone number and email address.
- The contact form recipient, under Contact > Contact Forms > Mail.

## If styling looks wrong after a second import

Run the demo import once. If you run it again and a page looks unstyled, go to Elementor > Tools and click **Regenerate Files & Data**.

## License keys

Each live site needs a key, made on your own computer:

```
cd C:\Users\dell2\agency-island\tools
node astitva-license.js issue yourdomain.com "Your Company"
```

Paste the key into Settings > Astitva License on that site. A key works on that domain, its `www.` version and its subdomains. Use `"*"` instead of a domain for a key that works anywhere. Local sites (localhost, 127.0.0.1, `.test`, `.local`) need no key.

The signing key is `C:\Users\dell2\astitva-license\private.pem`. Anyone with it can make keys, so never upload it or share it. If it is lost, old keys keep working but you cannot make new ones without changing the public key in `astitva-core\includes\license.php`.

The theme code itself is GPL, like all WordPress themes. The key controls who gets automatic updates; it does not stop someone from copying the files.

## Releasing an update

1. Make your changes in `astitva` and `astitva-core`.
2. In PowerShell: `cd C:\Users\dell2\agency-island\tools` then `.\release.ps1 -Version 1.0.1 -Notes "What changed"`.
3. Publish it. In Claude Code, run these one at a time (change 1.0.1 to your version):

```
! cd /c/Users/dell2/agency-island && cp dist/1.0.1/update.json update.json && git add -A && git commit -m "Release 1.0.1" && git push
! cd /c/Users/dell2/agency-island && "/c/Program Files/GitHub CLI/gh.exe" release create v1.0.1 dist/1.0.1/astitva.zip dist/1.0.1/astitva-core.zip dist/1.0.1/update.json --repo digitalhulk/astitva --title "Astitva 1.0.1" --notes "What changed"
```

Run the release command first if you want sites to see the zips before the version number changes; either order works within a few minutes.

Licensed sites check about every 12 hours and show the update in Dashboard > Updates. Settings > Astitva License has a **Check for updates now** button.

By default sites read `https://raw.githubusercontent.com/digitalhulk/astitva/main/update.json`, and the zips sit in that repo's GitHub releases. Both are free, but the repo must be public, so the code is visible. To use your own hosting instead, pass `-BaseUrl` to `release.ps1` and put the `update.json` link into Settings > Astitva License > Update server.

## Testing email

Contact Form 7 sends through your host. Send a test message after going live. If it does not arrive, install an SMTP plugin.

## Licenses

Theme and plugin code, images and video were created for this project. Bricolage Grotesque and Inter come from Google Fonts under the SIL Open Font License. Icons in Elementor widgets use Font Awesome Free.
