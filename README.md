[![Discord](https://img.shields.io/discord/417478014285381636.svg?label=discord&logo=discord&color=informational)](https://discord.gg/hYE2GAM)
# Zomboid-Server with -Stats-Reporter
a package that grabs information from a running project zomboid server &amp; posts to a database to be displayed on a webpage or with a discord bot.

This package is for people that want to host their own zomboid server with a web front end. The front end is still in progress, but the reporting system that feeds data to it reads project zomboid server files and outputs them to a database every 5 minutes. 

Currently output are:
- Current Date in server
- Days since the apocalypse began(Days Survived)
- Player data-username+character name, & last online time for each player.
- Config data: server name, description, etc.

# Frontend
![](/assets/frontend.png)

This is a simple stand-in front end to display data that was collected.

# Discord
![](/assets/discord.png)

This is a small bot that will report the current date in the server. Currently, you have to create your own bot to use this code. See 
[Setting up a bot.](https://www.digitaltrends.com/gaming/how-to-make-a-discord-bot/) You only need to follow the parts on the discord site to create a bot and get the token, then paste the token into the docker compose file.

# Setup Notes

run chmod 777 -R ./z on the server folder created by docker-compose so that it can be written to by services.

Reporter wont complete successfully until someone has been in game and map_t.bin has been created. 
map_t.bin seems to take some time for the server to create. Might be waiting for the full save time(i think 5 mins). It took about that much time playing the game fresh for it to appear.

# Docker Compose

Here is a quick overview of the services in the compose file:

### Frontend
The frontend is a small ReactJS page that reads from the database and displays the list of players and some game setting data.

### Backend(API)
This is a small nodejs script that reads from the database for the frontend & the discord bot.

### Discord Bot (Optional)
This is a discord bot that can sit in your discord server and respond with the In-Game date of your server whenever someone says "!date". You must provide it with your own Bot Token.

### NGinX
This proxy combines the Frontend & the Backend into one site, where webaddress.com/api accesses the backend and webaddress.com is the frontend.

### Reporter
The reporter is a php script that runs on a timer - roughly every 5 mins - and scrapes data from the Project Zomboid save files to then send to the database. So far the following are read:

### SQL
This is the database that the reporter writes to, and the backend reads from.

### Adminer (Optional)
This is a web interface for people to read the database. You dont need it, but if you want to see what is actually being reported, you can login to this and view the current database.

### Project Zomboid
This is the Zomboid server itself.
