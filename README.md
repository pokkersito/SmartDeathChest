## About

**SmartDeathChest** is a UHC-style plugin for **PocketMine-MP 5** that creates a smart double chest at the location where a player dies. It securely stores all dropped items and armor in an organized layout, protected by a floating hologram displaying a live countdown.

When the timer runs out, the chest disappears with sound and visual effects, and any remaining items are dropped on the ground.

---

## How Does It Work?

When a player dies:
- A **double chest** is spawned at the death location.
- The player’s inventory and armor are automatically saved inside the chest.
- A **floating hologram** appears above the chest showing:
  - The player's name
  - A real-time countdown (in seconds)
- When the countdown ends:
  - The chest is removed
  - Any leftover items are dropped naturally
  - An **explosion sound and particle** effect is played

---

## Features

### 🔧 config.yml:
- `time`: Duration in seconds before the chest disappears (default: `60`)

### ✨ Hologram:
- Displays the player’s name and time remaining
- Updates every second

### 💼 Chest:
- Automatically stores all dropped items and armor
- Armor is placed in specific visual slots (helmet, chestplate, leggings, boots)

### 💥 Effects:
- At the end of the timer:
  - **Explosion sound**
  - **Explosion particle**
  - **Floating text removal**
  - **Remaining items are dropped**

---

## 👤 Author

- **Pokker77**:
  - 📧 **Discord**: [NeptuneMCPE](https://discord.gg/neptunemcpe)
  - 📹 **YouTube**: [@Pokkersito_77](https://youtube.com/@Pokkersito_77)

---

## 🎬 Demo Video

You can view a short demonstration of the plugin in the following video:

https://imgur.com/a/ruiztp6
