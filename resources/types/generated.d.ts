declare namespace App.Data {
export type MessageData = {
id: number;
subject: string;
content: string;
readAt: string | null;
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
name: string;
email: string;
emailVerifiedAt: string | null;
};
}
