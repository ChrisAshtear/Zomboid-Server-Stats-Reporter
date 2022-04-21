# Zomboid-Server with -Stats-Reporter
a package that grabs information from a running project zomboid server &amp; posts to a database to be displayed on a webpage or with a discord bot.

This package is for people that want to host their own zomboid server with a web front end. The front end is still in progress, but the reporting system that feeds data to it reads project zomboid server files and outputs them to a database every 5 minutes. 

Currently output are:
- Current Date in server
- Days since the apocalypse began(Days Survived)
- Player data-username+character name, & last online time for each player.

# Frontend
![](/assets/frontend.png)

This is a simple stand-in front end to display data that was collected.

# Discord
![](/assets/discord.png)

This is a small bot that will report the current date in the server. Currently, you have to create your own bot to use this code. 

# Setup Notes

run chmod 777 -R ./z on the server folder created by docker-compose so that it can be written to by services.

Reporter wont complete successfully until someone has been in game and map_t.bin has been created.
