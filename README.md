# Dance Dimension

A browser rhythm game inspired by Dance Dance Revolution and StepMania. This copy is a static PHP + JavaScript site that works in a subdirectory (for example `/dance-dimension/`) or at a domain root.

## Play

Open `index.php` or `Splash.html`. The splash logo (or the Skip button) leads to the menu. From there you can play a song, manage the library, or view the leaderboard.

- Keyboard: arrow keys.
- Touch or mouse: tap the four pads on the play field.
- Audio starts with the chart; if the browser blocks autoplay, use **Tap to start audio**.

The four bundled tracks are original short demo WAV loops with placeholder art and generated charts. They are not commercial recordings.

## Layout

```
├── Splash.html          Entry animation and skip link
├── Menu/Menu.php        Main menu
├── Game/Game.php        Song select and play
│   └── Song_Level/      Play field, scoring, results
├── Song_Select/         Library list, upload, edit, delete
├── Leaderboard/         Scores by song
├── 1-Song_Data/         Charts, demo audio, art, JSON
└── 2-Web_Assets/        Styles, font, UI art
```

## Local development

```bash
php -S localhost:8777
```

Then visit `http://localhost:8777/`.

## Library writes

Upload, edit, and delete accept WAV, OGG, or MP3 audio (max 8 MB), JPEG/PNG/WebP art (max 3 MB), and `.txt` charts. Delete asks for confirmation and keeps score keys tied to song IDs.

To require a token before those writes, copy `config.example.php` to `config.php` (not committed) and set `write_token`. Unlock at `unlock.php`.

The JSON files and `1-Song_Data/` folders must be writable by the PHP process.

## History note

Older git commits still contain commercial audio and cover art that have been removed from the tree. Replacing that history would need a rewrite and an explicit decision.
