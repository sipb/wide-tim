import { Client, Events, GuildMember, Message } from 'discord.js';
import { readFileSync } from 'fs';
import { sha256 } from 'js-sha256';

const pepper = readFileSync('pepper.txt', 'utf-8');

const get2027VerifyLink = (id: string) => {
    return `https://discord2027.mit.edu:444/verify2027.php?id=${id}&auth=${sha256(`${pepper}:${id}`)}`;
}

const setup = (client: Client, config: any) => {
    client.on(Events.GuildMemberAdd, async (member: GuildMember) => {
        try {
            await member.send(`Hi! I am Wide Tim, the most massive member of the 2027 Discord. I use my large arms to hold off people who shouldn't be here and to hug people who should, and I have lumbered into your DMs to help with that.

In order to prove you are an adMITted member of the Class of 2027, and in need of hugs, please go to ${get2027VerifyLink(member.id)} to verify your account using your email address. 

Once you have done that, I will then use all my considerable strength to yeet you into the server with the other adMITs where you belong. If, for some reason, you have continued trouble gaining access to the server, send an email to 2027discordadmin@mit.edu for assistance.`);
        } catch (err) {
            console.log(`Could not send verification DM to ${member.user.tag}: ${err}`);
        }
    });

    client.on(Events.MessageCreate, async (message: Message) => {
        if (message.content == '/verify') {
            try {
                await message.member?.send(`Hi! I am Wide Tim, the most massive member of the 2027 Discord. I use my large arms to hold off people who shouldn't be here and to hug people who should, and I have lumbered into your DMs to help with that.

In order to prove you are an adMITted member of the Class of 2027, and in need of hugs, please go to ${get2027VerifyLink(message.member?.id)} to verify your account using your email address. 

Once you have done that, I will then use all my considerable strength to yeet you into the server with the other adMITs where you belong. If, for some reason, you have continued trouble gaining access to the server, send an email to 2027discordadmin@mit.edu for assistance.`);
            } catch (err) {
                console.log(`Could not send verification DM to ${message.member?.user.tag}: ${err}`);
            }
        }
    });
}

module.exports = {
    setup
};
