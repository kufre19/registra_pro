<template>
    <div class="inbox-labels mb-4 w-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-gray">{{$t('labels')}}</span>
            <div class="d-flex align-items-center">
                <button class="label-btn add-btn btn width-30 height-30 bg-base rounded d-flex flex-shrink-0 align-items-center justify-content-center p-0 mr-2" type="button">
                    <app-icon name="plus" class="label-btn-icon" />
                </button>

                <div class="dropdown actions-dropdown px-0">
                    <button type="button" class="label-btn btn-option btn dropdown-btn" data-toggle="dropdown">
                        <app-icon name="more-vertical" class="label-btn-icon" />
                    </button>
                    <dropdown-menu />
                </div>
            </div>
        </div>

        <ul class="labels-list list-unstyled">
            <li class="label-list-item">
                <a href="#" class="label-link d-flex align-items-center">
                    <app-icon name="tag" class="label-icon cyan" />
                    <span class="label-text pl-2 text-gray">{{$t('promising_offers')}}</span>
                </a>
            </li>
            <li class="label-list-item">
                <a href="#" class="label-link d-flex align-items-center ">
                    <app-icon name="tag" class="label-icon green" />
                    <span class="label-text pl-2 text-gray">{{$t('work_in_progress')}}</span>
                </a>
            </li>
            <li class="label-list-item">
                <a href="#" class="label-link d-flex align-items-center ">
                    <app-icon name="tag" class="label-icon orange" />
                    <span class="label-text pl-2 text-gray">{{$t('support')}}</span>
                </a>
            </li>
            <li class="label-list-item">
                <a href="#" class="label-link d-flex align-items-center ">
                    <app-icon name="tag" class="label-icon blue" />
                    <span class="label-text pl-2 text-gray">{{$t('promotions')}}</span>
                </a>
            </li>
            <li class="label-list-item">
                <a href="#" class="label-link d-flex align-items-center ">
                    <app-icon name="tag" class="label-icon red" />
                    <span class="label-text pl-2 text-gray">{{$t('read_later')}}</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import DropdownMenu from '../DropdownMenu';

export default {
    name: 'inbox-labels',
    components: {
        'dropdown-menu': DropdownMenu,
    }
}
</script>

<style lang="scss">
.inbox-labels {width: 100%;}

.dropdown-btn {
    padding: 0;
    border-radius: 50%;

    & > svg {
        color: var(--blue-light-btn);
    }
}

.add-btn {
    color: var(--blue-light-btn);
    background-color: var(--white-base-color);
}

.add-btn:hover, .actions-dropdown .dropdown-btn:hover {
    color: var(--blue-light-btn);
    background-color: var(--white-base-color);
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
}

.label-list-item {margin-bottom: .5rem;}

/* label icon colors */
.cyan   {color: var(--cyan);}
.green  {color: var(--green);}
.orange {color: var(--orange);}
.blue   {color: var(--blue);}
.red    {color: var(--red);}

$label-scale: 1;
$label-scale-lrg: 0.9;

.label-icon,
.label-btn-icon {
    transform: scale($label-scale);
}

.label-text:hover {
    color: var(--blue);
}
</style>
