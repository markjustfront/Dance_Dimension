The Dance Dimension project is based on the popular game Step Mania, which itself was based on Dance Revolution, with the difference that SM is open source. This first project follows the guidelines provided by the Step Mania project provided by our Educators. 

In this project we accomplished and structured a working or semiworking game based on SM. It has its Main Menu alongside a Comment Section a Scoreboard Section and then the redirect buttons under the "Main Menu" text that can lead to setting an upload page or song list (That is connected to the game).

**Web Structure**
_Upon Loading the page_
```bash
├── Splash Page
│  ├── Menu
│  ├── Song List
│  │    ├── Play Song
│  │    ├── Preview
│  │    ├── Edit Song
│  │    └── Delete Song
│  ├── Upload
│  └── Settings
├── Comments
├── Leaderboard
```

**First Level**
    . Splash Page: The glowing logo with small animation that redirects to the main menu, is just to emphasize the style of the web. The same logo can be found as the web's icon.

**Second Level**
. Menu: The Menu, along with Comments and Leaderboard, share the same level because they are set on the same page but achieve different porpoise, the Menu redirects to game-related pages.

. Comments: Displays comments that the user can write and will be saved inside a JSON file.

. Leaderboard: Display the maximum points that a user has achieved on a song.

**Third Level**
. Song List: Displays the list of available songs with all their data and some buttons to be redirected to related pages for that specific song such as Edit or the Game.

. Upload: This is where you will be able to upload a desired song. It has the following: Name of the Song, Artist, Song Image, Audio file, Description and Game File.

. Setting: Basic settings for the web, enable sound, input type and such.

**Fourth Level**
. Play Song: This redirects to the game, and subsequently,  you can start playing the level of whichever song button you have clicked.

. Preview: When Sound is enabled, if you click this button, a preview of the song will play indecently. To stop it, click it again. 

. Edit Song: This is the same page as the Upload page, but with all the fields prefilled for the song that you want to edit.

. Delete Song: Just a button and a disclaimer that once you delete a song, it will not come back.

**PHP**
. 

**JS**
. 