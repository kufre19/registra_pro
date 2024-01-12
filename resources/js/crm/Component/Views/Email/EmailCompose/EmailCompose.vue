<template>
    <form class="email-compose-form rounded shadow">
        <div class="user-input row align-items-center mb-4">
            <label for="recipient" class="mb-0 col-sm-2">{{ $t('to') }}</label>
            <app-input
                class="mb-0 col-sm-10"
                id="recipient"
                type="select"
                :list="[recipients]"
                v-model="recipientValue"
            />
        </div>

        <div class="user-input row align-items-center mb-4">
            <label for="subject" class="mb-0 col-sm-2">{{
                $t('subject')
            }}</label>
            <app-input
                class="mb-0 col-sm-10"
                id="subject"
                type="text"
                v-model="subjectValue"
            />
        </div>

        <div class="user-input row align-items-center">
            <label for="message" class="mb-3 col-sm-3">{{
                $t('message')
            }}</label>
            <app-input class="col-sm-10 offset-sm-2" id="message" type="text-editor" v-model="messageValue" />
        </div>

        <div class="form-control-wrapper mt-3 row">
            <div
                class="form-control d-flex align-items-center justify-content-end col-sm-10 offset-sm-2"
            >
                <a href="#" class="popup-parent text-muted px-2">
                    <app-icon name="paperclip" />
                    <span class="popup rounded py-1 px-2">{{
                        $t('attach_files')
                    }}</span>
                </a>
                <a href="#" class="popup-parent text-muted px-2">
                    <app-icon name="save" />
                    <span class="icon-title rounded py-1 px-2">{{
                        $t('save_to_drafts')
                    }}</span>
                </a>
                <a href="#" class="popup-parent text-muted px-2">
                    <app-icon name="trash" />
                    <span class="icon-title rounded py-1 px-2">{{
                        $t('dismiss_reply')
                    }}</span>
                </a>
                <a href="#" class="popup-parent text-muted px-2">
                    <app-icon name="settings" />
                    <span class="icon-title rounded py-1 px-2">{{
                        $t('more_actions')
                    }}</span>
                </a>

                <div class="btn-group popup ml-2">
                    <button type="button" class="btn btn-primary">
                        {{ $t('send') }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                    >
                        <span class="sr-only">
                            {{ $t('toggle_dropdown') }}
                        </span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right" style="">
                        <a class="dropdown-item" href="#">{{
                            $t('schedule_send')
                        }}</a>
                        <a class="dropdown-item" href="#">{{ $t('cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import { FormSubmitMixin } from '../../../../Mixins/Global/FormSubmitMixin';

export default {
    name: 'email-compose',
    mixins: [FormSubmitMixin],
    data() {
        return {
            recipientValue: '',
            subjectValue: '',
            messageValue: '',
            recipients: [
                { id: 1, value: 'Chester Whipplefilter' },
                { id: 2, value: 'Brent Mustangburger' },
                { id: 3, value: 'Francesco Bernoulli' },
                { id: 4, value: 'Cruz Ramirez' },
            ],
        };
    },
};
</script>

<style lang="scss" scoped>
.email-compose-form {
    background-color: var(--default-card-color);
    padding: 2rem;
}

.feather {
    width: 1.125rem;
    height: 1.125rem;
}

.form-control {
    background-color: none;

    svg:hover {
        color: var(--blue);
    }
}

.btn-group {
    .btn {
        background-color: var(--blue-btn);
        border: none;
        outline: none;

        &:hover {
            background-color: var(--blue-light-btn);
        }
    }
}

// overriding the existing active style
.form-control .btn-group .btn:active {
    background-color: var(--blue);
}
</style>
