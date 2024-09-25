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
        isGroupAdmin: string;
        groups?: any | null;
    };
}
