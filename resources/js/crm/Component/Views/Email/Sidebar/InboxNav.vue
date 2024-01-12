<template>
    <ul class="inbox-nav mb-4 list-unstyled">
        <li class="inbox-nav-link">
            <a href="#" class="d-flex justify-content-between">
                <span>
                    <app-icon name="inbox" />
                    <span class="link-text pl-3">{{ $t('inbox') }}</span>
                </span>
                <span class="badge badge-sm badge-primary ml-4">3</span>
            </a>
        </li>
        <li class="inbox-nav-link">
            <a href="#">
                <app-icon name="send" />
                <span class="link-text pl-3">{{ $t('sent_emails') }}</span>
            </a>
        </li>
        <li class="inbox-nav-link">
            <a href="#">
                <app-icon name="star" />
                <span class="link-text pl-3">{{ $t('favourite') }}</span>
            </a>
        </li>
        <li class="inbox-nav-link">
            <a href="#">
                <app-icon name="edit-3" />
                <span class="link-text pl-3">{{ $t('draft') }}</span>
            </a>
        </li>
        <li class="inbox-nav-link">
            <a href="#">
                <app-icon name="trash-2" />
                <span class="link-text pl-3">{{ $t('deleted') }}</span>
            </a>
        </li>
        <li class="inbox-nav-link">
            <a data-toggle="collapse" href="#more-menus">
                <app-icon name="chevron-down" />
                <span class="link-text pl-2">
                    {{ $t('more') }}
                </span>
            </a>

            <div class="collapse" id="more-menus">
                <ul class="">
                    <li class="my-3">
                        <a href="#">
                            Dropdown 1
                        </a>
                    </li>

                    <li class="my-3">
                        <a href="#">
                            Dropdown 2
                        </a>
                    </li>
                </ul>
            </div>
                                            
        </li>
    </ul>
</template>

<script>
export default {
    name: 'inbox-nav',
}
</script>

<style lang="scss">
.inbox-nav-link {
    margin-bottom: 1rem;

    a {
        color: var(--inbox-nav-color);
        transition: 150ms;
    
        &:hover {
            color: var(--blue)
        }
    }

    .badge {background-color: var(--blue-btn);}
}
</style>
