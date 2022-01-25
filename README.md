# Zomboid-Server with -Stats-Reporter
a package that grabs information from a running project zomboid server &amp; posts to a database to be displayed on a webpage or with a discord bot.

This package is for people that want to host their own zomboid server with a web front end. The front end is still in progress, but the reporting system that feeds data to it reads project zomboid server files and outputs them to a database every 5 minutes. 

Currently output are:
- Current Date in server
- Days since the apocalypse began(Days Survived)
- Player data-username+character name, & last online time for each player.

