<template>
    <section class="email-compose content-wrapper">
        <app-breadcrumb
            :page-title="$t('email')"
            :button="breadcrumbButton"
        />

        <div class="email-template-wrapper">
            <div class="row align-items-stretch h-100">
                <div class="col-12 col-md-5 col-lg-12 col-xl-3">
                   <email-sidebar /> 
                </div>

                <div class="col-12 col-md-7 col-lg-12 col-xl-9">
                    <email-compose />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import EmailComposeVue from './EmailCompose/EmailCompose.vue'
import EmailSidebarVue from './Sidebar/EmailSidebar.vue'

export default {
    name: 'Compose',
    components: {
        'email-sidebar': EmailSidebarVue,
        'email-compose': EmailComposeVue,
    },
    data() {
        return {
            breadcrumbButton: {
                label: 'Compose',
                url: '/email/compose',
            }
        }
    },
    computed: {

    },
    methods: {

    }
}
</script>
