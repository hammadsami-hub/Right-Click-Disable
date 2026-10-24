# Right Click Disable

A lightweight WordPress plugin that disables right-click on your website's front end and shows a short, customizable notification instead.

- **Author:** [Hammad Sami](https://hammadsami.com)
- **License:** GPL v2 or later
- **Requires:** WordPress 5.8+, PHP 7.4+

## Features

- ON/OFF setting under **Settings → Right Click Disable**
- Temporary, centered notification (default: "Right click is disabled!")
- Customizable message text
- 5 designs: Dark, Light, Green, Red, Blue
- Responsive; works with modern themes and Elementor
- Frontend CSS/JS load **only when the feature is ON**
- Plain PHP, vanilla JavaScript and CSS. No libraries.

## Installation

**From a ZIP**

1. Go to **Plugins → Add New Plugin → Upload Plugin**.
2. Choose `right-click-disable.zip` and click **Install Now**.
3. Click **Activate**.

**Manually**

1. Copy the `right-click-disable` folder into `wp-content/plugins/`.
2. Activate it under **Plugins**.

## Usage

1. Go to **Settings → Right Click Disable**.
2. Set the toggle to **ON**.
3. Optionally change the message and design.
4. Click **Save Changes** and test on your site in a private/logged-out window.

Set the toggle to **OFF** to restore normal right-click.

## Notes and limitations

- This only discourages casual copying. It does **not** protect your content: visitors can still use browser menus, keyboard shortcuts, view-source, developer tools, or disable JavaScript.
- Right click is intentionally **not** blocked inside the Elementor editor/preview or the Customizer preview, so you can keep editing.
- On touch devices, a long-press can trigger the same notification.
- Settings are removed from the database when the plugin is deleted.

## File structure

```
right-click-disable/
├── right-click-disable.php
├── assets/
│   ├── css/right-click-disable.css
│   └── js/right-click-disable.js
├── README.md
├── readme.txt
├── LICENSE
└── .gitignore
```

## License

GPL v2 or later. See [LICENSE](LICENSE).
