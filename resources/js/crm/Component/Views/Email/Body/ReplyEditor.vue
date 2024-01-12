<template>
    <div>
       <app-input 
            label="reply-editor-input" 
            id="reply-editor" 
            type="text-editor" 
            placeholder="Write your message here..." 
            v-model="message" 
        />
       <button class="btn submit-btn py-2 px-3 rounded shadow" type="submit">{{ $t('send') }}</button>
    </div>
</template>

<script>
export default {
    name: 'reply-editor',
    data() {
        return {
            message: '',
        }
    }
}
</script>

<style lang="scss">
.submit-btn {
    background-color: var(--blue-btn);
    color: #fff;

    &:hover {
        color: #fff;
    }
}
</style>
