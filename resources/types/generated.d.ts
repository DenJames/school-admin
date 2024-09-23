declare namespace App.Data {
export type GroupData = {
id: number;
userId: number;
teamId: number;
name: string;
users?: any | null;
owner?: App.Data.UserData;
invitations?: any | null;
createdAt: string | null;
updatedAt: string | null;
};
export type GroupInvitationData = {
id: number;
groupId: number;
userId: number;
email: string;
group?: App.Data.GroupData;
user?: App.Data.UserData;
createdAt: string | null;
updatedAt: string | null;
};
export type MessageData = {
id: number;
subject: string;
content: string;
readAt: string | null;
createdAt: string | null;
sender?: App.Data.UserData;
receiver?: App.Data.UserData;
replies?: App.Data.UserData;
};
export type ReplyData = {
id: number;
userId: number;
content: string;
message?: App.Data.MessageData;
user?: App.Data.UserData;
createdAt: string | null;
updatedAt: string | null;
};
export type UserData = {
id: number;
name: string;
email: string;
emailVerifiedAt: string | null;
groups?: any | null;
currentGroupMembership: any | null;
};
}
