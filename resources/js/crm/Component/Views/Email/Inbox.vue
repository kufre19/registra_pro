<template>
    <section class="email-inbox content-wrapper">
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
                   <email-body />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import EmailSidebar from './Sidebar/EmailSidebar.vue'
import EmailBody from './Body/EmailBody.vue'

export default {
    name: 'Inbox',
    components:{
        'email-sidebar': EmailSidebar,
        'email-body': EmailBody
    },
    data() {
        return {
            breadcrumbButton: {
                label: 'Inbox',
                url: '/email/inbox',
            },
            temporaryChatheadImg: 'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg',
        }
    },
}
</script>

<style lang="scss">
:root {
    --blue-btn: #2799f6;
    --blue-light-btn: #36A0F7;
    --inbox-nav-color: #9397A0;
    --label-btn-color: rgb(28, 31, 38);
    --avatar-border-color: #566076;
    --email-body-color: rgba(54, 160, 247, 0.05);
    --default-card-color: var(--navbar-bg);

    --gray: #6c757d;
    --cyan: rgb(23, 162, 184);
    --green: rgb(40, 167, 69);
    --orange: rgb(252, 101, 16);
    --blue: rgb(0, 123, 255);
    --red: rgb(252, 44, 16);
}

.text-gray {color: var(--gray);}

 
.popup-parent {
    display: block;
    position: relative;

    svg:hover {
        color: var(--blue);
    }

    &:hover span {
        bottom: 150%;
        opacity: 1;
    }

    span {
        background-color: #333;
        color: #ffffff;
        position: absolute;
        white-space: nowrap;
        transition: 250ms;
        left: 50%;
        bottom: 95%;
        opacity: 0;
        transform: translate(-50%);
        z-index: 99;

        &::after {
            content: '';
            position: absolute;
            bottom: -14px;
            left: 50%;
            transform: translate(-50%);
            width: 10px;
            height: 10px;
            border: 7px solid #333;
            border-left-color: transparent;
            border-right-color: transparent;
            border-bottom-color: transparent;
        }
    }
}
</style>
