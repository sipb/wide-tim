# wide-tim

Verification bot, currently for MIT 2027 server.

On the future, support will be added for any MIT Discord server, and potentially other authentication methods

To self-host, add your token to `token.txt` and a random string of your choosing on `pepper.txt`

## Running the Bot

To run the bot, you'll need to install [Node 16.9](https://nodejs.org/) or higher. Create a [new Discord bot](https://discord.com/developers/docs/getting-started) and enable [intents](https://discord.com/developers/docs/topics/gateway#gateway-intents). 

First, clone this package and install the dependencies.

```
git clone https://github.com/sipb/wide-tim.git
cd wide-tim
npm install
```

Next, add your Discord bot token to `token.txt` and a random string to `pepper.txt`.

```
echo "INSERT_BOT_TOKEN_HERE" >> token.txt
echo "INSERT_RANDOM_STRING_HERE" >> pepper.txt
```

Finally, run the bot.

```
npm start
```
