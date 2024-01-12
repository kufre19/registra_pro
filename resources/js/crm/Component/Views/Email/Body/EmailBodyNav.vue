<template>
    <ul class="email-body-nav list-unstyled d-flex flex-column align-items-center p-4">
        <email-body-nav-link :isActive="true"/>
        <email-body-nav-link />
        <email-body-nav-link />
        <email-body-nav-link />
    </ul>
</template>

<script>
import NavLink from './EmailBodyNavLink.vue'

export default {
    name: 'email-body-nav',
    components: {
        'email-body-nav-link': NavLink,
    }
}
</script>

<style lang="scss">
.email-body-nav {
    background: var(--email-body-color);
    margin-bottom: 0;
}

$breakpoint: 1200px;

@media only screen and (min-width: $breakpoint) {
    .email-body-nav {
        flex: 2;
    }    
}
</style>
