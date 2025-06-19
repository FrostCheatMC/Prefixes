
---

<p align="center">
  <img src="https://img.shields.io/badge/plugin-Prefixes-blueviolet?style=for-the-badge">
  <br><br>
  <a href="https://paypal.me/FrostCheatMC?country.x=CO&locale.x=es_XC">
    <img src="https://img.shields.io/badge/donate-paypal-ff69b4?style=for-the-badge&logo=paypal">
  </a>
  <a href="https://discord.gg/k8X7CG2kFv">
    <img src="https://img.shields.io/discord/1384337463971020911?style=for-the-badge&logo=discord&logoColor=white">
  </a>
  <a href="https://poggit.pmmp.io/ci/FrostCheatMC/Prefixes/Prefixes">
    <img src="https://poggit.pmmp.io/ci.shield/FrostCheatMC/Prefixes/Prefixes?style=for-the-badge">
  </a>
  <a href="https://poggit.pmmp.io/p/Prefixes">
    <img src="https://poggit.pmmp.io/shield.downloads/Prefixes?style=for-the-badge">
  </a>
</p>

<h1 align="center">✨ Prefixes</h1>
<p align="center">Customize and manage player prefixes with ease. Supports permissions, sessions, RankSystem integration, GUI menus, and more!</p>

---

## 💡 Features

- ✅ Prefixes with color & formatting support
- 🛡️ Permission-based access to prefixes
- 🧠 Player session management
- 📦 Full GUI interface with clickable prefix selection
- 🧩 Integration with [RankSystem](https://github.com/Falkirks/RankSystem) for chat placeholders
- 🌐 Multi-language support (EN, ES, FR, PT, etc.)
- 🔁 Async saving for better performance
- ⚡ Lightweight and optimized for PocketMine-MP

---

## 🛠️ Installation

1. 📥 [Download Prefixes from Poggit](https://poggit.pmmp.io/p/Prefixes)
2. 🔌 Drag the `.phar` file into your server's `/plugins/` folder
3. 🚀 Restart your server
4. ✅ Use `/prefixes` in-game to open the menu

---

## 🌍 Supported Languages

Change the language via the config file.

Available languages:
- 🇺🇸 English (`en_us`)
- 🇪🇸 Español (`es_es`)
- 🇫🇷 Français (`fr_fr`)
- 🇵🇹 Português (`pr_br`)
- 🇩🇪 Deutsch (`gr_gr`)
- 🇷🇺 Русский (`rs_rs`)

Help us translate more in [`/resources/languages/`](resources/languages/)

---

## 📚 Commands

| Command                                       | Description                                            | Permission                     |
| --------------------------------------------- | ------------------------------------------------------ | ------------------------------ |
| `/prefixes`                                   | Opens the prefix GUI menu                              | `prefixes.command`             |
| `/prefix set <player> <prefix>`               | Assigns a prefix to a player manually                  | `prefixes.command.set`         |
| `/prefix remove <player>`                     | Removes the prefix from a player                       | `prefixes.command.remove`      |
| `/prefix delete <prefix>`                     | Deletes a prefix completely                            | `prefixes.command.delete`      |
| `/prefix create <name> <format> <permission>` | Creates a new prefix with name, format, and permission | `prefixes.command.create`      |
| `/prefix save`                                | Saves all prefix and session data manually             | `prefixes.command.save`        |
| `/prefix reload`                              | Reloads config, prefixes, sessions, and language files | `prefixes.command.reload`      |
| `/prefix setlanguage <language>`              | Sets the plugin's default language                     | `prefixes.command.setlanguage` |
| `/prefix help`                                | Shows the list of available commands                   | `prefixes.command.help`        |

> 💡 You can also use `/prefixes` as the main command to open the GUI.

---

## 🔌 RankSystem Integration

If [RankSystem](https://poggit.pmmp.io/p/RankSystem/) is installed and enabled in config, this plugin automatically registers a chat tag (e.g., `{prefix}`) to display the user's selected prefix in chat.

Enable via config:

```yaml
rank-system-chat: true
rank-system-prefix-placeholder: "prefix"
````

---

## 🔄 Saving System

This plugin uses asynchronous tasks to save player sessions and prefixes without causing lag. All session and prefix data is stored in `plugin_data/Prefixes/`:

* `prefixes/` → each prefix config
* `sessions/` → each player's selected prefix
* `languages/` → translation files

---

## 📁 File Structure

```
plugin_data/
└── Prefixes/
    ├── prefixes/
    │   └── <prefix>.yml
    ├── sessions/
    │   └── <uuid>.yml
    ├── languages/
    │   ├── en_us.yml
    │   └── es_es.yml
    └── config.yml
```

---

## 📖 License

This plugin is open source and licensed under the [MIT License](LICENSE).
Feel free to fork, contribute, or open issues and pull requests.

---

## ☕ Support the Developer

If you find this project useful, consider supporting me:

> 💖 [Donate via PayPal](https://paypal.me/FrostCheatMC?country.x=CO&locale.x=es_XC)

Any help is appreciated and motivates future updates!

---

<p align="center"><b>Made with ❤️ by FrostCheatMC</b></p>

---