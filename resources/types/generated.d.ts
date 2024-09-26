declare namespace App.Data {
export type AbsenceData = {
id: number;
reason: string;
excused: boolean;
approvedAt: string | null;
user?: App.Data.UserData;
lesson?: App.Data.LessonData;
teacher?: App.Data.TeacherData;
};
export type CityData = {
id: number;
name: string;
zipCode: string;
country?: App.Data.CountryData;
};
export type ClassCategoryData = {
id: number;
name: string;
};
export type ClassroomData = {
id: number;
name: string;
school?: App.Data.SchoolData;
};
export type ClassroomReservationData = {
id: number;
booked_from: string;
booked_to: string;
classroom?: App.Data.ClassroomData;
};
export type CountryData = {
id: number;
name: string;
iso: string;
cities?: App.Data.CityData;
};
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
export type HomeworkData = {
id: number;
lesson?: App.Data.LessonData;
name: string;
description: string;
dueDate: string;
dueDateForHumans: string;
isSubmitted: boolean;
};
export type HomeworkSubmissionData = {
id: number;
userId?: App.Data.UserData;
homeworkId?: App.Data.HomeworkData;
content: string | null;
feedback: string | null;
};
export type LessonData = {
id: number;
name: string;
description: string | null;
duration: number;
start: string;
end: string;
team?: App.Data.TeamData;
teacher?: App.Data.TeacherData;
classCategory?: App.Data.ClassCategoryData;
classroomReservation?: App.Data.ClassroomReservationData;
homeworks?: App.Data.HomeworkData;
absences: any;
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
export type SchoolData = {
id: number;
name: string;
location?: App.Data.SchoolLocationData;
};
export type SchoolLocationData = {
id: number;
name: string;
city?: App.Data.CityData;
schools?: App.Data.SchoolData;
};
export type TeacherData = {
id: number;
school?: App.Data.SchoolData;
user?: App.Data.UserData;
};
export type TeamData = {
id: number;
name: string;
owner?: App.Data.UserData;
school?: App.Data.SchoolData;
};
export type UserData = {
id: number;
name: string;
email: string;
emailVerifiedAt: string | null;
isGroupAdmin: string;
profilePhotoUrl: string | null;
groups?: any | null;
};
}
