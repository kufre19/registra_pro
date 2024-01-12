<template>
    <div>
        <template v-if="taskProgressShow && totalTask > 0 ">
            <label>{{$t('task_progress','Task progress')}}</label>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" :aria-valuenow="`${percentileOfTask}%`" :style="`width:${percentileOfTask}%`" aria-valuemin="0" aria-valuemax="100">
                {{checkTask > 0 ? `${checkTask}/${totalTask}`: '' }}
                </div>
            </div>
            <hr/>
        </template>
        <viewer
            ref="toastUiViewer"
            :initial-value="value"
            :height="`${height}px`"
            :theme="theme"
        />
    </div>
</template>

<script>
import '@toast-ui/editor/dist/toastui-editor-viewer.css';
import '@toast-ui/editor/dist/theme/toastui-editor-dark.css';
import {Viewer} from '@toast-ui/vue-editor';

export default {
    name: "MarkdownViewer",
    components: {
        viewer: Viewer
    },
    props: {
        value: {},
        height: 300,
        taskProgressShow:{
            type: Boolean,
            default: false
        }
    },
    watch: {
        value: {
            handler: function (newValue) {
                this.$refs.toastUiViewer.invoke('setMarkdown', newValue);
                this.updateTaskProgess();
            },
        }
    },
    data() {
        return {
            editorOptions: {
                hideModeSwitch: true,
            },
            theme:document.documentElement.getAttribute('theme'),
            checkTask:0,
            uncheckTask:0,
        }
    },
    created() {
        this.updateTaskProgess();
    },
    mounted() {
        setTimeout(()=> {
            let theme = localStorage.getItem('theme');
            if(theme==='dark'){
                $('.toastui-editor-contents').parent().addClass('toastui-editor-dark');
            } else {
                $('.toastui-editor-contents').parent().removeClass('toastui-editor-dark');
            }
        })
    },
    computed: {
        totalTask(){
            return this.checkTask + this.uncheckTask;
        },
        percentileOfTask(){
            return ((this.checkTask)/(this.totalTask))*100;
        }
    },
    methods: {
        updateTaskProgess(){
            let check = /\[x\]/g;
            this.checkTask=((this.value || '').match(check) || []).length;
            let uncheck = /\[ \]/g;
            this.uncheckTask = ((this.value || '').match(uncheck) || []).length;
        }
    },
}
</script>
<style lang="scss">
//.toastui-editor-contents p {
//    color: var(--default-font-color) !important;
//}
// .toastui-editor-contents pre {
//     color: var(--default-font-color) !important;
// }
// .toastui-editor-contents blockquote {
//     color: var(--default-font-color) !important;
// }
</style>
