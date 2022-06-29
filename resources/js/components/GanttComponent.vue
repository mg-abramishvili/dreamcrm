<template>
    <div ref="gantt"></div>
</template>

<script>
    import {gantt} from 'dhtmlx-gantt'

    export default {
        name: 'gantt',
        props: {
            productions: {
                type: Object,
                default () {
                    return { data: [], links: [] }
                }
            }
        },
        mounted: function () {
            gantt.config.xml_date = "%Y-%m-%d"
            gantt.config.readonly = true

            gantt.config.columns = [
                {
                    name: "name",
                    label: "Производство",
                    align: "left",
                    width: 350,
                },
                // {
                //     name: "start_date",
                //     label: "Дата начала",
                //     align: "center",
                //     width: 75,
                // },
                // {
                //     name: "end_date",
                //     label: "Срок",
                //     align: "center",
                //     width: 75,
                // },
                // {
                //     name: "duration",
                //     label: "Осталось",
                //     align: "center"
                // }
            ]

            gantt.templates.task_text=function(start,end,task){
                return task.name
            }

            gantt.config.start_on_monday = true

            gantt.config.scales = [
                {
                    unit: "month",
                    step: 1,
                    format: "%F, %Y"
                },
                {
                    unit: "day",
                    step: 1,
                    format: "%d",
                }
            ]

            gantt.config.min_column_width = 20

            gantt.i18n.setLocale("ru")
        
            gantt.init(this.$refs.gantt)
            gantt.parse(this.$props.productions)
        },
    }
</script>

<style>
    @import "~dhtmlx-gantt/codebase/dhtmlxgantt.css";
</style>