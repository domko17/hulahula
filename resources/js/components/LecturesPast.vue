<template>

    <div v-if="loading" class="loading">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>
    <p>KLOP</p>
<!--    <table class="table table-striped sortable-table" id="lectures_past_pc">-->
<!--        <thead>-->
<!--        <tr>-->
<!--            <th></th>-->
<!--            <th> @lang('general.Student') </th>-->
<!--            <th> @lang('general.Teacher') </th>-->
<!--            <th> @lang('general.Date') </th>-->
<!--            <th> @lang('lecture.start') </th>-->
<!--            <th> @lang('general.package') </th>-->
<!--            <th> @lang('general.actions') </th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        <tr v-for="lecture in lecturesPastData" :key="lecture.id" >-->
<!--            <td class="py-1" style="font-size: 1.5em">-->

<!--            </td>-->
<!--            <td>-->
<!--&lt;!&ndash;                @if($l->canceled)&ndash;&gt;-->
<!--&lt;!&ndash;                !ZRUŠENÁ!&ndash;&gt;-->
<!--&lt;!&ndash;                @endif&ndash;&gt;-->
<!--&lt;!&ndash;                @if(count($l->students) == 1)&ndash;&gt;-->
<!--&lt;!&ndash;                {{ $l->students[0]->user->name }}&ndash;&gt;-->
<!--&lt;!&ndash;                @else&ndash;&gt;-->
<!--&lt;!&ndash;                @foreach($l->students as $s)&ndash;&gt;-->
<!--&lt;!&ndash;                {{ $s->user->profile->last_name.", " }}&ndash;&gt;-->
<!--&lt;!&ndash;                @endforeach&ndash;&gt;-->
<!--&lt;!&ndash;                @endif&ndash;&gt;-->
<!--            </td>-->
<!--            <td>-->
<!--                <a href="{{ route('user.profile', lecture.hour.teacher.id) }}" class="text-primary">-->
<!--                    {{ lecture.hour.teacher.profile.first_name }} {{ lecture.hour.teacher.profile.last_name }}-->
<!--                </a>-->
<!--            </td>-->
<!--            <td>-->
<!--                <b>{{ lecture.class_date }}</b>-->
<!--            </td>-->
<!--            <td>{{ substr(lecture.hour.class_start, 0, 5) }}-->
<!--                - {{ substr(lecture.hour.class_end, 0, 5) }}</td>-->
<!--            <td>asa</td>-->
<!--            <td>-->
<!--&lt;!&ndash;                <button&ndash;&gt;-->
<!--&lt;!&ndash;                    onclick="window.location.href='{{ route('lectures.show', lecture.id) }}'"&ndash;&gt;-->
<!--&lt;!&ndash;                    class="btn btn-inverse-primary btn-sm pull-right"><i&ndash;&gt;-->
<!--&lt;!&ndash;                    class="fa fa-search"></i> @lang('general.detail')</button>&ndash;&gt;-->
<!--            </td>-->
<!--        </tr>-->
<!--        </tbody>-->
<!--    </table>-->
</template>

<script>
import axios from 'axios'

export default {

    data() {
        return {
            lecturesPastData: {},
            loading: false,
            error: null
        }
    },

    mounted() {
        this.getResults();
    },

    methods: {
        getResults() {

            const token = Buffer.from(`dominkhorvath138@gmail.com:somar123`, 'utf8').toString('base64')
            this.loading = true
            axios.post('http://localhost/api/getLectures', { "type": "past" }, {
                headers: {
                    'Content-Type': 'application/json',
                    "Access-Control-Allow-Origin": "*",
                    'Authorization': `Basic ${token}`
                }
            }).then(({ data }) => {
                this.lecturesPastData = data
                this.loading = false
            })
            .catch((err) => {
                this.error = err
                this.loading = false
            });
        }
    }
}
</script>

<style scoped>

</style>
