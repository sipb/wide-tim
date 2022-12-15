import { Client, Events, GuildMember } from 'discord.js';
import { readFileSync } from 'fs';
import { sha256 } from 'js-sha256';

const pepper = readFileSync('pepper.txt', 'utf-8');

const get2027VerifyLink = (id: string) => {
    return `https://discord2027.mit.edu:444/verify2027.php?id=${id}&auth=${sha256(`${pepper}:${id}`)}`;
}

const setup = (client: Client, config: any) => {
    client.on(Events.GuildMemberAdd, async (member: GuildMember) => {
        member.send(`Hi! I am Wide Tim, the most massive member of the 2027 Discord. I use my large arms to hold off people who shouldn't be here and to hug people who should, and I have lumbered into your DMs to help with that.`);
        member.send(`In order to prove you are an adMITted member of the Class of 2027, and in need of hugs, please go to 2027discord.mit.edu to verify your account using your email address. The email should come relatively quickly (unless you're using Yahoo, for some reason), so if you don't see it soon, make sure you are using the right email from your MIT application, and check your spam.`);
        member.send(`Once you have verified, the website will ask you to confirm your preferred name. The default will be set based on what you told us in the application, but if your name has changed, and you would like to be introduced differently to your fellow adMITs, you can do so at this stage. We do expect everyone in the server to use a name they might be known as at MIT; it's much better once you come to campus for CPW!`);
        member.send(`Once you have done that, I will then use all my considerable strength to yeet you into the server with the other adMITs where you belong.If, for some reason, you have continued trouble gaining access to the server, send an email to 2027discordadmin@mit.edu for assistance.`);
        member.send(`Please click on the following link to get verified as a member of the class of 2027!\n\n${get2027VerifyLink(member.id)}`);
    });
}

module.exports = {
    setup
};
