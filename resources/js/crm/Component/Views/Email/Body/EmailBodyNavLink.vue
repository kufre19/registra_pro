<template>
    <li class="email-body-nav-link w-100">
        <a href="#" :class="{'active': isActive}" class="d-block w-100 h-100 p-4">
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="mr-2">
                        <app-avatar
                            title="Sender img"
                            avatar-class="avatars-w-40"
                            :img="temporaryChatheadImg"
                            alt-text="Sender img"
                        />
                    </div>
                    <div>
                        <p class="sender-name text-default mb-0">
                            SENDER_NAME
                        </p>
                        <p class="email-content text-muted mb-0">
                            Lorem, consectetur adipisici...
                        </p>
                    </div>
                </div>
                <div class="sent-at text-muted text-size-11">
                    11:00
                </div>
            </div>
        </a>
    </li>
</template>

<script>
export default {
    name: 'email-body-nav-link',
    props: {
        isActive: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            temporaryChatheadImg: 'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg',
        }
    }
}
</script>

<style lang="scss">
.email-body-nav-link {
    width: 80%;

    a.active {
        background-color: var(--white-base-color);
        border-radius: 0.25rem;
        box-shadow: var(--default-box-shadow);
    }

    p.sender-name {
        color: var(--blue);
    }
}
</style>
