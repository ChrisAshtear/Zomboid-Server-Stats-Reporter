require('dotenv').config(); //initialize dotenv
const Discord = require('discord.js'); //import discord.js
const axios = require('axios');
const client = new Discord.Client({ intents: ["GUILDS", "GUILD_MESSAGES"] }); //create new client

client.on('ready', () => {
  console.log(`Logged in as ${client.user.tag}!`);
});

client.login(process.env.CLIENT_TOKEN); //login bot using token
client.on('message', msg => {
  if (msg.content === '!date') {
	
  axios.get(process.env.API_SITE)
  .then((response) => { msg.reply("Game Date is: "+(response.data[0].month)+"/"+(response.data[0].dayofmonth)+". The Apocalypse began "+response.data[0].daysSinceStart+" days ago.");})

  }
});