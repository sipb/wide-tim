import { Client, Events, GatewayIntentBits } from 'discord.js';
import { readFileSync } from 'fs';
import config = require('./config')

const client = new Client({
    intents: [
        // TODO: adding all intents for now
        // remove unnecessary ones
        GatewayIntentBits.Guilds,
		GatewayIntentBits.GuildMessages,
		GatewayIntentBits.MessageContent,
		GatewayIntentBits.GuildMembers,
    ],
})

const modules = ['verification']

client.once(Events.ClientReady, c => {
    for (const module of modules) {
        require('./' + module).setup(client, config)
    }
    console.log(`Ready! Logged in as ${c.user.tag}`);
})

const token = readFileSync('token.txt', 'utf-8')

client.login(token)