declare namespace App.Data {
export type HomeworkData = {
id: number;
lesson?: App.Data.LessonData;
name: string;
description: string;
dueDate: string;
route: string;
dueDateForHumans: string;
};
export type LessonData = {
id: number;
teamId: number;
classCategoryId: number;
teacherId: number;
name: string;
duration: number;
startsAt: string;
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
};
}
