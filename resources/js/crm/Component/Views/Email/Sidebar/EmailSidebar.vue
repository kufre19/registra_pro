<template>
    <div class="email-sidebar rounded shadow">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-12 col-lg-6 col-xl-12">
                <a href="/email/compose" class="btn compose-btn w-100 py-2 rounded mb-primary">
                    <app-icon name="mail"/>

                    {{ $t('compose_mail') }}
                </a>

               <inbox-nav /> 
            </div>

            <div class="col-12 col-sm-6 col-md-12 col-lg-6 col-xl-12">
               <inbox-labels /> 
               <inbox-chat /> 
            </div>
        </div>
    </div>
</template>

<script>

import InboxNav from './InboxNav.vue'
import InboxLabels from './InboxLabels.vue'
import InboxChat from './InboxChat.vue'

export default {
    name: 'EmailSidebar',
    components: {
        'inbox-nav': InboxNav,
        'inbox-labels': InboxLabels,
        'inbox-chat': InboxChat
    },

}
</script>

<style lang="scss">
.email-sidebar {
    padding: 2rem;
    background-color: var(--default-card-color);
}

// COMPOSE BUTTON STYLES
.compose-btn {
    background-color: var(--blue-btn);
    color: white;
}

.compose-btn:hover {
    background-color: var(--blue-light-btn);
    color: white;
}
</style>
