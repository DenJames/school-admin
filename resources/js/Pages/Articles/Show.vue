<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Card.vue";
import ArticleData = App.Data.ArticleData;

interface Props {
    article: ArticleData;
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ article.title }}</h2>
        </template>

        <div class="col-span-12">
            <Card>
                <template #header>{{ article.title }}</template>

                <article class="p-2">
                    {{ article.content }}

                    <div class="mt-4">
                        <div class="text-xs text-white/50">
                            Sendt til:
                            <span v-if="article.isGlobal"> Alle</span>
                            <span v-if="article.school && !article.isGlobal"> {{ article.school.name }}</span>
                            <span v-if="article.team && !article.school && !article.isGlobal">
                                {{ article.team.name }}</span
                            >
                            <br />
                            <span v-if="article.user">Sendt af: </span>
                            <span v-if="article.isGlobal"> Sendt af: Administrator</span>
                            <span v-if="article.user && !article.isGlobal"> {{ article.user.name }}</span>
                        </div>
                    </div>
                </article>
            </Card>
        </div>
    </AppLayout>
</template>
