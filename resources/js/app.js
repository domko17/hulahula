import { createApp } from 'vue';

import LecturesPast from "./components/LecturesPast.vue";
import LecturesFuture from "./components/LecturesFuture.vue";

createApp(LecturesPast).mount('#lecture_past')
createApp(LecturesFuture).mount('#lecture_future')


