declare namespace App.Data {
export type AbsenceData = {
id: number;
reason: string;
excused: boolean;
approvedAt: string;
user?: App.Data.UserData;
lesson?: App.Data.LessonData;
teacher?: App.Data.TeacherData;
permissions: { update: boolean;delete: boolean } };
export type CityData = {
id: number;
name: string;
zipCode: string;
country?: App.Data.CountryData;
permissions: { update: boolean;delete: boolean } };
export type ClassCategoryData = {
id: number;
name: string;
permissions: { update: boolean;delete: boolean } };
export type ClassroomData = {
id: number;
name: string;
school?: App.Data.SchoolData;
permissions: { update: boolean;delete: boolean } };
export type ClassroomReservationData = {
id: number;
booked_from: string;
booked_to: string;
classroom?: App.Data.ClassroomData;
permissions: { update: boolean;delete: boolean } };
export type CountryData = {
id: number;
name: string;
iso: string;
cities?: App.Data.CityData;
permissions: { update: boolean;delete: boolean } };
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
permissions: { update: boolean;delete: boolean } };
export type GroupInvitationData = {
id: number;
groupId: number;
userId: number;
email: string;
group?: App.Data.GroupData;
user?: App.Data.UserData;
createdAt: string | null;
updatedAt: string | null;
permissions: { update: boolean;delete: boolean } };
export type HomeworkData = {
id: number;
name: string;
description: string;
due_date: string;
lesson?: App.Data.LessonData;
permissions: { update: boolean;delete: boolean } };
export type LessonData = {
id: number;
name: string;
description: string | null;
duration: number;
startsAt: string;
team?: App.Data.TeamData;
teacher?: App.Data.TeacherData;
classCategory?: App.Data.ClassCategoryData;
classroomReservation?: App.Data.ClassroomReservationData;
homeworks?: App.Data.HomeworkData;
absences?: App.Data.AbsenceData;
permissions: { update: boolean;delete: boolean } };
export type MessageData = {
id: number;
subject: string;
content: string;
readAt: string | null;
createdAt: string | null;
sender?: App.Data.UserData;
receiver?: App.Data.UserData;
replies?: App.Data.UserData;
permissions: { update: boolean;delete: boolean } };
export type ReplyData = {
id: number;
userId: number;
content: string;
message?: App.Data.MessageData;
user?: App.Data.UserData;
createdAt: string | null;
updatedAt: string | null;
permissions: { update: boolean;delete: boolean } };
export type SchoolData = {
id: number;
name: string;
location?: App.Data.SchoolLocationData;
permissions: { update: boolean;delete: boolean } };
export type SchoolLocationData = {
id: number;
name: string;
city?: App.Data.CityData;
schools?: App.Data.SchoolData;
permissions: { update: boolean;delete: boolean } };
export type TeacherData = {
id: number;
school?: App.Data.SchoolData;
user?: App.Data.UserData;
permissions: { update: boolean;delete: boolean } };
export type TeamData = {
id: number;
name: string;
owner?: App.Data.UserData;
school?: App.Data.SchoolData;
permissions: { update: boolean;delete: boolean } };
export type UserData = {
id: number;
name: string;
email: string;
emailVerifiedAt: string | null;
isGroupAdmin: string;
profilePhotoUrl: string | null;
groups?: any | null;
permissions: { update: boolean;delete: boolean } };
}
