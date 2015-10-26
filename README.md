# Locustana
Locustana is a fork of [Katana by Hydreon](https://github.com/Hydreon/Katana). Katana is a fork of [PocketMine](https://github.com/PocketMine/PocketMine-MP). For the changes from PocketMine to Katana, please check the [Katana page](https://github.com/Hydreon/Katana).

### Changes from Katana
* Removed all useless console messages
* Removed installer wizard

### Warnings & Intentional Incompatibility
- Using Katana on Windows is not supported.
- Katana does not support leveldb.
- Katana does not use PocketMine's auto-updating or stats tracking systems.
- Katana will only generate empty chunks for regions of the world that are not set.
- Katana's default caching systems do not allow for worlds that are changed dynamically and saved (e.g. survival or player creative build worlds)
- Katana performs reduced physics calculations.
- Katana does not tick mob AIs.
- Katana does not support packet channels.
- Katana forces biome colors to be green. :rainbow:

### Design Philosophy
This server software was created and is maintained by William Teder and Ethan Kuehnel of Hydreon Corporation for the Lifeboat Server Network. We recognize that the functionality that is needed to run large minigame networks differs from that needed to run more vanilla servers. We hope that by removing unused features we can simplify core functions to make them easier to understand and maintain, while reducing overhead to improve performance. This is not software for everyone, this is software for our intended use. Hopefully you find it useful too.
