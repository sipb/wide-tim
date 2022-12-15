import { Client, Events, GuildMember } from 'discord.js';
import { readFileSync } from 'fs';
import { sha256 } from 'js-sha256';

const pepper = readFileSync('pepper.txt', 'utf-8');

const get2027VerifyLink = (id: string) => {
    return `https://discord2027.mit.edu:444/verify.php?id=${id}&auth=${sha256(`${pepper}:${id}`)}`;
}

const setup = (client: Client, config: any) => {
    client.on(Events.GuildMemberAdd, async (member: GuildMember) => {
        member.send(`Hi! I'm Tim. Please click on the following link to get verified as a member of the class of 2027!\n\n${get2027VerifyLink(member.id)}`);
    });
}

module.exports = {
    setup
};