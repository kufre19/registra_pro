<template>
    <div class="email-body rounded shadow d-flex flex-column flex-lg-row">
        <email-body-nav /> 

        <div class="email-body-content p-4">
            <div class="tab-content h-100 mail-details">
                <div class="tab-pane fade show active" id="inbox-mail-1">
                    <email-body-header /> 

                    <!-- Message -->
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <app-avatar 
                                    title="Sender img" 
                                    avatar-class="avatars-w-40" 
                                    :img="temporaryChatheadImg" 
                                    alt-text="Sender img" 
                                />
                            </div>
                            <p class="mb-0">Ivana Alexandria <span class="text-muted text-size-12">- Hi, I have new meeting opportunity...</span>
                            </p>
                        </div>
                        <p class="text-muted mb-0">14.09.2021 09:50</p>
                    </div>

                    <!-- Reply -->
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3 pl-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-reply text-muted mr-4"></i>
                            <p class="mb-0">You <span class="text-muted text-size-12">- Hi, Thanks to inform. We'll need to...</span>
                            </p>
                        </div>
                        <p class="text-muted mb-0">14.09.2021 10:15</p>
                    </div>

                    <!-- Message -->
                    <div>
                        <div class="d-flex align-items-center justify-content-between py-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <app-avatar 
                                        title="Sender img" 
                                        avatar-class="avatars-w-40" 
                                        :img="temporaryChatheadImg" 
                                        alt-text="Sender img" 
                                    />
                                </div>
                                <p class="mb-0">Ivana Alexandria</p>
                            </div>
                            <p class="text-muted mb-0">14.09.2021 09:50</p>
                        </div>
                        <div>
                            <p class="text-secondary">Hey Jontray,</p>
                            <p class="text-muted">
                                Praesent dui ex, dapibus eget mauris ut, finibus vestibulum
                                enim. Quisque arcu leo, facilisis in fringilla id, luctus in
                                tortor. Nunc vestibulum est quis orci varius viverra.
                                Curabitur dictum volutpat massa vulputate molestie. In at
                                felis ac velit maximus convallis.
                            </p>
                            <p class="text-muted">
                                Regards, <br>
                                Jessica
                            </p>
                        </div>
                    </div>

                    <!-- Reply Editor -->
                    <reply-editor /> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EmailBodyHeader from './EmailBodyHeader.vue';
import DropdownMenu from '../DropdownMenu.vue';
import EmailBodyNavVue from './EmailBodyNav.vue';
import ReplyEditor from './ReplyEditor.vue';

export default {
    name: 'email-body',
    components: {
        'email-body-header': EmailBodyHeader,
        'email-body-nav': EmailBodyNavVue,
        'dropdown-menu': DropdownMenu,
        'reply-editor': ReplyEditor,
    },
    data() {
        return {
            temporaryChatheadImg: 'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg',
        }
    }
}
</script>

<style lang="scss">
    img.avatar {
        border-radius: 50%;
        width: 50px;
        height: 50px;
    }

    .email-body {
        background-color: var(--default-card-color);

        .email-body-content {
            flex: 3;
        }
    }

    .nav-container {
        height: 100%;
    }
</style>
