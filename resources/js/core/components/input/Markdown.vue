<template>
  <editor
    ref="toastUiEditor"
    :initial-value="initialValue"
    :options="editorOptions"
    :height="`${data.height}px`"
    preview-style="tab"
    initialEditType="wysiwyg"
    @change="onEditorChange"
  />
</template>

<script>
import "@toast-ui/editor/dist/toastui-editor.css";
import "@toast-ui/editor/dist/theme/toastui-editor-dark.css";
import { Editor } from "@toast-ui/vue-editor";
import { InputMixin } from "./mixin/InputMixin";

export default {
  name: "Markdown",
  mixins: [InputMixin],
  components: {
    editor: Editor,
  },
  data() {
    return {
      editorOptions: {
        // hideModeSwitch: true,
        autofocus: false,
        toolbarItems: [
          ["heading", "bold", "italic", "strike"],
          [
            "hr",
            "quote",
            "ul",
            "ol",
            "task",
            "link",
            "code",
            "codeblock",
            // 'table',
            // 'image',
          ],
        ],
        theme: document.documentElement.getAttribute("theme"),
      },
      initialValue: this.value,
    };
  },
  methods: {
    onEditorChange() {
      let markdown = this.$refs.toastUiEditor.invoke("getMarkdown");
      this.$emit("input", markdown);
    },
    resetEditor(data) {
      this.$refs.toastUiEditor.invoke("reset");
      console.log(data);
    },
  },
  mounted() {
    this.$hub.$on("reset-markdown-editor", (data) => this.resetEditor(data));
    setTimeout(()=> {
        let theme = localStorage.getItem('theme');
        if(theme==='dark'){
            $('.toastui-editor-defaultUI').addClass('toastui-editor-dark');
        } else {
            $('.toastui-editor-defaultUI').removeClass('toastui-editor-dark');
        }
    })
  },
};
</script>

<style lang="scss">
.toastui-editor-dark .toastui-editor-md-container,
.toastui-editor-dark .toastui-editor-ww-container {
  background-color: var(--form-control-bg) !important;
}

.toastui-editor-main-container {
  color: var(--default-font-color) !important;
}

.toastui-editor-dark .ProseMirror,
.toastui-editor-dark .toastui-editor-contents p,
.toastui-editor-dark .toastui-editor-contents h1,
.toastui-editor-dark .toastui-editor-contents h2,
.toastui-editor-dark .toastui-editor-contents h3,
.toastui-editor-dark .toastui-editor-contents h4,
.toastui-editor-dark .toastui-editor-contents h5,
.toastui-editor-dark .toastui-editor-contents h6 {
  color: var(--default-font-color) !important;
}
</style>
