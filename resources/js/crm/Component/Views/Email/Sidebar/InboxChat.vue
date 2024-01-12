<template>
    <div class="inbox-chat">
        <p class="text-gray mb-3">{{ $t('chat') }}</p>

        <app-avatars-group 
            :avatar-group-class="`avatars-group-w-60`" 
            :avatars="avatars" 
            :shadow="true" 
            :border="true"
        />

    </div>
</template>

<script>
export default {
    name: 'inbox-chat',
    data() {
        return {
            avatars: [
                {
                    img :  'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg', 
                    alt : 'avatar', 
                    title: 'Barry Allen', 
                    subTitle: 'Fastest man alive'
                },
                {
                    img :  'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg', 
                    alt : 'avatar', 
                    title: 'Barry Allen', 
                    subTitle: 'Fastest man alive'
                },
                {
                    img :  'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg', 
                    alt : 'avatar', 
                    title: 'Barry Allen', 
                    subTitle: 'Fastest man alive'
                },
                {
                    img :  'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg', 
                    alt : 'avatar', 
                    title: 'Barry Allen', 
                    subTitle: 'Fastest man alive'
                },
            ],
            temporaryChatheadImg: 'https://www.publicdomainpictures.net/pictures/360000/nahled/sample-black-stamp-text-on-blue-1594663246MRK.jpg',
        }
    }
}
</script>

<style lang="scss">
.avatar-group {
    img {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        border: 2px solid var(--avatar-border-color);
    }
}

.avatar-with-status {
    position: relative;

   &:hover {
       transition: 200ms;
       transform: translateY(-5%) scale(1.05);
   }

    &::after  {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        bottom: 4px;
        right: 4px;
        background-color: rgb(23, 162, 184);
    }

    &.danger::after {background-color: var(--red);}
    &.success::after {background-color: var(--green);}
    &.info::after {background-color: var(--blue);}
    &.warning::after {background-color: var(--orange);}
    &.secondary::after {background-color: var(--gray);}

    &:hover .user-name {
        top: -63%;
        opacity: 1;
    }

    .user-name {
        background-color: #333;
        position: absolute;
        white-space: nowrap;
        transition: 250ms;
        left: 50%;
        top: -55%;
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
