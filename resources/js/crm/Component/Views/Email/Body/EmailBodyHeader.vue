<template>
    <div class="email-body-header d-flex align-items-center justify-content-between border-bottom pb-primary">
        <div>
            <h5 class="mb-0">
                {{ $t('meeting_with_new_investors') }}
            </h5>
            <p class="text-muted text-size-12 mb-0">
                Today, 16th December 2021, 11:26am
            </p>
        </div>

        <div class="d-flex align-items-center">
            <a href="#" class="popup-parent text-muted px-2">
                <app-icon name="star" />
                <span class="popup rounded py-1 px-2">{{ $t('add_to_favourites') }}</span>
            </a>
            <a href="#" class="popup-parent text-muted px-2">
                <app-icon name="printer" />
                <span class="popup rounded py-1 px-2">{{ $t('print') }}</span>
            </a>
            <a href="#" class="popup-parent text-muted px-2">
                <app-icon name="trash" />
                <span class="popup rounded py-1 px-2">{{ $t('move_to_trash') }}</span>
            </a>

            <div class="dropdown actions-dropdown">
                <button type="button" class="btn-option btn dropdown-btn" data-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                </button>
                <dropdown-menu /> 
            </div>
        </div>
    </div> 
</template>

<script>
import DropdownMenu from '../DropdownMenu.vue';

export default {
    name: 'email-body-header',
    components: {
        'dropdown-menu': DropdownMenu
    }
}

</script>

<style lang="scss">
.dropdown-btn {
    $btn-dimension: 1.75rem;

    padding: 0;
    border-radius: 50%;
    color: #fff;
    width: $btn-dimension;
    height: $btn-dimension;

    &:hover {
        color: var(--blue-light-btn);
        background: var(--label-btn-color);
    }
}
</style>
